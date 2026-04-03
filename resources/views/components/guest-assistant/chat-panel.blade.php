<div
    class="flex w-[min(100vw-2rem,22rem)] flex-col overflow-hidden rounded-2xl border border-[#e3e3e0] bg-[#FDFDFC] shadow-2xl dark:border-[#3E3E3A] dark:bg-[#141414]"
    data-guest-assistant-panel
    wire:transition="guest-assistant-surface"
    x-data="{
        optimisticUser: null,
        composerMaxLines: 4,
        nearBottomThresholdPx: 80,
        init() {
            $wire.$watch('thread', () => {
                this.optimisticUser = null
            })
            $wire.$watch('message', () => {
                this.$nextTick(() => this.composerResize())
            })
            // Panel mounts only when the chat is opened; land on the latest messages (matches guest-assistant-surface ~220ms transition).
            this.scheduleScrollToBottom()
            setTimeout(() => this.scheduleScrollToBottom(), 240)
            this.$nextTick(() => this.composerResize())
            setTimeout(() => this.composerResize(), 240)
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
        composerResize() {
            const el = this.$refs.composerInput
            if (! el) {
                return
            }
            el.style.height = 'auto'
            const cs = getComputedStyle(el)
            const lh = parseFloat(cs.lineHeight)
            const lineHeight = Number.isFinite(lh) && lh > 0 ? lh : 20
            const padY = parseFloat(cs.paddingTop) + parseFloat(cs.paddingBottom)
            const maxH = lineHeight * this.composerMaxLines + padY
            const full = el.scrollHeight
            const next = Math.min(full, maxH)
            el.style.height = `${next}px`
            el.style.overflowY = full > maxH ? 'auto' : 'hidden'
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
