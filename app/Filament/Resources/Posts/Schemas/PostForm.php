<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get, $record = null): void {
                        $currentSlug = $get('slug');
                        $newSlug = Str::slug($state ?? '');
                        $previousTitle = $record?->title;
                        $previousAutoSlug = $previousTitle !== null ? Str::slug($previousTitle) : null;
                        $shouldSet = $currentSlug === ''
                            || ($previousAutoSlug !== null && $currentSlug === $previousAutoSlug);
                        if ($shouldSet) {
                            $set('slug', $newSlug);
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-filled from title; you can edit manually.'),
                Toggle::make('is_public'),
                RichEditor::make('content')
                    ->json()
                    ->columnSpanFull(),
            ]);
    }
}
