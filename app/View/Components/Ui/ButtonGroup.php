<?php

namespace App\View\Components\Ui;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonGroup extends Component
{
    /**
     * Create a new component instance.
     *
     * Renders a wrapper so direct child buttons share borders (fused group):
     * first button rounded-l only, last rounded-r only, middle buttons square;
     * adjacent borders overlap for a single shared edge.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.ui.button-group');
    }
}
