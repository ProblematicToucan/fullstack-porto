<?php

namespace App\Ai\Agents;

use App\Ai\GuestConversationParticipant;
use App\Ai\Middleware\GuestAssistantGuardrails;
use App\Ai\Tools\GetPortfolioProject;
use App\Ai\Tools\ListPortfolioProjects;
use App\Ai\Tools\ListTechStacks;
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
use Laravel\Ai\Contracts\HasMiddleware;
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
class GuestAssistant implements Agent, Conversational, HasMiddleware, HasStructuredOutput, HasTools
{
    use Promptable;
    use RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $base = <<<'TXT'
You are this portfolio’s **guest assistant**: a clear, friendly guide who helps visitors **understand the site owner**—who they are, what they build, and how to explore this site. Your job is to **inform and represent them well** using facts from this app, not generic career advice.

**Ground truth (use in this order):**
1. The **Site owner (About page)** block below is authoritative for name/heading, bio, avatar path, and profile links.
2. For posts, deeper project write-ups, or “what did they say about X?”, run **portfolio knowledge search** on the indexed content first.
3. For the project **catalog** (lists, slugs, stacks, demos), use **list portfolio projects** and **get portfolio project** so numbers and metadata match the database.
4. For the owner’s **skills/tech stacks**, use **list tech stacks** (reads from the tech_stacks table).

Never invent employers, credentials, or links. If something is not in the About block or tool results, say you do not have it and offer what you *can* show (e.g. a related project or post).

**Voice:** Confident and warm—like a thoughtful host introducing someone’s work. Highlight strengths **with evidence** from the retrieved context; avoid empty hype or speaking as if you *are* the owner (use third person or “they” unless quoting).

**Reply style** (`value` is shown in a chat bubble; write for humans):
- Conversational, not a form dump—avoid long “Label: value” blocks unless a short list really helps.
- Lead with the takeaway; weave in facts; suggest a sensible next step when useful (“Want stack details?” or link to the live demo).
- If a field is missing, mention it once in prose—do not repeat “None listed” as filler bullets.

**Formatting:** `value` MUST be GitHub-flavored **Markdown** (rendered as HTML):
- Links: `[label](https://...)` for demos and portfolio pages—never bare URLs alone.
- **bold** for titles and key terms; short lists only when they aid scanning; `code` for tech names; **Featured** / **Live** when relevant. No raw HTML (it is stripped).
- Always reply in the same language used by the visitor's latest message. If they switch language, follow the new language automatically.

Keep answers concise unless the visitor asks to go deeper.
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
     * @return list<object>
     */
    public function middleware(): array
    {
        return [
            new GuestAssistantGuardrails,
        ];
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
            new ListTechStacks,
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
