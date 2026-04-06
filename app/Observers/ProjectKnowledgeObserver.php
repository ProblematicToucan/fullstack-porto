<?php

namespace App\Observers;

use App\Jobs\SyncKnowledgeChunksJob;
use App\Models\Project;

class ProjectKnowledgeObserver
{
    public function saved(Project $project): void
    {
        \Illuminate\Support\Facades\Cache::forget('landing.featured_projects');
        SyncKnowledgeChunksJob::dispatch(Project::class, (int) $project->getKey());
    }

    public function deleted(Project $project): void
    {
        \Illuminate\Support\Facades\Cache::forget('landing.featured_projects');
        SyncKnowledgeChunksJob::dispatch(Project::class, (int) $project->getKey());
    }
}
