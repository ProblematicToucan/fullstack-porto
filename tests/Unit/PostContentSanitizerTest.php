<?php

use App\Support\PostContentSanitizer;

it('renders bullet lists from TipTap doc JSON', function () {
    $doc = [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'bulletList',
                'content' => [
                    [
                        'type' => 'listItem',
                        'content' => [
                            [
                                'type' => 'paragraph',
                                'content' => [
                                    ['type' => 'text', 'text' => 'First item'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = PostContentSanitizer::sanitize($doc);

    expect($html)->toContain('<ul>')
        ->and($html)->toContain('<li>')
        ->and($html)->toContain('First item');
});

it('renders bullet lists inside Filament builder paragraph blocks', function () {
    $blocks = [
        [
            'type' => 'paragraph',
            'data' => [
                'text' => [
                    'type' => 'doc',
                    'content' => [
                        [
                            'type' => 'bulletList',
                            'content' => [
                                [
                                    'type' => 'listItem',
                                    'content' => [
                                        [
                                            'type' => 'paragraph',
                                            'content' => [
                                                ['type' => 'text', 'text' => 'From builder'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = PostContentSanitizer::sanitize($blocks);

    expect($html)->toContain('<ul>')
        ->and($html)->toContain('<li>')
        ->and($html)->toContain('From builder');
});
