<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    /** Flux-style colors for text (matches Badge palette). */
    public const COLORS = [
        'default', 'red', 'orange', 'yellow', 'lime', 'green', 'emerald',
        'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose',
    ];

    /**
     * Create a new component instance.
     *
     * @param  string  $size  Size of the text: sm, default, lg, xl. Default: default.
     * @param  string  $variant  Text variant: strong, subtle. Default: default.
     * @param  string  $color  Color of the text (Flux palette). Default: default.
     * @param  bool  $inline  If true, render as span instead of p.
     */
    public function __construct(
        public string $size = 'default',
        public string $variant = 'default',
        public string $color = 'default',
        public bool $inline = false,
    ) {
        if (! in_array($this->color, self::COLORS, true)) {
            $this->color = 'default';
        }
    }

    public function tag(): string
    {
        return $this->inline ? 'span' : 'p';
    }

    public function sizeClasses(): string
    {
        return match ($this->size) {
            'sm' => 'text-sm',
            'lg' => 'text-lg',
            'xl' => 'text-xl',
            default => 'text-base',
        };
    }

    /**
     * Variant (strong/subtle) only applies when color is default.
     */
    public function variantClasses(): string
    {
        if ($this->color !== 'default') {
            return '';
        }

        return match ($this->variant) {
            'strong' => 'text-zinc-900 dark:text-zinc-100',
            'subtle' => 'text-zinc-500 dark:text-zinc-400',
            default => 'text-zinc-700 dark:text-zinc-300',
        };
    }

    public function colorClasses(): string
    {
        if ($this->color === 'default') {
            return $this->variantClasses();
        }

        return match ($this->color) {
            'red' => 'text-red-600 dark:text-red-400',
            'orange' => 'text-orange-600 dark:text-orange-400',
            'yellow' => 'text-yellow-600 dark:text-yellow-400',
            'lime' => 'text-lime-600 dark:text-lime-400',
            'green' => 'text-green-600 dark:text-green-400',
            'emerald' => 'text-emerald-600 dark:text-emerald-400',
            'teal' => 'text-teal-600 dark:text-teal-400',
            'cyan' => 'text-cyan-600 dark:text-cyan-400',
            'sky' => 'text-sky-600 dark:text-sky-400',
            'blue' => 'text-blue-600 dark:text-blue-400',
            'indigo' => 'text-indigo-600 dark:text-indigo-400',
            'violet' => 'text-violet-600 dark:text-violet-400',
            'purple' => 'text-purple-600 dark:text-purple-400',
            'fuchsia' => 'text-fuchsia-600 dark:text-fuchsia-400',
            'pink' => 'text-pink-600 dark:text-pink-400',
            'rose' => 'text-rose-600 dark:text-rose-400',
            default => $this->variantClasses(),
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.text');
    }
}
