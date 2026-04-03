<?php

namespace App\Ai\Agents;

use App\Ai\GuestConversationParticipant;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;
use Stringable;

class GuestAssistant implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant that can help users with their questions and about this personal portfolio website.';
    }

    /**
     * Start or continue guest chat: conversation rows use null {@code user_id}.
     *
     * Persist the returned {@see AgentResponse::$conversationId} client-side
     * and pass it to {@see continueGuestConversation()} on later requests.
     */
    public function forGuest(): static
    {
        return $this->forUser(new GuestConversationParticipant);
    }

    /**
     * Resume a guest thread using the conversation id from a prior response.
     */
    public function continueGuestConversation(string $conversationId): static
    {
        return $this->continue($conversationId, new GuestConversationParticipant);
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
