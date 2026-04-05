<?php

namespace App\Ai\Agents;

use App\Ai\GuestConversationParticipant;
use App\Ai\Tools\GetPortfolioProject;
use App\Ai\Tools\ListPortfolioProjects;
use App\Ai\Tools\PortfolioKnowledgeSearch;
use App\Models\About;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
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
#[Temperature(0.55)]
class GuestAssistant implements Agent, Conversational, HasStructuredOutput, HasTools
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $base = <<<'TXT'
You are a helpful assistant for this personal portfolio website. Use the following About-page context as ground truth for who is your owner and runs this site, their bio, and links.

When the user asks about blog posts, projects, or specific work on this site, use the portfolio knowledge search tool to retrieve relevant indexed content before answering. For the project catalog (listing projects or full details by slug), use the list portfolio projects and get portfolio project tools so your facts match the database.

**Reply style (the `value` you return is shown in a chat bubble; write for humans):**
- Sound natural and conversational—like a knowledgeable colleague, not a form or database dump.
- Do **not** answer with long "Label: value" lines or robotic bullet inventories unless a short list truly helps (e.g. comparing options).
- Weave facts into sentences. Lead with the takeaway, then add useful detail. Offer a clear next step when it fits (e.g. "Want the tech stack?" or "I can link you to the portfolio page.").
- If something is missing (categories, stack, media), mention it briefly in prose instead of repeating "None listed" as a list item.

**Formatting:** The `value` field MUST be GitHub-flavored **Markdown** (it will be rendered as HTML). Use it for clarity:
- Links: `[readable label](https://...)` for live demos, portfolio pages, and references—never bare URLs as the only text.
- Emphasis: **bold** for project titles or key terms; use short bullet or numbered lists only when they improve scanning.
- Inline "badge-like" labels: use **Featured**, **Live**, or `code` for tech names when helpful—avoid HTML tags (they are stripped).

Keep answers concise when possible; expand when the user asks for depth.
TXT;

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
            'value' => $schema->string()
                ->required()
                ->description('Assistant reply as GitHub-flavored Markdown: conversational prose, markdown links for URLs, **bold** for emphasis; no raw HTML.'),
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

    /**
     * Convert assistant markdown to safe HTML for the guest chat UI.
     */
    public static function renderAssistantMessageHtml(string $markdown): HtmlString
    {
        $html = Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        // CommonMark emits <a href="...">; open external references in a new tab.
        if (str_contains($html, '<a ')) {
            $html = preg_replace('/<a\s+/', '<a target="_blank" rel="noopener noreferrer" ', $html) ?? $html;
        }

        return new HtmlString($html);
    }
}
