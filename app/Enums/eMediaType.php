<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum eMediaType: string implements HasLabel, HasIcon
{
    case Image = 'image';
    case Video = 'video';
    case Audio = 'audio';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Image => 'heroicon-o-photo',
            self::Video => 'heroicon-o-film',
            self::Audio => 'heroicon-o-musical-note',
        };
    }

    /**
     * @return string|null
     */

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
