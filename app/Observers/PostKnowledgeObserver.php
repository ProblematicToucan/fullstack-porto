<?php

namespace App\Observers;

use App\Jobs\SyncKnowledgeChunksJob;
use App\Models\Post;

class PostKnowledgeObserver
{
    public function saved(Post $post): void
    {
        \Illuminate\Support\Facades\Cache::forget('landing.latest_posts');
        SyncKnowledgeChunksJob::dispatch(Post::class, (int) $post->getKey());
    }

    public function deleted(Post $post): void
    {
        \Illuminate\Support\Facades\Cache::forget('landing.latest_posts');
        SyncKnowledgeChunksJob::dispatch(Post::class, (int) $post->getKey());
    }
}
