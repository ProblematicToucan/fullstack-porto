<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
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
        $post = Post::query()->select('id','title','slug','is_public')->paginate(self::INDEX_PER_PAGE);

        return Inertia::render(self::VIEW, ['posts' => $post]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $post;
    }
}
