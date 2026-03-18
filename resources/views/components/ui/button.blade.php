@if($isLink())
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses() . ' ' . $variantClasses() . ' ' . $sizeClasses() . ' ' . $insetClasses() . ' gap-1.5 no-underline']) }}>
        @if($icon)
            {!! svg($icon, $iconSizeClasses() . ' shrink-0', ['aria-hidden' => 'true'])->toHtml() !!}
        @endif
        @if($slot->isNotEmpty())
            <span>{{ $slot }}</span>
        @endif
        @if($iconTrailing)
            @if($isBuiltInTrailingIcon())
                <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="{{ $iconSizeClasses() }} shrink-0" aria-hidden="true">
                    <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                </svg>
            @else
                {!! svg($iconTrailing, $iconSizeClasses() . ' shrink-0', ['aria-hidden' => 'true'])->toHtml() !!}
            @endif
        @endif
    </a>
@else
    <button type="{{ $type }}" @if($loading) wire:loading.attr="disabled" @endif {{ $attributes->merge(['class' => $baseClasses() . ' ' . $variantClasses() . ' ' . $sizeClasses() . ' ' . $insetClasses() . ' gap-1.5']) }}>
        @if($loading)
            <span class="hidden shrink-0 motion-reduce:animate-none" wire:loading.remove.class="hidden"
                wire:loading.class="inline-flex">
                {!! svg('heroicon-o-arrow-path', $iconSizeClasses() . ' animate-spin', ['aria-hidden' => 'true'])->toHtml() !!}
            </span>
        @endif
        @if($icon)
            {!! svg($icon, $iconSizeClasses() . ' shrink-0', ['aria-hidden' => 'true'])->toHtml() !!}
        @endif
        @if($slot->isNotEmpty())
            <span>{{ $slot }}</span>
        @endif
        @if($iconTrailing)
            @if($isBuiltInTrailingIcon())
                <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="{{ $iconSizeClasses() }} shrink-0" aria-hidden="true">
                    <path d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001" stroke="currentColor" stroke-linecap="square" />
                </svg>
            @else
                {!! svg($iconTrailing, $iconSizeClasses() . ' shrink-0', ['aria-hidden' => 'true'])->toHtml() !!}
            @endif
        @endif
    </button>
@endif