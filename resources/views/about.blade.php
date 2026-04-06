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

            @if(is_array($about->links) && count($about->links))
                <div class="mt-8">
                    <h2 class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-3">Links</h2>
                    <ul class="flex flex-wrap gap-2">
                        @foreach($about->links as $label => $url)
                            @if($url)
                                <li>
                                    <a
                                        href="{{ $url }}"
                                        @unless(str_starts_with($url, 'mailto:')) target="_blank" rel="noopener noreferrer" @endunless
                                        class="group inline-flex items-center gap-2 rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#161615] px-3.5 py-2 text-[13px] font-medium text-zinc-700 dark:text-zinc-300 transition-all duration-150 hover:border-[#1b1b18] dark:hover:border-[#EDEDEC] hover:bg-white dark:hover:bg-[#1a1a18] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:shadow-sm"
                                    >
                                        {{-- Per-type icons --}}
                                        @if(str_contains(strtolower($label), 'linkedin'))
                                            <svg class="size-4 shrink-0 text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-[#0A66C2]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                        @elseif(str_contains(strtolower($label), 'github'))
                                            <svg class="size-4 shrink-0 text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-[#1b1b18] dark:group-hover:text-[#EDEDEC]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                                        @elseif(str_contains(strtolower($label), 'email') || str_starts_with($url, 'mailto:'))
                                            <svg class="size-4 shrink-0 text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                        @else
                                            <svg class="size-4 shrink-0 text-zinc-400 dark:text-zinc-500 transition-colors group-hover:text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        @endif
                                        <span>{{ is_string($label) ? $label : $url }}</span>
                                        {{-- External arrow indicator --}}
                                        @unless(str_starts_with($url, 'mailto:'))
                                            <svg class="size-3 shrink-0 text-zinc-300 dark:text-zinc-600 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd"/></svg>
                                        @endunless
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </article>
    @else
        <p class="text-zinc-500 dark:text-zinc-400">No about content yet.</p>
    @endif
@endsection
