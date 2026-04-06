<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $featuredProjectsArray = \Illuminate\Support\Facades\Cache::remember('landing.featured_projects', now()->addHours(6), function () {
            return Project::select(['id', 'title', 'slug', 'image'])
                ->where('is_featured', true)
                ->take(6)
                ->get()
                ->map(fn($model) => $model->getAttributes())
                ->toArray();
        });

        $featuredProjects = Project::hydrate($featuredProjectsArray);

        $latestPostsArray = \Illuminate\Support\Facades\Cache::remember('landing.latest_posts', now()->addHours(6), function () {
            return Post::select(['id', 'title', 'slug'])
                ->public()
                ->latest()
                ->take(3)
                ->get()
                ->map(fn($model) => $model->getAttributes())
                ->toArray();
        });

        $latestPosts = Post::hydrate($latestPostsArray);

        return view('landing', [
            'featuredProjects' => $featuredProjects,
            'latestPosts' => $latestPosts,
        ]);
    }
}
