@php
    $class = $baseClasses() . ' ' . $variantClasses();
    $merged = $attributes->merge(['class' => $class]);
@endphp
@if($isButton())
    <button type="button" {{ $merged }}>
        {{ $slot }}
    </button>
@else
    <a href="{{ $renderedHref() }}" @if($isExternal()) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif {{ $merged }}>
        {{ $slot }}
    </a>
@endif