<?php

namespace App\Ai\Agents;

use App\Ai\GuestConversationParticipant;
use App\Models\KnowledgeChunk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Tools\SimilaritySearch;
use Stringable;

#[Provider(Lab::OpenAI)]
#[Model('gpt-5.4-mini')]
#[Temperature(0.1)]
class GuestAssistant implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a helpful assistant that can help users with their questions and about this personal portfolio website. When the user asks about blog posts, projects, or specific work on this site, use the portfolio knowledge search tool to retrieve relevant indexed content before answering.';
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
        return [
            SimilaritySearch::usingModel(
                KnowledgeChunk::class,
                'embedding',
                minSimilarity: 0.4,
                limit: 12,
                query: fn ($query) => $query->searchable(),
            )->withDescription('Search indexed text from this portfolio\'s public blog posts and projects. Use for factual questions about posts, projects, or the site owner\'s work.'),
        ];
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

    /**
     * Turn persisted assistant {@code content} into user-facing text.
     *
     * Conversation memory stores structured replies as JSON in {@code agent_conversation_messages.content}
     * (the SDK persists {@see AgentResponse::$text}, which for structured output is the raw JSON string).
     */
    public static function formatStoredAssistantContent(string $rawContent): string
    {
        $decoded = json_decode($rawContent, true);

        if (\is_array($decoded) && \array_key_exists('value', $decoded)) {
            return (string) $decoded['value'];
        }

        return $rawContent;
    }
}
