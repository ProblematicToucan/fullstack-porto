<?php

use App\Ai\Agents\GuestAssistant;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Responses\StructuredAgentResponse;

it('persists guest conversation memory without a user id', function (): void {
    // First prompt: main reply, then title generation for the new conversation (RememberConversation).
    GuestAssistant::fake([
        ['value' => 'First reply'],
        'Chat title',
        ['value' => 'Second reply'],
    ]);

    $first = (new GuestAssistant)->forGuest()->prompt('Hello');

    expect($first->conversationId)->not->toBeNull()
        ->and(DB::table('agent_conversations')->whereNull('user_id')->count())->toBe(1)
        ->and(DB::table('agent_conversation_messages')->where('conversation_id', $first->conversationId)->count())->toBe(2);

    $second = (new GuestAssistant)->continueGuestConversation($first->conversationId)->prompt('Follow up');

    if (! $second instanceof StructuredAgentResponse) {
        throw new \UnexpectedValueException('Expected structured agent response.');
    }

    expect($second->conversationId)->toBe($first->conversationId)
        ->and(DB::table('agent_conversation_messages')->where('conversation_id', $first->conversationId)->count())->toBe(4)
        ->and($second->structured['value'])->toBe('Second reply');
});
