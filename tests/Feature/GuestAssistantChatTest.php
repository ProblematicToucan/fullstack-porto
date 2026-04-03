<?php

use App\Ai\Agents\GuestAssistant;
use App\Livewire\Ai\GuestAssistantChat;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

it('wraps the guest assistant in a persist region for SPA navigation', function (): void {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('x-persist="guest-assistant"', false);
});

it('validates message is required when sending', function (): void {
    Livewire::test(GuestAssistantChat::class)
        ->call('send')
        ->assertHasErrors(['message' => 'required']);
});

it('scopes the composer submit button loading state to the send action', function (): void {
    $html = Livewire::test(GuestAssistantChat::class)
        ->call('toggle')
        ->html();

    expect($html)->toContain('wire:target="send"');
});

it('renders optimistic send flow and agent typing markup in the chat panel', function (): void {
    $html = Livewire::test(GuestAssistantChat::class)
        ->call('toggle')
        ->html();

    expect($html)->toContain('optimisticUser')
        ->and($html)->toContain('submitSend')
        ->and($html)->toContain('wire:loading')
        ->and($html)->toContain(__('Agent is typing'));
});

it('stores conversation id in session and loads messages into the thread after send', function (): void {
    GuestAssistant::fake([
        ['value' => 'Hello from assistant'],
        'Chat title',
    ]);

    $test = Livewire::test(GuestAssistantChat::class)
        ->set('message', 'Hi there')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('message', '');

    $test->assertSessionHas('guest_assistant_conversation_id');

    $id = session('guest_assistant_conversation_id');

    $assistantRow = DB::table('agent_conversation_messages')
        ->where('conversation_id', $id)
        ->where('role', 'assistant')
        ->first();

    $assistantBubble = collect($test->instance()->thread)->firstWhere('role', 'assistant');

    expect($id)->not->toBeNull()
        ->and(DB::table('agent_conversation_messages')->where('conversation_id', $id)->count())->toBe(2)
        ->and($test->instance()->thread)->toHaveCount(2)
        ->and((string) $assistantRow->content)->toContain('value');

    expect($assistantBubble)->not->toBeNull()
        ->and($assistantBubble['content'])->toBe('Hello from assistant');
});

it('extracts the structured value key for display', function (): void {
    expect(GuestAssistant::formatStoredAssistantContent('{"value":"Hi"}'))->toBe('Hi')
        ->and(GuestAssistant::formatStoredAssistantContent('not json'))->toBe('not json');
});

it('continues the same conversation on a second message', function (): void {
    GuestAssistant::fake([
        ['value' => 'First reply'],
        'Title one',
        ['value' => 'Second reply'],
    ]);

    $test = Livewire::test(GuestAssistantChat::class)
        ->set('message', 'First')
        ->call('send');

    $firstId = session('guest_assistant_conversation_id');

    $test->set('message', 'Second')->call('send');

    expect(session('guest_assistant_conversation_id'))->toBe($firstId)
        ->and(DB::table('agent_conversation_messages')->where('conversation_id', $firstId)->count())->toBe(4)
        ->and($test->instance()->thread)->toHaveCount(4);
});

it('hydrates thread from session when opening the panel', function (): void {
    GuestAssistant::fake([
        ['value' => 'Stored reply'],
        'Stored title',
    ]);

    Livewire::test(GuestAssistantChat::class)
        ->set('message', 'Hello')
        ->call('send');

    $fresh = Livewire::test(GuestAssistantChat::class)
        ->call('toggle');

    expect($fresh->instance()->isOpen)->toBeTrue()
        ->and($fresh->instance()->thread)->toHaveCount(2);
});
