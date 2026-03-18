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
     * When href is provided, the component renders an <a> tag styled as a button,
     * with an optional trailing link arrow icon.
     *
     * @param  string  $variant  One of: primary, secondary, outline, ghost, subtle, danger
     * @param  string  $size  One of: sm, md, lg
     * @param  string  $type  HTML button type: button, submit, reset (ignored when href is set)
     * @param  bool  $loading  When true (default), shows spinner and disables during Livewire requests. Set :loading="false" to disable.
     * @param  string|null  $icon  Optional leading icon (Blade Icons name, e.g. heroicon-o-ellipsis-horizontal)
     * @param  string|null  $iconTrailing  Optional trailing icon: Blade Icons name or built-in (e.g. arrow-up-right)
     * @param  string|null  $href  When set, renders an <a> tag instead of a button
     * @param  bool  $inset  When true with ghost or subtle variant, applies negative margin to negate padding for better alignment
     */
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public string $type = 'button',
        public bool $loading = true,
        public ?string $icon = null,
        public ?string $iconTrailing = null,
        public ?string $href = null,
        public bool $inset = false,
    ) {
        //
    }

    /** Built-in trailing icon names that render a custom SVG (e.g. arrow-up-right). */
    private const BUILT_IN_TRAILING_ICONS = ['arrow-up-right'];

    /**
     * Whether the trailing icon is a built-in (custom SVG) rather than a Blade Icon.
     */
    public function isBuiltInTrailingIcon(): bool
    {
        return $this->iconTrailing !== null
            && in_array($this->iconTrailing, self::BUILT_IN_TRAILING_ICONS, true);
    }

    /**
     * Whether the component should render as a link.
     */
    public function isLink(): bool
    {
        return $this->href !== null && $this->href !== '';
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
        return 'inline-flex items-center justify-center rounded-sm border text-sm font-medium leading-normal transition-colors focus:outline-none focus:ring-1 focus:ring-offset-1 disabled:pointer-events-none disabled:opacity-50';
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
     * When inset is true and variant is ghost or subtle, negative margin classes to negate padding for alignment.
     */
    public function insetClasses(): string
    {
        if (! $this->inset || ! in_array($this->variant, ['ghost', 'subtle'], true)) {
            return '';
        }

        return match ($this->size) {
            'sm' => '-mx-3 -my-1',
            'lg' => '-mx-6 -my-2.5',
            default => '-mx-5 -my-1.5',
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
