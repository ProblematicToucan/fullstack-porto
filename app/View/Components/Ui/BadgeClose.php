<?php

namespace App\View\Components\Ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BadgeClose extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  string|null  $icon  Blade Icons name for the close icon. Default: heroicon-o-x-mark.
     * @param  string  $iconVariant  Icon variant (for future use; Blade Icons use one set per icon).
     */
    public function __construct(
        public ?string $icon = null,
        public string $iconVariant = 'mini',
    ) {
        $this->icon ??= 'heroicon-o-x-mark';
    }

    public function iconSizeClasses(): string
    {
        return 'size-3';
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.badge-close');
    }
}
