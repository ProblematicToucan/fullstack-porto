<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans min-h-screen flex flex-col">
    <header class="w-full border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
        <nav class="max-w-4xl mx-auto px-6 py-4 flex flex-wrap items-center gap-4 text-sm">
            <x-ui.link href="{{ route('home') }}" variant="ghost">Home</x-ui.link>
            <x-ui.link href="{{ route('project.index') }}" variant="ghost">Projects</x-ui.link>
            <x-ui.link href="{{ route('post.index') }}" variant="ghost">Posts</x-ui.link>
            <x-ui.link href="{{ route('about') }}" variant="ghost">About</x-ui.link>
        </nav>
    </header>

    <main class="flex-1 w-full max-w-4xl mx-auto px-6 py-8 lg:py-12">
        @yield('content')
    </main>
</body>

</html>
