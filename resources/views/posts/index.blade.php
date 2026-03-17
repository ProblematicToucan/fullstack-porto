@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h1 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Posts</h1>

    @if($posts->isEmpty())
        <x-ui.text variant="subtle">No posts yet.</x-ui.text>
    @else
        <ul class="space-y-4">
            @foreach($posts as $post)
                <li>
                    <x-ui.link href="{{ route('post.show', $post) }}" variant="ghost" class="text-base">
                        {{ $post->title }}
                    </x-ui.link>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
@endsection
