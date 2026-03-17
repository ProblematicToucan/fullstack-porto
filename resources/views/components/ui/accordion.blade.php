{{-- Flux-style accordion: https://fluxui.dev/components/accordion --}}
<div
    data-accordion-root
    data-variant="{{ $variant }}"
    data-transition="{{ $transition ? 'true' : 'false' }}"
    x-data="{
        exclusive: @js($exclusive),
        closeOthersExcept(exceptId) {
            this.$dispatch('accordion-close-others', { exceptId });
        }
    }"
    x-init="
        if (exclusive) {
            setTimeout(() => {
                const firstOpen = $el.querySelector('[data-accordion-item][data-initial-open=\'true\']');
                if (firstOpen) closeOthersExcept(firstOpen.dataset.accordionItemId);
            }, 0);
        }
    "
    {{ $attributes->merge(['class' => 'divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]']) }}
>
    {{ $slot }}
</div>
