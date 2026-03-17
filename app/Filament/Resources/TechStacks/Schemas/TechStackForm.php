<?php

namespace App\Filament\Resources\TechStacks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TechStackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->nullable(),
                TextInput::make('logo')
                    ->nullable()
                    ->url()
                    ->helperText('URL to logo image (optional).'),
                Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
