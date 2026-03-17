<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state ?? ''))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-filled from title; you can edit manually.'),
                KeyValue::make('description')
                    ->columnSpanFull(),
                TextInput::make('project_url')
                    ->url()
                    ->label('Project URL'),
                TextInput::make('repo_url')
                    ->url()
                    ->label('Repository URL'),
                FileUpload::make('image')
                    ->image()
                    ->directory('projects'),
                Toggle::make('is_featured'),
            ]);
    }
}
