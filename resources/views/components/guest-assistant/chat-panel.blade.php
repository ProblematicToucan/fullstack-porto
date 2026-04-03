<div
    class="flex w-[min(100vw-2rem,22rem)] flex-col overflow-hidden rounded-2xl border border-[#e3e3e0] bg-[#FDFDFC] shadow-2xl dark:border-[#3E3E3A] dark:bg-[#141414]"
    data-guest-assistant-panel
    wire:transition="guest-assistant-surface"
    x-data="{
        optimisticUser: null,
        nearBottomThresholdPx: 80,
        init() {
            $wire.$watch('thread', () => {
                this.optimisticUser = null
            })
            // Panel mounts only when the chat is opened; land on the latest messages (matches guest-assistant-surface ~220ms transition).
            this.scheduleScrollToBottom()
            setTimeout(() => this.scheduleScrollToBottom(), 240)
        },
        threadScrollEl() {
            return this.$refs.threadRoot
        },
        isNearBottom() {
            const el = this.threadScrollEl()
            if (! el) {
                return true
            }
            const { scrollTop, scrollHeight, clientHeight } = el
            return scrollHeight - scrollTop - clientHeight <= this.nearBottomThresholdPx
        },
        scrollThreadToBottom() {
            const el = this.threadScrollEl()
            if (! el) {
                return
            }
            el.scrollTop = el.scrollHeight
        },
        scheduleScrollToBottom() {
            this.$nextTick(() => {
                this.scrollThreadToBottom()
                requestAnimationFrame(() => this.scrollThreadToBottom())
            })
        },
        submitSend() {
            const raw = $wire.message ?? ''
            const msg = (typeof raw === 'string' ? raw : String(raw)).trim()
            if (! msg) {
                return
            }
            const stickToBottom = this.isNearBottom()
            this.optimisticUser = msg
            $wire.set('message', '')
            if (stickToBottom) {
                this.scheduleScrollToBottom()
            }
            $wire.send(msg).finally(() => {
                this.optimisticUser = null
                if (stickToBottom) {
                    this.scheduleScrollToBottom()
                }
            })
        },
    }"
>
    @include('components.guest-assistant.chat-header')
    @include('components.guest-assistant.chat-thread')
    @include('components.guest-assistant.chat-composer')
</div>
