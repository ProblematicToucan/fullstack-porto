<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    const string VIEW = 'Post';
    const int INDEX_PER_PAGE = 5;

    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $post = Post::query()
            ->select('id', 'title', 'slug', 'is_public', 'updated_at')
            ->where('is_public', '=', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(self::INDEX_PER_PAGE)
            ->toArray();

        return Inertia::render(self::VIEW, ['posts' => $post]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): Post
    {
        return $post;
    }
}
