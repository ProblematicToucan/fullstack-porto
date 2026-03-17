<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $about = About::first();

        return view('about', compact('about'));
    }
}
