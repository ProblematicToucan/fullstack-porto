{{-- Flux-style accordion item. Open state controlled by Alpine so grid transition runs on both open and close. --}}

@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('accordionItem', (initial = {}) => ({
        id: initial.id ?? '',
        open: initial.open ?? false,
        disabled: initial.disabled ?? false,

        toggle() {
            if (this.disabled) return;
            const root = this.$el.closest('[data-accordion-root]');
            if (root?.__x?.$data?.exclusive && !this.open) {
                root.__x.$data.closeOthersExcept(this.id);
            }
            this.open = !this.open;
        },

        closeIfNotExcepted(event) {
            if (this.$el.closest('[data-accordion-root]') !== event.target) return;
            if (event.detail.exceptId === this.id) return;
            this.open = false;
        },
    }));
});
</script>
@endonce

@php
    $contentSlot = (isset($content) && $content->isNotEmpty()) ? $content : $slot;
    $itemId = 'accordion-item-' . uniqid();
@endphp
<div
    data-accordion-item
    data-accordion-item-id="{{ $itemId }}"
    @if($expanded) data-initial-open="true" @endif
    x-data="accordionItem({ id: @js($itemId), open: @js($expanded), disabled: @js($disabled) })"
    @accordion-close-others.window="closeIfNotExcepted($event)"
    class="group"
>
    <button
        type="button"
        :disabled="disabled"
        @click="toggle()"
        :aria-expanded="open"
        :aria-disabled="disabled"
        class="flex w-full list-none cursor-pointer items-center justify-between gap-2 rounded-none border-0 bg-transparent py-3 text-left text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:opacity-80 focus:outline-none focus:ring-0 disabled:pointer-events-none disabled:opacity-50 [&::-webkit-details-marker]:hidden [&::marker]:hidden"
    >
        <span class="min-w-0 flex-1">{{ $heading ?? '' }}</span>
        <span class="shrink-0 text-[#706f6c] dark:text-[#A1A09A] transition-transform duration-200" :class="open && 'rotate-90'" aria-hidden="true">
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </span>
    </button>
    {{-- Grid always in DOM so transition runs on both open and close --}}
    <div
        class="grid transition-[grid-template-rows] duration-200 ease-out"
        :style="open ? 'grid-template-rows: 1fr' : 'grid-template-rows: 0fr'"
    >
        <div class="min-h-0 overflow-hidden text-[#706f6c] dark:text-[#A1A09A]">
            <div class="pb-3 pt-0 text-sm leading-relaxed">
                {{ $contentSlot }}
            </div>
        </div>
    </div>
</div>
