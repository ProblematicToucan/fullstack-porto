<?php

use Illuminate\Support\Facades\Blade;

test('link with target blank omits wire navigate for external navigation', function () {
    $html = Blade::render(
        '<x-ui.button href="https://example.com/demo" target="_blank" rel="noopener noreferrer">View</x-ui.button>'
    );

    expect($html)->not->toContain('wire:navigate');
    expect($html)->toContain('href="https://example.com/demo"');
});

test('internal path link uses wire navigate', function () {
    $html = Blade::render(
        '<x-ui.button href="/projects">List</x-ui.button>'
    );

    expect($html)->toContain('wire:navigate');
    expect($html)->toContain('href="/projects"');
});

test('mailto link omits wire navigate', function () {
    $html = Blade::render(
        '<x-ui.button href="mailto:a@b.com">Email</x-ui.button>'
    );

    expect($html)->not->toContain('wire:navigate');
});
