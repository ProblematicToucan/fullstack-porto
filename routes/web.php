<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
Route::get('/project/{project}', [ProjectController::class, 'show'])->name('project.show');

Route::get('/post', [PostController::class, 'index'])->name('post.index');
Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');

Route::get('/about', AboutController::class)->name('about');
