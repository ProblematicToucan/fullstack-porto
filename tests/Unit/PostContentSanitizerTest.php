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

it('renders builder code blocks as pre and code', function () {
    $blocks = [
        [
            'type' => 'code',
            'data' => [
                'text' => '<?php echo "ok";',
            ],
        ],
    ];

    $html = PostContentSanitizer::sanitize($blocks);

    expect($html)->toContain('<pre>')
        ->and($html)->toContain('<code>')
        ->and($html)->toContain('&lt;?php echo &#34;ok&#34;;');
});

it('renders TipTap code blocks as pre and code', function () {
    $doc = [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'codeBlock',
                'attrs' => ['language' => 'php'],
                'content' => [
                    ['type' => 'text', 'text' => '$x = 1;'],
                ],
            ],
        ],
    ];

    $html = PostContentSanitizer::sanitize($doc);

    expect($html)->toContain('<pre>')
        ->and($html)->toContain('<code>')
        ->and($html)->toContain('$x &#61; 1;');
});

it('renders inline code marks from TipTap', function () {
    $doc = [
        'type' => 'doc',
        'content' => [
            [
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'run',
                        'marks' => [
                            ['type' => 'code'],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $html = PostContentSanitizer::sanitize($doc);

    expect($html)->toContain('<code>')
        ->and($html)->toContain('run');
});
