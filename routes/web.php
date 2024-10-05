<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', LandingController::class)->name('landing');

Route::prefix('/project')->name('project.')->controller(ProjectController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{project}', 'show')->name('show');
});

Route::get('/bio', ProfileController::class)->name('bio');
