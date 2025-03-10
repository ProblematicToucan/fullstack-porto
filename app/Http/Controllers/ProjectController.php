<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    const string VIEW = 'Project';
    const int INDEX_PER_PAGE  = 5;

    public function index(): Response
    {
        $projects = Project::query()
            ->select('id', 'title', 'slug', 'repo_url', 'is_featured')
            ->with('categories:id,name')
            ->orderByDesc('is_featured')
            ->paginate(self::INDEX_PER_PAGE);

        // Modify the collection within the paginator while keeping pagination intact
        $projects->through(fn ($project) => $this->transformProject($project));

        return Inertia::render(self::VIEW, ['projects' => $projects]);
    }

    public function show(Project $project): Project
    {
        return $this->transformProject(
            $project->load('categories:id,name', 'projectMedias', 'techStacks')
        );
    }

    private function transformProject(Project $project): Project
    {
        $project->category_names = $project->categories->pluck('name')->implode(', ');
        return $project;
    }
}
