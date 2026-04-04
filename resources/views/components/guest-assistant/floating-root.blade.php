<div
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3"
    data-guest-assistant-chat
>
    @if ($isOpen)
        @include('components.guest-assistant.chat-panel')
    @else
        @include('components.guest-assistant.launcher-button')
    @endif
</div>
