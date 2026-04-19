<?php

namespace App\Ai\Knowledge;

use App\Models\KnowledgeChunk;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;

final class KnowledgeChunkIndexer
{
    public function __construct(
        private KnowledgeTextChunker $chunker,
    ) {}

    public static function make(): self
    {
        return new self(new KnowledgeTextChunker);
    }

    public function syncPost(Post $post): void
    {
        if ($post->trashed() || ! $post->is_public) {
            $this->markPostChunksDeleted($post);

            return;
        }

        $canonical = PortfolioContentExtractor::canonicalTextForPost($post);
        $hash = hash('sha256', $canonical);

        if ($hash === $post->knowledge_source_hash) {
            KnowledgeChunk::query()
                ->where('post_id', $post->getKey())
                ->update(['is_deleted' => false]);

            return;
        }

        $segments = $this->chunker->chunk($canonical);

        DB::transaction(function () use ($post, $segments, $hash): void {
            KnowledgeChunk::query()->where('post_id', $post->getKey())->delete();

            if ($segments === []) {
                $post->updateQuietly(['knowledge_source_hash' => $hash]);

                return;
            }

            $texts = array_column($segments, 'text');
            $response = Embeddings::for($texts)
                ->dimensions(1536)
                ->generate(Lab::OpenAI, 'text-embedding-3-small');

            $chunks = [];
            $now = now();

            foreach ($segments as $i => $segment) {
                $chunks[] = [
                    'post_id' => $post->getKey(),
                    'project_id' => null,
                    'chunk_index' => $segment['chunk_index'],
                    'content' => $segment['text'],
                    'embedding' => json_encode($response->embeddings[$i]),
                    'is_deleted' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            KnowledgeChunk::query()->insert($chunks);

            $post->updateQuietly(['knowledge_source_hash' => $hash]);
        });
    }

    public function syncProject(Project $project): void
    {
        if ($project->trashed()) {
            $this->markProjectChunksDeleted($project);

            return;
        }

        $canonical = PortfolioContentExtractor::canonicalTextForProject($project);
        $hash = hash('sha256', $canonical);

        if ($hash === $project->knowledge_source_hash) {
            KnowledgeChunk::query()
                ->where('project_id', $project->getKey())
                ->update(['is_deleted' => false]);

            return;
        }

        $segments = $this->chunker->chunk($canonical);

        DB::transaction(function () use ($project, $segments, $hash): void {
            KnowledgeChunk::query()->where('project_id', $project->getKey())->delete();

            if ($segments === []) {
                $project->updateQuietly(['knowledge_source_hash' => $hash]);

                return;
            }

            $texts = array_column($segments, 'text');
            $response = Embeddings::for($texts)
                ->dimensions(1536)
                ->generate(Lab::OpenAI, 'text-embedding-3-small');

            $chunks = [];
            $now = now();

            foreach ($segments as $i => $segment) {
                $chunks[] = [
                    'post_id' => null,
                    'project_id' => $project->getKey(),
                    'chunk_index' => $segment['chunk_index'],
                    'content' => $segment['text'],
                    'embedding' => json_encode($response->embeddings[$i]),
                    'is_deleted' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            KnowledgeChunk::query()->insert($chunks);

            $project->updateQuietly(['knowledge_source_hash' => $hash]);
        });
    }

    private function markPostChunksDeleted(Post $post): void
    {
        KnowledgeChunk::query()
            ->where('post_id', $post->getKey())
            ->update(['is_deleted' => true]);
    }

    private function markProjectChunksDeleted(Project $project): void
    {
        KnowledgeChunk::query()
            ->where('project_id', $project->getKey())
            ->update(['is_deleted' => true]);
    }
}
