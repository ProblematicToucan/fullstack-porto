<?php

namespace App\Http\Controllers;

use App\Seo\Meta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Landing', ['meta' => Meta::render()]);
    }
}
