@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <h1 class="text-xl font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-6">Projects</h1>

    @if($projects->isEmpty())
        <x-ui.text variant="subtle">No projects yet.</x-ui.text>
    @else
        <ul class="space-y-4">
            @foreach($projects as $project)
                <li>
                    <x-ui.link href="{{ route('project.show', $project) }}" variant="ghost" class="text-base">
                        {{ $project->title }}
                    </x-ui.link>
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @endif
@endsection
