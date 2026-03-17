<button
    type="button"
    aria-label="{{ __('Remove') }}"
    {{ $attributes->merge(['class' => 'shrink-0 rounded p-0.5 opacity-70 hover:opacity-100 focus:opacity-100 focus:outline-none focus:ring-1 focus:ring-current']) }}
>
    {!! svg($icon, $iconSizeClasses(), ['aria-hidden' => 'true'])->toHtml() !!}
</button>
