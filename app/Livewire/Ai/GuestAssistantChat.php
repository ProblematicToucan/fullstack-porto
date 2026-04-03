<?php

namespace App\Livewire\Ai;

use App\Ai\Agents\GuestAssistant;
use App\Models\AgentConversationMessage;
use Illuminate\Contracts\View\View;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Livewire\Component;
use Throwable;
use UnexpectedValueException;

class GuestAssistantChat extends Component
{
    private const SESSION_CONVERSATION_KEY = 'guest_assistant_conversation_id';

    public bool $isOpen = false;

    public string $message = '';

    /**
     * @var array<int, array{role: string, content: string}>
     */
    public array $thread = [];

    public ?string $error = null;

    public function mount(): void
    {
        $this->refreshThread();
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;

        if ($this->isOpen) {
            $this->refreshThread();
        }
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function send(): void
    {
        $this->validate([
            'message' => ['required', 'string', 'max:10000'],
        ]);

        $this->error = null;

        try {
            $conversationId = session(self::SESSION_CONVERSATION_KEY);

            $agent = $conversationId !== null
                ? (new GuestAssistant)->continueGuestConversation($conversationId)
                : (new GuestAssistant)->forGuest();

            $response = $agent->prompt($this->message);

            if (! $response instanceof StructuredAgentResponse) {
                throw new UnexpectedValueException('Expected structured assistant response.');
            }

            session([self::SESSION_CONVERSATION_KEY => $response->conversationId]);
            $this->message = '';
            $this->refreshThread();
        } catch (Throwable $e) {
            report($e);
            $this->error = __('Something went wrong. Please try again.');
        }
    }

    /**
     * Reload visible messages from the database for the current session conversation.
     */
    private function refreshThread(): void
    {
        $conversationId = session(self::SESSION_CONVERSATION_KEY);

        if ($conversationId === null || $conversationId === '') {
            $this->thread = [];

            return;
        }

        $this->thread = AgentConversationMessage::query()
            ->where('conversation_id', $conversationId)
            ->orderBy('created_at')
            ->get(['role', 'content'])
            ->map(fn (AgentConversationMessage $m): array => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->all();
    }

    public function render(): View
    {
        return view('livewire.ai.guest-assistant-chat');
    }
}
