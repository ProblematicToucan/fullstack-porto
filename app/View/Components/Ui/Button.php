<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * When used with Livewire (wire:click or type="submit" inside a Livewire form),
     * the button automatically shows a loading spinner and is disabled (pointer-events
     * disabled via disabled state) until the request completes.
     *
     * @param  string  $variant  One of: primary, secondary, outline, ghost, subtle, danger
     * @param  string  $size  One of: sm, md, lg
     * @param  string  $type  HTML button type: button, submit, reset
     * @param  bool  $loading  When true (default), shows spinner and disables during Livewire requests. Set :loading="false" to disable.
     * @param  string|null  $icon  Optional leading icon (any Blade Icons name, e.g. heroicon-o-ellipsis-horizontal)
     * @param  string|null  $iconTrailing  Optional trailing icon (any Blade Icons name)
     */
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public string $type = 'button',
        public bool $loading = true,
        public ?string $icon = null,
        public ?string $iconTrailing = null,
    ) {
        //
    }

    /**
     * Icon size classes per button size (for use with Blade Icons svg() helper).
     */
    public function iconSizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'size-3.5',
            'lg' => 'size-5',
            default => 'size-4',
        };
    }

    /**
     * Base classes shared by all buttons.
     */
    public function baseClasses(): string
    {
        return 'inline-flex items-center justify-center rounded-sm border text-sm font-medium leading-normal transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';
    }

    /**
     * Variant-specific classes.
     */
    public function variantClasses(): string
    {
        return match ($this->variant) {
            'primary' => 'bg-[#1b1b18] border-black text-white hover:bg-black hover:border-black dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white',
            'secondary' => 'bg-neutral-100 border-neutral-300 text-neutral-900 hover:bg-neutral-200 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-100 dark:hover:bg-neutral-700',
            'outline' => 'border-black text-black bg-transparent hover:bg-black hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black',
            'ghost' => 'border-transparent bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800',
            'subtle' => 'border-transparent bg-neutral-100/80 text-neutral-600 hover:bg-neutral-200/80 dark:bg-neutral-800/80 dark:text-neutral-400 dark:hover:bg-neutral-700/80',
            'danger' => 'bg-red-600 border-red-600 text-white hover:bg-red-700 hover:border-red-700 dark:bg-red-500 dark:hover:bg-red-600',
            default => 'bg-[#1b1b18] border-black text-white hover:bg-black dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white',
        };
    }

    /**
     * Size-specific classes.
     */
    public function sizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'px-3 py-1 text-xs',
            'lg' => 'px-6 py-2.5 text-base',
            default => 'px-5 py-1.5',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }
}
