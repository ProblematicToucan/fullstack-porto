<?php

use App\Models\Post;
use App\Models\Project;

describe('Landing', function () {
    test('GET / returns 200', function () {
        $response = $this->get('/');
        $response->assertStatus(200);
    });

    test('GET route home returns 200', function () {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
    });
});

describe('Projects', function () {
    test('GET project index returns 200', function () {
        $response = $this->get(route('project.index'));
        $response->assertStatus(200);
    });

    test('GET project show with existing slug returns 200', function () {
        Project::factory()->create(['slug' => 'foo']);
        $response = $this->get(route('project.show', 'foo'));
        $response->assertStatus(200);
    });

    test('GET project show with nonexistent slug returns 404', function () {
        $response = $this->get(route('project.show', 'nonexistent'));
        $response->assertStatus(404);
    });
});

describe('Posts', function () {
    test('GET post index returns 200', function () {
        $response = $this->get(route('post.index'));
        $response->assertStatus(200);
    });

    test('GET post show with public post slug returns 200', function () {
        Post::factory()->create(['slug' => 'bar', 'is_public' => true]);
        $response = $this->get(route('post.show', 'bar'));
        $response->assertStatus(200);
    });

    test('GET post show with non-public post slug returns 404', function () {
        Post::factory()->private()->create(['slug' => 'private']);
        $response = $this->get(route('post.show', 'private'));
        $response->assertStatus(404);
    });
});

describe('About', function () {
    test('GET about returns 200', function () {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
    });
});
