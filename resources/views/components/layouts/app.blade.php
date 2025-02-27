@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}" @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if ($favicon = filament()->getFavicon())
        <link rel="icon" href="{{ $favicon }}" />
    @endif

    @php
        $brandName = trim(strip_tags(filament()->getBrandName()));
    @endphp

    <title>
        {{ filled($title) ? "{$title} - " : null }} {{ $brandName }}
    </title>

    @viteReactRefresh

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    @stack('styles')

    @if (!filament()->hasDarkMode())
        <script>
            localStorage.setItem('theme', 'light')
        </script>
    @elseif (filament()->hasDarkModeForced())
        <script>
            localStorage.setItem('theme', 'dark')
        </script>
    @else
        <script>
            const loadDarkMode = () => {
                window.theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

                if (
                    window.theme === 'dark' ||
                    (window.theme === 'system' &&
                        window.matchMedia('(prefers-color-scheme: dark)')
                            .matches)
                ) {
                    document.documentElement.classList.add('dark')
                }
            }

            loadDarkMode()

            document.addEventListener('livewire:navigated', loadDarkMode)
        </script>
    @endif
</head>

<body>
    {{ $slot }}

    @livewire('notifications')

    @filamentScripts
</body>

</html>
