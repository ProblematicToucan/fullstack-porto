<?php

use App\Ai\Knowledge\KnowledgeChunkIndexer;
use App\Models\KnowledgeChunk;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Queue::fake();
});

it('indexes searchable chunks for a public post and hides them when not public', function (): void {
    $post = Post::factory()->create([
        'is_public' => true,
        'content' => [
            ['type' => 'paragraph', 'data' => ['text' => '<p>Visible article body.</p>']],
        ],
    ]);

    $indexer = KnowledgeChunkIndexer::make();
    $indexer->syncPost($post);

    expect(KnowledgeChunk::query()->searchable()->where('post_id', $post->id)->count())->toBeGreaterThan(0);

    $post->update(['is_public' => false]);
    $indexer->syncPost($post->fresh());

    expect(KnowledgeChunk::query()->searchable()->where('post_id', $post->id)->count())->toBe(0)
        ->and(KnowledgeChunk::query()->where('post_id', $post->id)->where('is_deleted', true)->count())->toBeGreaterThan(0);
});

it('skips re-embedding when the source hash is unchanged', function (): void {
    $post = Post::factory()->create([
        'is_public' => true,
        'content' => [
            ['type' => 'paragraph', 'data' => ['text' => '<p>Stable text.</p>']],
        ],
    ]);

    $indexer = KnowledgeChunkIndexer::make();
    $indexer->syncPost($post);
    $firstCount = KnowledgeChunk::query()->where('post_id', $post->id)->count();

    $indexer->syncPost($post->fresh());

    expect(KnowledgeChunk::query()->where('post_id', $post->id)->count())->toBe($firstCount)
        ->and($post->fresh()->knowledge_source_hash)->not->toBeNull();
});
