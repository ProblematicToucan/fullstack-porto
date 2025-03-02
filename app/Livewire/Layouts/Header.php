<?php

namespace App\Livewire\Layouts;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Contracts\Support\Arrayable;
use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        return view('livewire.layouts.header', [
            'navigation' => $this->getNavigation()
        ]);
    }

    /**
     * Summary of getNavigation
     * @return Arrayable|NavigationItem[]
     */
    private function getNavigation(): array|Arrayable
    {
        $navGroups = Filament::getNavigation();
        return reset($navGroups)->getItems();
    }
}
