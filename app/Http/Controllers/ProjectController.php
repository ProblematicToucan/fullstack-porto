<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    const PROJECT_VIEW = 'Project';
    const PROJECT_INDEX_PER_PAGE  = 5;

    public function index(): Response
    {
        $projects = Project::select('id', 'title', 'slug', 'repo_url', 'is_featured')->with('categories:id,name')->orderBy('is_featured', 'desc')->paginate(self::PROJECT_INDEX_PER_PAGE);

        // Transform category names into a comma-separated string
        $projects->map(function ($project) {
            $project->category_names = $project->categories->pluck('name')->implode(', ');
            return $project;
        });

        return Inertia::render(self::PROJECT_VIEW, ['projects' => $projects]);
    }

    public function show(Request $request, Project $project): Project
    {
        $project->load('categories:id,name', 'projectMedias', 'techStacks');

        // Transform category names into a comma-separated string
        $project->category_names = $project->categories->pluck('name')->implode(', ');

        return $project;
    }
}
