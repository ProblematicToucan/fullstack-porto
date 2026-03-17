<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('categories', 'techStacks')
            ->latest()
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $project->load('categories', 'techStacks');

        return view('projects.show', compact('project'));
    }
}
