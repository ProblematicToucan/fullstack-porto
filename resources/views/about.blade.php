@extends('layouts.app')

@section('title', 'About')

@section('content')
    @if($about)
        <article>
            @if($about->avatar)
                <div class="mb-6">
                    <img src="{{ \App\Support\PublicStorageUrl::url($about->avatar) }}" alt="About" class="rounded-full w-24 h-24 object-cover border border-[#e3e3e0] dark:border-[#3E3E3A]" />
                </div>
            @endif

            @if($about->heading)
                <h1 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ $about->heading }}</h1>
            @endif

            @if($about->body)
                <div class="prose prose-zinc dark:prose-invert max-w-none text-[13px] leading-[20px] text-zinc-700 dark:text-zinc-300">
                    {!! nl2br(e($about->body)) !!}
                </div>
            @endif
        </article>
    @else
        <p class="text-zinc-500 dark:text-zinc-400">No about content yet.</p>
    @endif
@endsection
