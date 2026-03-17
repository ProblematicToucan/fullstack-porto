<?php

namespace App\View\Components\Ui;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccordionItem extends Component
{
    /**
     * Create a new component instance.
     *
     * Flux-style accordion item. Supports shorthand (heading prop + default slot) or
     * full form (x-slot:heading and x-slot:content / default slot).
     *
     * @param  string|null  $heading  Shorthand for heading (omit when using slot).
     * @param  bool  $expanded  If true, expanded by default. Default: false.
     * @param  bool  $disabled  If true, cannot be toggled. Default: false.
     */
    public function __construct(
        public ?string $heading = null,
        public bool $expanded = false,
        public bool $disabled = false,
    ) {
        //
    }

    public function render(): View
    {
        return view('components.ui.accordion-item');
    }
}
