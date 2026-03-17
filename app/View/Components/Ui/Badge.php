<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    /** Flux-style colors (soft and solid variants). */
    public const COLORS = [
        'zinc', 'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald',
        'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose',
    ];

    /**
     * Create a new component instance.
     *
     * @param  string  $color  Badge color (e.g. zinc, red, blue). Default: zinc.
     * @param  string  $size  Badge size: sm, lg. Default: md (no prop).
     * @param  bool  $rounded  If true, fully rounded (pill) border radius.
     * @param  string  $variant  Style variant: default (soft) or solid.
     * @param  string|null  $icon  Blade Icons name for leading icon (e.g. heroicon-o-user-circle).
     * @param  string|null  $iconTrailing  Blade Icons name for trailing icon.
     * @param  string  $as  HTML element: div or button. Default: div.
     * @param  string|array|null  $inset  Inset sides: top, bottom, left, right or combination (e.g. "top bottom" or ['top','bottom']).
     */
    public function __construct(
        public string $color = 'zinc',
        public string $size = 'md',
        public bool $rounded = false,
        public string $variant = 'soft',
        public ?string $icon = null,
        public ?string $iconTrailing = null,
        public string $as = 'div',
        public string|array|null $inset = null,
    ) {
        if (! in_array($this->color, self::COLORS, true)) {
            $this->color = 'zinc';
        }
    }

    public function isButton(): bool
    {
        return $this->as === 'button';
    }

    public function baseClasses(): string
    {
        return 'inline-flex items-center gap-1 font-medium border transition-colors';
    }

    public function sizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'px-2 py-0.5 text-xs',
            'lg' => 'px-3 py-1 text-sm',
            default => 'px-2.5 py-0.5 text-xs',
        };
    }

    public function roundedClasses(): string
    {
        return $this->rounded ? 'rounded-full' : 'rounded-md';
    }

    /**
     * Soft (default) variant: light background + border + text.
     */
    public function softColorClasses(): string
    {
        return match ($this->color) {
            'zinc' => 'bg-zinc-100 border-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300',
            'red' => 'bg-red-100 border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300',
            'orange' => 'bg-orange-100 border-orange-200 text-orange-700 dark:bg-orange-900/30 dark:border-orange-800 dark:text-orange-300',
            'amber' => 'bg-amber-100 border-amber-200 text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-300',
            'yellow' => 'bg-yellow-100 border-yellow-200 text-yellow-700 dark:bg-yellow-900/30 dark:border-yellow-800 dark:text-yellow-300',
            'lime' => 'bg-lime-100 border-lime-200 text-lime-700 dark:bg-lime-900/30 dark:border-lime-800 dark:text-lime-300',
            'green' => 'bg-green-100 border-green-200 text-green-700 dark:bg-green-900/30 dark:border-green-800 dark:text-green-300',
            'emerald' => 'bg-emerald-100 border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300',
            'teal' => 'bg-teal-100 border-teal-200 text-teal-700 dark:bg-teal-900/30 dark:border-teal-800 dark:text-teal-300',
            'cyan' => 'bg-cyan-100 border-cyan-200 text-cyan-700 dark:bg-cyan-900/30 dark:border-cyan-800 dark:text-cyan-300',
            'sky' => 'bg-sky-100 border-sky-200 text-sky-700 dark:bg-sky-900/30 dark:border-sky-800 dark:text-sky-300',
            'blue' => 'bg-blue-100 border-blue-200 text-blue-700 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-300',
            'indigo' => 'bg-indigo-100 border-indigo-200 text-indigo-700 dark:bg-indigo-900/30 dark:border-indigo-800 dark:text-indigo-300',
            'violet' => 'bg-violet-100 border-violet-200 text-violet-700 dark:bg-violet-900/30 dark:border-violet-800 dark:text-violet-300',
            'purple' => 'bg-purple-100 border-purple-200 text-purple-700 dark:bg-purple-900/30 dark:border-purple-800 dark:text-purple-300',
            'fuchsia' => 'bg-fuchsia-100 border-fuchsia-200 text-fuchsia-700 dark:bg-fuchsia-900/30 dark:border-fuchsia-800 dark:text-fuchsia-300',
            'pink' => 'bg-pink-100 border-pink-200 text-pink-700 dark:bg-pink-900/30 dark:border-pink-800 dark:text-pink-300',
            'rose' => 'bg-rose-100 border-rose-200 text-rose-700 dark:bg-rose-900/30 dark:border-rose-800 dark:text-rose-300',
            default => 'bg-zinc-100 border-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300',
        };
    }

    /**
     * Solid variant: bold background, no border, white/dark text.
     */
    public function solidColorClasses(): string
    {
        return match ($this->color) {
            'zinc' => 'bg-zinc-600 border-zinc-600 text-white dark:bg-zinc-500 dark:border-zinc-500',
            'red' => 'bg-red-600 border-red-600 text-white dark:bg-red-500 dark:border-red-500',
            'orange' => 'bg-orange-600 border-orange-600 text-white dark:bg-orange-500 dark:border-orange-500',
            'amber' => 'bg-amber-600 border-amber-600 text-white dark:bg-amber-500 dark:border-amber-500',
            'yellow' => 'bg-yellow-500 border-yellow-500 text-black dark:bg-yellow-400 dark:border-yellow-400 dark:text-black',
            'lime' => 'bg-lime-600 border-lime-600 text-white dark:bg-lime-500 dark:border-lime-500',
            'green' => 'bg-green-600 border-green-600 text-white dark:bg-green-500 dark:border-green-500',
            'emerald' => 'bg-emerald-600 border-emerald-600 text-white dark:bg-emerald-500 dark:border-emerald-500',
            'teal' => 'bg-teal-600 border-teal-600 text-white dark:bg-teal-500 dark:border-teal-500',
            'cyan' => 'bg-cyan-600 border-cyan-600 text-white dark:bg-cyan-500 dark:border-cyan-500',
            'sky' => 'bg-sky-600 border-sky-600 text-white dark:bg-sky-500 dark:border-sky-500',
            'blue' => 'bg-blue-600 border-blue-600 text-white dark:bg-blue-500 dark:border-blue-500',
            'indigo' => 'bg-indigo-600 border-indigo-600 text-white dark:bg-indigo-500 dark:border-indigo-500',
            'violet' => 'bg-violet-600 border-violet-600 text-white dark:bg-violet-500 dark:border-violet-500',
            'purple' => 'bg-purple-600 border-purple-600 text-white dark:bg-purple-500 dark:border-purple-500',
            'fuchsia' => 'bg-fuchsia-600 border-fuchsia-600 text-white dark:bg-fuchsia-500 dark:border-fuchsia-500',
            'pink' => 'bg-pink-600 border-pink-600 text-white dark:bg-pink-500 dark:border-pink-500',
            'rose' => 'bg-rose-600 border-rose-600 text-white dark:bg-rose-500 dark:border-rose-500',
            default => 'bg-zinc-600 border-zinc-600 text-white dark:bg-zinc-500 dark:border-zinc-500',
        };
    }

    public function colorClasses(): string
    {
        return $this->variant === 'solid'
            ? $this->solidColorClasses()
            : $this->softColorClasses();
    }

    /**
     * Inset: negative margins. Flux options: top, bottom, left, right or combination.
     *
     * @return string Tailwind classes (e.g. -mt-1 -mb-1)
     */
    public function insetClasses(): string
    {
        if ($this->inset === null || $this->inset === '') {
            return '';
        }
        $sides = is_array($this->inset)
            ? $this->inset
            : array_map('trim', explode(' ', (string) $this->inset));
        $classes = [];
        if (in_array('top', $sides, true)) {
            $classes[] = '-mt-0.5';
        }
        if (in_array('bottom', $sides, true)) {
            $classes[] = '-mb-0.5';
        }
        if (in_array('left', $sides, true)) {
            $classes[] = '-ml-0.5';
        }
        if (in_array('right', $sides, true)) {
            $classes[] = '-mr-0.5';
        }

        return implode(' ', $classes);
    }

    public function iconSizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'size-3',
            'lg' => 'size-4',
            default => 'size-3.5',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }
}
