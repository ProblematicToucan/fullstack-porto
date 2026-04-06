<?php

namespace App\Observers;

use App\Models\About;
use Illuminate\Support\Facades\Cache;

class AboutObserver
{
    public function saved(About $about): void
    {
        Cache::forget('about_page');
    }

    public function deleted(About $about): void
    {
        Cache::forget('about_page');
    }
}
