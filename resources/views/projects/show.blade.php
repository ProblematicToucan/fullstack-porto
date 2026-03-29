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
                <img src="{{ \App\Support\PublicStorageUrl::url($project->image) }}" alt="{{ $project->title }}" class="block w-full h-auto max-w-full" loading="eager" decoding="async" />
            </div>
        @endif

        @if($project->description !== null && $project->description !== [])
            <div class="mb-6 text-justify prose prose-zinc dark:prose-invert max-w-none [&_p]:my-0 [&_p]:text-justify [&_p+p]:mt-6 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:text-justify [&_blockquote]:text-justify [&_figure]:my-0 [&_p+figure]:mt-6 [&_figure+p]:mt-6 [&_blockquote+p]:mt-6 [&_figcaption]:text-start">
                @if(is_array($project->description) && \App\Support\PostContentSanitizer::isBuilderBlocks($project->description))
                    {!! \App\Support\PostContentSanitizer::sanitize($project->description) !!}
                @elseif(is_array($project->description))
                    <div class="not-prose space-y-6">
                        @foreach($project->description as $block)
                            @if(is_string($block))
                                <p class="text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300">{{ $block }}</p>
                            @elseif(is_array($block))
                                <p class="text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300">
                                    {{ $block['content'] ?? $block['text'] ?? '' }}
                                </p>
                            @endif
                        @endforeach
                    </div>
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
                        <span @class([
                            'group relative inline-flex',
                            'cursor-help' => filled($tech->description),
                        ])>
                            @if(filled($tech->logo))
                                <span class="inline-flex items-center gap-2 rounded-md border border-[#e3e3e0] bg-white/60 px-2 py-1.5 text-[13px] text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-zinc-900/40 dark:text-[#EDEDEC]">
                                    <img src="{{ $tech->logo }}" alt="" width="20" height="20" class="size-5 shrink-0 object-contain" loading="lazy" decoding="async" />
                                    <span>{{ $tech->name }}</span>
                                </span>
                            @else
                                <x-ui.badge color="blue" size="sm">{{ $tech->name }}</x-ui.badge>
                            @endif
                            @if(filled($tech->description))
                                <span role="tooltip" class="pointer-events-none absolute left-1/2 top-full z-30 mt-1.5 w-max max-w-xs -translate-x-1/2 rounded-md border border-[#e3e3e0] bg-[#fafafa] px-2.5 py-2 text-left text-[12px] leading-snug text-zinc-700 opacity-0 shadow-md transition-opacity duration-150 group-hover:opacity-100 dark:border-[#3E3E3A] dark:bg-zinc-800 dark:text-zinc-200">
                                    {{ $tech->description }}
                                </span>
                            @endif
                        </span>
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
                                <img src="{{ \App\Support\PublicStorageUrl::url($media->media_url) }}" alt="{{ $media->media_description ?? 'Project media' }}"
                                    class="block w-full h-auto max-w-full rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]" loading="lazy" decoding="async" />
                            @elseif($media->media_url)
                                <a href="{{ Str::startsWith($media->media_url, ['http', '/']) ? $media->media_url : \App\Support\PublicStorageUrl::url($media->media_url) }}"
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
