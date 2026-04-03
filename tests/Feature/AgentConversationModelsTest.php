<?php

use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\User;

it('relates a conversation to its messages and optional user', function (): void {
    $user = User::factory()->create();
    $conversation = AgentConversation::factory()
        ->forUser($user)
        ->has(AgentConversationMessage::factory()->count(2)->forUser($user), 'messages')
        ->create();

    expect($conversation->user)->toBeInstanceOf(User::class)
        ->and($conversation->user->is($user))->toBeTrue()
        ->and($conversation->messages)->toHaveCount(2)
        ->and($conversation->messages->first()->conversation->is($conversation))->toBeTrue();
});

it('casts message JSON columns to arrays', function (): void {
    $message = AgentConversationMessage::factory()->create([
        'tool_calls' => [['id' => 'call_1', 'name' => 'search', 'arguments' => '{}']],
        'usage' => ['input_tokens' => 10],
    ]);

    expect($message->tool_calls)->toBeArray()
        ->and($message->tool_calls[0]['name'])->toBe('search')
        ->and($message->usage['input_tokens'])->toBe(10);
});

it('lists agent conversations on the user', function (): void {
    $user = User::factory()->create();
    AgentConversation::factory()->count(2)->forUser($user)->create();

    expect($user->agentConversations)->toHaveCount(2);
});
