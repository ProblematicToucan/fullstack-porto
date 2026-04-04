<?php

namespace App\Observers;

use App\Jobs\SyncKnowledgeChunksJob;
use App\Models\Project;

class ProjectKnowledgeObserver
{
    public function saved(Project $project): void
    {
        SyncKnowledgeChunksJob::dispatch(Project::class, (int) $project->getKey());
    }

    public function deleted(Project $project): void
    {
        SyncKnowledgeChunksJob::dispatch(Project::class, (int) $project->getKey());
    }
}
