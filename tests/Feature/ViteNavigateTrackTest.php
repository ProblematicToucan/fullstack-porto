<?php

test('vite entry tags omit data navigate track reload for wire navigate performance', function () {
    $html = $this->get('/')->assertSuccessful()->getContent();

    expect($html)->not->toContain('data-navigate-track="reload"');
});
