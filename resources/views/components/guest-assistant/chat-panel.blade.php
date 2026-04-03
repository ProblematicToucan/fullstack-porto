<div
    class="flex w-[min(100vw-2rem,22rem)] flex-col overflow-hidden rounded-2xl border border-[#e3e3e0] bg-[#FDFDFC] shadow-2xl dark:border-[#3E3E3A] dark:bg-[#141414]"
    data-guest-assistant-panel
    wire:transition="guest-assistant-surface"
>
    @include('components.guest-assistant.chat-header')
    @include('components.guest-assistant.chat-thread')
    @include('components.guest-assistant.chat-composer')
</div>
