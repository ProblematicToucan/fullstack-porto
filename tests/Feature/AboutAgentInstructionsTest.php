<?php

use App\Ai\Agents\GuestAssistant;
use App\Models\About;

it('returns empty about context when no about row exists', function (): void {
    expect(About::agentInstructionsContext())->toBe('');
});

it('formats about heading body links and avatar for agent instructions', function (): void {
    About::query()->create([
        'heading' => 'Hello, I am Alex',
        'body' => 'I build Laravel apps.',
        'avatar' => 'about/photo.jpg',
        'links' => [
            'GitHub' => 'https://github.com/example',
        ],
    ]);

    $ctx = About::agentInstructionsContext();

    expect($ctx)
        ->toContain('=== Site owner (About page)')
        ->toContain('About section heading: Hello, I am Alex')
        ->toContain('I build Laravel apps.')
        ->toContain('GitHub: https://github.com/example')
        ->toContain('Avatar image (public path on this site): about/photo.jpg')
        ->toContain('===');
});

it('merges about context into guest assistant instructions', function (): void {
    About::query()->create([
        'heading' => 'Dev Portfolio',
        'body' => 'Full-stack developer.',
        'avatar' => null,
        'links' => [],
    ]);

    $instructions = (string) (new GuestAssistant)->instructions();

    expect($instructions)
        ->toContain('Ground truth')
        ->toContain('Dev Portfolio')
        ->toContain('Full-stack developer.');
});
