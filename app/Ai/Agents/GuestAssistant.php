<?php

namespace App\Ai\Agents;

use App\Ai\GuestConversationParticipant;
use App\Ai\Tools\GetPortfolioProject;
use App\Ai\Tools\ListPortfolioProjects;
use App\Ai\Tools\PortfolioKnowledgeSearch;
use App\Models\About;
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
use Stringable;

#[Provider(Lab::OpenAI)]
#[Model('gpt-5.4-mini')]
#[Temperature(0.3)]
class GuestAssistant implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $base = 'You are a helpful assistant for this personal portfolio website. Use the following About-page context as ground truth for who is your owner and runs this site, their bio, and links. When the user asks about blog posts, projects, or specific work on this site, use the portfolio knowledge search tool to retrieve relevant indexed content before answering. For structured questions about the project catalog (listing every project or full details for one project by slug), use the list portfolio projects and get portfolio project tools so your facts match the database.';

        $about = About::agentInstructionsContext();

        return $about === '' ? $base : "{$base}\n\n{$about}";
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
            new PortfolioKnowledgeSearch,
            new ListPortfolioProjects,
            new GetPortfolioProject,
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
