<button
    type="button"
    wire:click="toggle"
    wire:transition="guest-assistant-surface"
    class="flex size-14 shrink-0 items-center justify-center rounded-full border border-[#e3e3e0] bg-[#1b1b18] text-white shadow-lg transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-[#1b1b18]/30 dark:border-[#3E3E3A] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:focus:ring-white/30"
    aria-label="{{ __('Open assistant chat') }}"
>
    {!! svg('heroicon-o-chat-bubble-left-right', 'size-7', ['aria-hidden' => 'true'])->toHtml() !!}
</button>
