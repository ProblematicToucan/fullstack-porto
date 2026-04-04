<?php

use App\Ai\Knowledge\PortfolioContentExtractor;

it('omits image blocks and keeps paragraph and code text', function (): void {
    $blocks = [
        ['type' => 'paragraph', 'data' => ['text' => '<p>Hello <strong>world</strong></p>']],
        ['type' => 'image', 'data' => ['image' => 'x.png']],
        ['type' => 'code', 'data' => ['text' => '$foo = 1;']],
    ];

    $plain = PortfolioContentExtractor::plainTextFromBuilderContent($blocks);

    expect($plain)->toContain('Hello world')
        ->and($plain)->toContain('$foo = 1;')
        ->and($plain)->not->toContain('x.png');
});

it('accepts legacy string content', function (): void {
    expect(PortfolioContentExtractor::plainTextFromBuilderContent('  plain  '))->toBe('plain');
});
