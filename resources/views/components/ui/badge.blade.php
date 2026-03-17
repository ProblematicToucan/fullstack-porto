@php
    $class = $baseClasses() . ' ' . $sizeClasses() . ' ' . $roundedClasses() . ' ' . $colorClasses() . ' ' . $insetClasses();
    $merged = $attributes->merge(['class' => $class]);
@endphp
@if($isButton())
    <button type="button" {{ $merged }}>
        @if($icon)
            <span class="shrink-0" aria-hidden="true">{!! svg($icon, $iconSizeClasses(), ['aria-hidden' => 'true'])->toHtml() !!}</span>
        @endif
        @if($slot->isNotEmpty())
            <span>{{ $slot }}</span>
        @endif
        @if($iconTrailing)
            <span class="shrink-0" aria-hidden="true">{!! svg($iconTrailing, $iconSizeClasses(), ['aria-hidden' => 'true'])->toHtml() !!}</span>
        @endif
    </button>
@else
    <div {{ $merged }}>
        @if($icon)
            <span class="shrink-0" aria-hidden="true">{!! svg($icon, $iconSizeClasses(), ['aria-hidden' => 'true'])->toHtml() !!}</span>
        @endif
        @if($slot->isNotEmpty())
            <span>{{ $slot }}</span>
        @endif
        @if($iconTrailing)
            <span class="shrink-0" aria-hidden="true">{!! svg($iconTrailing, $iconSizeClasses(), ['aria-hidden' => 'true'])->toHtml() !!}</span>
        @endif
    </div>
@endif
