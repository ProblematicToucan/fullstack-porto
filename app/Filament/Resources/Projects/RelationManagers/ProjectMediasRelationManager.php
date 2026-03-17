<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectMediasRelationManager extends RelationManager
{
    protected static string $relationship = 'projectMedias';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('media_type')
                    ->required()
                    ->maxLength(255),
                TextInput::make('media_url')
                    ->url()
                    ->required()
                    ->maxLength(255),
                Textarea::make('media_description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('media_type')
            ->columns([
                TextColumn::make('media_type')
                    ->searchable(),
                TextColumn::make('media_url')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('media_description')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
