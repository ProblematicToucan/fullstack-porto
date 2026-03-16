<div
    {{ $attributes->merge([
        'class' => 'inline-flex overflow-visible [&>*]:rounded-none [&>*:first-child]:rounded-l-sm [&>*:last-child]:rounded-r-sm [&>*:not(:first-child)]:-ml-px [&>*]:focus:ring-inset [&>*]:focus:ring-offset-0',
    ]) }}
>
    {{ $slot }}
</div>
