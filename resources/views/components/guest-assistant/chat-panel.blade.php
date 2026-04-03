<div
    class="flex w-[min(100vw-2rem,22rem)] flex-col overflow-hidden rounded-2xl border border-[#e3e3e0] bg-[#FDFDFC] shadow-2xl dark:border-[#3E3E3A] dark:bg-[#141414]"
    data-guest-assistant-panel
    wire:transition="guest-assistant-surface"
    x-data="{
        optimisticUser: null,
        init() {
            $wire.$watch('thread', () => {
                this.optimisticUser = null
            })
        },
        submitSend() {
            const raw = $wire.message ?? ''
            const msg = (typeof raw === 'string' ? raw : String(raw)).trim()
            if (! msg) {
                return
            }
            this.optimisticUser = msg
            $wire.set('message', '')
            $wire.send(msg).finally(() => {
                this.optimisticUser = null
            })
        },
    }"
>
    @include('components.guest-assistant.chat-header')
    @include('components.guest-assistant.chat-thread')
    @include('components.guest-assistant.chat-composer')
</div>
