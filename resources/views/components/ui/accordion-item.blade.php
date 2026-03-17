{{-- Accordion item: reads open state from root store, no local state. --}}
@php
    $contentSlot = (isset($content) && $content->isNotEmpty()) ? $content : $slot;
    $itemId = 'accordion-item-' . uniqid();
@endphp
<div
    data-accordion-item
    data-accordion-item-id="{{ $itemId }}"
    @if($expanded) data-initial-open="true" @endif
    x-data="{
        id: @js($itemId),
        disabled: @js($disabled),
        get rootId() { return this.$el.closest('[data-accordion-root]')?.dataset?.accordionId },
        get store() { return this.rootId ? Alpine.store('accordion')?.[this.rootId] : null },
        get isOpen() {
            const s = this.store;
            if (!s) return false;
            return s.exclusive ? s.openId === this.id : !!s.openIds[this.id];
        },
        toggle() {
            if (!this.disabled && this.store) this.store.toggle(this.id);
        }
    }"
    class="group"
>
    <button
        type="button"
        :disabled="disabled"
        @click="toggle()"
        :aria-expanded="isOpen"
        :aria-disabled="disabled"
        class="flex w-full list-none cursor-pointer items-center justify-between gap-2 rounded-none border-0 bg-transparent py-3 text-left text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:opacity-80 focus:outline-none focus:ring-0 disabled:pointer-events-none disabled:opacity-50"
    >
        <span class="min-w-0 flex-1">{{ $heading ?? '' }}</span>
        <span
            class="shrink-0 text-[#706f6c] dark:text-[#A1A09A] transition-transform duration-200"
            :class="isOpen && 'rotate-90'"
            aria-hidden="true"
        >
            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>
    </button>
    <div
        class="grid transition-[grid-template-rows] duration-200 ease-out"
        :style="isOpen ? 'grid-template-rows: 1fr' : 'grid-template-rows: 0fr'"
    >
        <div class="min-h-0 overflow-hidden text-[#706f6c] dark:text-[#A1A09A]">
            <div class="pb-3 pt-0 text-sm leading-relaxed">
                {{ $contentSlot }}
            </div>
        </div>
    </div>
</div>
