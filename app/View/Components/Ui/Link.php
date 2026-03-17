<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Link extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  string  $href  The URL the link points to. Required when as="a".
     * @param  string  $variant  Link style: default, ghost, subtle. Default: default.
     * @param  bool  $external  If true, open in a new tab (target="_blank" rel="noopener noreferrer").
     * @param  string  $as  HTML tag: a or button. Default: a.
     */
    public function __construct(
        public string $href = '#',
        public string $variant = 'default',
        public bool $external = false,
        public string $as = 'a',
    ) {
        if (! in_array($this->as, ['a', 'button'], true)) {
            $this->as = 'a';
        }
    }

    public function isButton(): bool
    {
        return $this->as === 'button';
    }

    public function baseClasses(): string
    {
        return 'cursor-pointer font-medium transition-colors focus:outline-none focus:ring-1 focus:ring-offset-1 rounded-sm';
    }

    public function variantClasses(): string
    {
        return match ($this->variant) {
            'ghost' => 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 hover:underline dark:hover:text-zinc-100',
            'subtle' => 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 hover:underline dark:hover:text-zinc-300',
            default => 'text-blue-600 underline hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.link');
    }
}
