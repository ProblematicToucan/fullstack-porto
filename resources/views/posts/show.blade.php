@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <article>
        <header class="mb-6">
            <h1 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $post->title }}</h1>
            <x-ui.text size="sm" variant="subtle">
                {{ $post->created_at?->format('F j, Y') ?? $post->updated_at?->format('F j, Y') }}
            </x-ui.text>
        </header>

        <div class="prose prose-zinc dark:prose-invert max-w-none text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300">
            {!! \App\Support\PostContentSanitizer::sanitize($post->content) !!}
        </div>
    </article>
@endsection
