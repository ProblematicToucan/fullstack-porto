<?php

namespace App\Jobs;

use App\Ai\Knowledge\KnowledgeChunkIndexer;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncKnowledgeChunksJob implements ShouldQueue
{
    use Queueable;

    /**
     * @param  class-string<Post|Project>  $modelClass
     */
    public function __construct(
        public string $modelClass,
        public int $id,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $indexer = KnowledgeChunkIndexer::make();

        if ($this->modelClass === Post::class) {
            $post = Post::withTrashed()->find($this->id);
            if ($post instanceof Post) {
                $indexer->syncPost($post);
            }

            return;
        }

        if ($this->modelClass === Project::class) {
            $project = Project::withTrashed()->find($this->id);
            if ($project instanceof Project) {
                $indexer->syncProject($project);
            }
        }
    }
}
