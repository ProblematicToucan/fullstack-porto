@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    {{-- Hero --}}
    <section class="mb-12 lg:mb-16">
        <x-ui.text size="xl" variant="strong" class="mb-2">Welcome</x-ui.text>
        <x-ui.text size="lg" variant="subtle" class="max-w-xl">
            A portfolio of projects and thoughts. Browse featured work below or explore all projects and posts.
        </x-ui.text>
    </section>

    {{-- Featured projects --}}
    <section class="mb-12 lg:mb-16">
        <h2 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Featured projects</h2>
        @if($featuredProjects->isEmpty())
            <x-ui.text variant="subtle">No featured projects yet.</x-ui.text>
        @else
            <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredProjects as $project)
                    <li>
                        <x-ui.link href="{{ route('project.show', $project) }}" variant="ghost" class="block group">
                            <div class="overflow-hidden rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] mb-3">
                                @if($project->image)
                                    <img src="{{ \App\Support\PublicStorageUrl::url($project->image) }}" alt="{{ $project->title }}"
                                        class="block w-full h-auto max-w-full group-hover:opacity-95 transition-opacity duration-300" loading="lazy" decoding="async" />
                                @else
                                    <div class="w-full min-h-40 flex items-center justify-center text-[#706f6c] dark:text-[#A1A09A] text-sm">
                                        No image
                                    </div>
                                @endif
                            </div>
                            <x-ui.text size="default" variant="strong" class="group-hover:underline">{{ $project->title }}</x-ui.text>
                        </x-ui.link>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Latest posts --}}
    <section>
        <h2 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Latest posts</h2>
        @if($latestPosts->isEmpty())
            <x-ui.text variant="subtle">No posts yet.</x-ui.text>
        @else
            <ul class="space-y-3">
                @foreach($latestPosts as $post)
                    <li>
                        <x-ui.link href="{{ route('post.show', $post) }}" variant="ghost">{{ $post->title }}</x-ui.link>
                    </li>
                @endforeach
            </ul>
            <p class="mt-4">
                <x-ui.button href="{{ route('post.index') }}" variant="outline" size="sm">View all posts</x-ui.button>
            </p>
        @endif
    </section>
@endsection
