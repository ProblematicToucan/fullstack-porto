<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $featuredProjects = Project::where('is_featured', true)->take(6)->get();
        $latestPosts = Post::public()->latest()->take(3)->get();

        return view('landing', [
            'featuredProjects' => $featuredProjects,
            'latestPosts' => $latestPosts,
        ]);
    }
}
