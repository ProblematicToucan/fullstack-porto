<?php

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

describe('PostController', function () {
    test('index displays public posts', function () {
        $publicPosts = Post::factory()->count(3)->create(['is_public' => true]);
        $privatePosts = Post::factory()->count(2)->create(['is_public' => false]);

        $response = $this->get(route('post.index'));

        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
        $response->assertViewHas('posts', function (LengthAwarePaginator $posts) use ($publicPosts) {
            return $posts->count() === 3 &&
                $posts->getCollection()->pluck('id')->diff($publicPosts->pluck('id'))->isEmpty();
        });
    });

    test('index paginates posts', function () {
        Post::factory()->count(15)->create(['is_public' => true]);

        $response = $this->get(route('post.index'));

        $response->assertStatus(200);
        $response->assertViewHas('posts', function (LengthAwarePaginator $posts) {
            return $posts->perPage() === 10 && $posts->total() === 15;
        });
    });

    test('index orders posts by latest', function () {
        $oldPost = Post::factory()->create(['is_public' => true, 'created_at' => now()->subDay()]);
        $newPost = Post::factory()->create(['is_public' => true, 'created_at' => now()]);

        $response = $this->get(route('post.index'));

        $response->assertViewHas('posts', function (LengthAwarePaginator $posts) use ($newPost, $oldPost) {
            return $posts->first()->id === $newPost->id && $posts->last()->id === $oldPost->id;
        });
    });

    test('show displays a public post', function () {
        $post = Post::factory()->create(['is_public' => true, 'slug' => 'test-post']);

        $response = $this->get(route('post.show', 'test-post'));

        $response->assertStatus(200);
        $response->assertViewIs('posts.show');
        $response->assertViewHas('post', function (Post $viewPost) use ($post) {
            return $viewPost->id === $post->id;
        });
    });

    test('show returns 404 for private post', function () {
        $post = Post::factory()->private()->create(['slug' => 'private-post']);

        $response = $this->get(route('post.show', 'private-post'));

        $response->assertStatus(404);
    });

    test('show returns 404 for non-existent post', function () {
        $response = $this->get(route('post.show', 'non-existent-post'));

        $response->assertStatus(404);
    });
});
