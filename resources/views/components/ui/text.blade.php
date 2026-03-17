@php
    $class = trim($sizeClasses() . ' ' . $colorClasses());
    $merged = $attributes->merge(['class' => $class]);
@endphp
@if($inline)
    <span {{ $merged }}>{{ $slot }}</span>
@else
    <p {{ $merged }}>{{ $slot }}</p>
@endif
