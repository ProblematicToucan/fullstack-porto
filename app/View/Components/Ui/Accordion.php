<?php

namespace App\View\Components\Ui;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    /**
     * Create a new component instance.
     *
     * Flux-style accordion: collapse/expand sections. Use with accordion.item, accordion.heading, accordion.content.
     *
     * @param  bool  $exclusive  If true, only one item can be expanded at a time. Default: false.
     * @param  bool  $transition  If true, enables expand/collapse transitions. Default: false.
     * @param  string  $variant  'default' or 'reverse' (icon before heading instead of after). Default: default.
     */
    public function __construct(
        public bool $exclusive = false,
        public bool $transition = false,
        public string $variant = 'default',
    ) {
        //
    }

    public function render(): View
    {
        return view('components.ui.accordion');
    }
}
