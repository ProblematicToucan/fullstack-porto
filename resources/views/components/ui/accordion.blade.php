{{-- Flux-style accordion. Single source of truth: root store holds open state. --}}
@php
    $accordionId = 'accordion-' . uniqid();
@endphp
<div
    data-accordion-root
    data-accordion-id="{{ $accordionId }}"
    data-variant="{{ $variant }}"
    data-transition="{{ $transition ? 'true' : 'false' }}"
    x-data="{
        accordionId: @js($accordionId),
        exclusive: @js($exclusive)
    }"
    x-init="
        Alpine.store('accordion') || Alpine.store('accordion', {});
        const state = { openId: null, openIds: {} };
        const writeStore = () => {
            Alpine.store('accordion')[accordionId] = {
                openId: state.openId,
                openIds: { ...state.openIds },
                exclusive: exclusive,
                toggle(id) {
                    if (exclusive) {
                        state.openId = state.openId === id ? null : id;
                    } else {
                        state.openIds[id] = !state.openIds[id];
                    }
                    writeStore();
                }
            };
        };
        writeStore();
        setTimeout(() => {
            const first = $el.querySelector('[data-accordion-item][data-initial-open=\'true\']');
            if (first && exclusive) { state.openId = first.dataset.accordionItemId; writeStore(); }
        }, 0);
    "
    {{ $attributes->merge(['class' => 'divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]']) }}
>
    {{ $slot }}
</div>
