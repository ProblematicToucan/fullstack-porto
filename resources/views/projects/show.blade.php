@extends('layouts.app')

@section('title', $project->title)

@section('content')
    <article>
        <header class="mb-6">
            <h1 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $project->title }}</h1>
            @if($project->categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    @foreach($project->categories as $category)
                        <x-ui.badge color="zinc" size="sm">{{ $category->name }}</x-ui.badge>
                    @endforeach
                </div>
            @endif
        </header>

        @if($project->image)
            <div class="mb-6 overflow-hidden rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" class="w-full max-h-[400px] object-cover" />
            </div>
        @endif

        @if($project->description !== null && $project->description !== [])
            <div class="mb-6 prose prose-zinc dark:prose-invert max-w-none">
                @if(is_array($project->description))
                    @foreach($project->description as $block)
                        @if(is_string($block))
                            <p class="text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300 mb-3">{{ $block }}</p>
                        @elseif(is_array($block))
                            <p class="text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300 mb-3">
                                {{ $block['content'] ?? $block['text'] ?? '' }}
                            </p>
                        @endif
                    @endforeach
                @else
                    <p class="text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300">{{ $project->description }}</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap gap-3 mb-6">
            @if($project->project_url)
                <x-ui.button href="{{ $project->project_url }}" variant="primary" size="sm" target="_blank" rel="noopener noreferrer">View project</x-ui.button>
            @endif
            @if($project->repo_url)
                <x-ui.button href="{{ $project->repo_url }}" variant="outline" size="sm" target="_blank" rel="noopener noreferrer">Repository</x-ui.button>
            @endif
        </div>

        @if($project->techStacks->isNotEmpty())
            <div class="mb-6">
                <x-ui.text size="sm" variant="subtle" class="mb-2">Tech stack</x-ui.text>
                <div class="flex flex-wrap gap-2">
                    @foreach($project->techStacks as $tech)
                        <x-ui.badge color="blue" size="sm">{{ $tech->name }}</x-ui.badge>
                    @endforeach
                </div>
            </div>
        @endif

        @if($project->projectMedias->isNotEmpty())
            <div class="mb-6">
                <x-ui.text size="sm" variant="subtle" class="mb-2">Media</x-ui.text>
                <ul class="space-y-3">
                    @foreach($project->projectMedias as $media)
                        <li>
                            @if($media->media_type === 'image' && $media->media_url)
                                <img src="{{ Storage::url($media->media_url) }}" alt="{{ $media->media_description ?? 'Project media' }}"
                                    class="max-w-full rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] max-h-64 object-cover" />
                            @elseif($media->media_url)
                                <a href="{{ Str::startsWith($media->media_url, ['http', '/']) ? $media->media_url : Storage::url($media->media_url) }}"
                                    target="_blank" rel="noopener noreferrer"
                                    class="text-blue-600 dark:text-blue-400 underline text-sm">{{ $media->media_description ?? 'View media' }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </article>
@endsection
