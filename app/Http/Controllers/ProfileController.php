<?php

namespace App\Http\Controllers;

use App\Seo\Meta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Profile', ['meta' => Meta::render()]);
    }
}
