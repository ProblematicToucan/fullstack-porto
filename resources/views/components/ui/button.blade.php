<button type="{{ $type }}"
    @if($loading) wire:loading.attr="disabled" @endif
    {{ $attributes->merge(['class' => $baseClasses() . ' ' . $variantClasses() . ' ' . $sizeClasses() . ' gap-1.5']) }}>
    @if($loading)
    <span class="hidden shrink-0 motion-reduce:animate-none" wire:loading.remove.class="hidden" wire:loading.class="inline-flex">
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
        {!! svg($iconTrailing, $iconSizeClasses() . ' shrink-0', ['aria-hidden' => 'true'])->toHtml() !!}
    @endif
</button>