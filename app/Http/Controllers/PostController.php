<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::select(['id', 'title', 'slug', 'created_at'])
            ->public()
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(string $post): View
    {
        $post = Post::public()->where('slug', $post)->firstOrFail();

        return view('posts.show', compact('post'));
    }
}
