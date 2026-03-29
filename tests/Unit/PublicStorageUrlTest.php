<?php

use App\Support\PublicStorageUrl;
use Tests\TestCase;

uses(TestCase::class);

test('returns empty string for null or empty path', function () {
    expect(PublicStorageUrl::url(null))->toBe('');
    expect(PublicStorageUrl::url(''))->toBe('');
});

test('returns absolute http urls unchanged', function () {
    expect(PublicStorageUrl::url('https://cdn.example.com/x.png'))->toBe('https://cdn.example.com/x.png');
    expect(PublicStorageUrl::url('http://example.com/y.png'))->toBe('http://example.com/y.png');
});

test('returns root-relative paths unchanged', function () {
    expect(PublicStorageUrl::url('/storage/foo.png'))->toBe('/storage/foo.png');
});

test('resolves stored paths against uploads disk', function () {
    config(['filesystems.uploads_disk' => 'public']);

    $url = PublicStorageUrl::url('projects/test.jpg');

    expect($url)->toContain('projects/test.jpg');
});
