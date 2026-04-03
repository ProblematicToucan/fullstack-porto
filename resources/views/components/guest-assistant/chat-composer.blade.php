<div class="border-t border-[#e3e3e0] p-3 dark:border-[#3E3E3A]" data-guest-assistant-composer>
    @if ($error)
        <p class="mb-2 text-xs text-red-600 dark:text-red-400" role="alert">
            {{ $error }}
        </p>
    @endif

    <form @submit.prevent="submitSend()" class="flex flex-col gap-2">
        <label class="sr-only" for="guest-assistant-message">{{ __('Message') }}</label>
        <div
            class="flex flex-col gap-2 rounded-xl border border-[#e3e3e0] bg-white p-2 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]"
        >
            <textarea
                id="guest-assistant-message"
                x-ref="composerInput"
                wire:model="message"
                rows="1"
                maxlength="10000"
                placeholder="{{ __('How can I help you today?') }}"
                class="scrollbar-thin min-h-[2.75rem] w-full resize-none overflow-x-hidden border-0 bg-transparent px-2 py-1.5 text-sm leading-5 text-[#1b1b18] placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-[#EDEDEC]"
                x-init="$nextTick(() => composerResize())"
                @input="composerResize()"
                @keydown.meta.enter.prevent="submitSend()"
                @keydown.ctrl.enter.prevent="submitSend()"
            ></textarea>

            <div
                class="flex items-center justify-between gap-2 border-t border-[#e3e3e0] pt-2 dark:border-[#3E3E3A]"
            >
                @include('components.guest-assistant.composer-actions-leading')

                <div class="flex items-center gap-2">
                    <span class="hidden text-[10px] text-neutral-400 sm:inline" aria-hidden="true">
                        ⌘↵ {{ __('to send') }}
                    </span>
                    <x-ui.button
                        type="submit"
                        variant="primary"
                        size="sm"
                        icon="heroicon-o-paper-airplane"
                        :loading="true"
                        loading-target="send"
                    />
                </div>
            </div>
        </div>
    </form>
</div>
