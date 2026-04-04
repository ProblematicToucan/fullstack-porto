<?php

namespace App\Ai;

use App\Ai\Agents\GuestAssistant;
use Laravel\Ai\Concerns\RemembersConversations;

/**
 * Stand-in participant for Laravel AI conversation memory when there is no authenticated user.
 *
 * The AI SDK's {@see RemembersConversations} middleware only runs when
 * {@see RemembersConversations::hasConversationParticipant()} is true.
 * A nullable {@see $id} maps to null {@code user_id} rows in {@code agent_conversations} /
 * {@code agent_conversation_messages}.
 *
 * Do not use {@see RemembersConversations::continueLastConversation()} for
 * guests: multiple guests share {@code user_id} null, so always pass an explicit conversation id
 * from the client (e.g. session or local storage) via {@see GuestAssistant::continueGuestConversation()}.
 */
final class GuestConversationParticipant
{
    public function __construct(public ?int $id = null) {}
}
