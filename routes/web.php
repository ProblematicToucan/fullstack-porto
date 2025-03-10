<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

Route::prefix('/project')->name('project.')->controller(ProjectController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('/{project}', 'show')->name('show');
});

Route::prefix('/post')->name('post.')->controller(PostController::class)->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('/{post}', 'show')->name('show');
});

Route::inertia('/bio', 'Profile')->name('bio');
