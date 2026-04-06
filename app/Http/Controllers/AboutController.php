<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $aboutArray = \Illuminate\Support\Facades\Cache::rememberForever('about_page', function () {
            return About::first()?->toArray();
        });

        $about = $aboutArray ? About::hydrate([$aboutArray])->first() : null;

        return view('about', compact('about'));
    }
}
