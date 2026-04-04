<div
    class="flex items-center justify-between gap-3 border-b border-[#e3e3e0] px-4 py-3 dark:border-[#3E3E3A]"
>
    <div class="min-w-0">
        <p class="truncate text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
            {{ __('Portfolio assistant') }}
        </p>
        <p class="truncate text-xs text-neutral-500 dark:text-neutral-400">
            {{ __('Ask about projects, posts, or this site.') }}
        </p>
    </div>
    <x-ui.button
        type="button"
        variant="ghost"
        size="sm"
        :loading="false"
        icon="heroicon-o-x-mark"
        class="shrink-0"
        wire:click="close"
        aria-label="{{ __('Close chat') }}"
    />
</div>
