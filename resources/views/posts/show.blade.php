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

        <div class="prose prose-zinc dark:prose-invert max-w-none text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300 [&_p_code]:rounded-md [&_p_code]:bg-zinc-100 [&_p_code]:px-1.5 [&_p_code]:py-px [&_p_code]:font-mono [&_p_code]:text-[0.9em] dark:[&_p_code]:bg-zinc-800 [&_pre]:my-4 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:border [&_pre]:border-zinc-200 [&_pre]:bg-zinc-50 [&_pre]:p-4 dark:[&_pre]:border-zinc-700 dark:[&_pre]:bg-zinc-900/40 [&_pre_code]:block [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:font-mono [&_pre_code]:text-[13px] [&_pre_code]:leading-relaxed">
            {!! \App\Support\PostContentSanitizer::sanitize($post->content) !!}
        </div>
    </article>
@endsection
