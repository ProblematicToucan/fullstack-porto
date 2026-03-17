@php
    $class = $baseClasses() . ' ' . $variantClasses();
    $merged = $attributes->merge(['class' => $class]);
@endphp
@if($isButton())
    <button type="button" {{ $merged }}>
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}"
        @if($external) target="_blank" rel="noopener noreferrer" @endif
        {{ $merged }}>
        {{ $slot }}
    </a>
@endif
