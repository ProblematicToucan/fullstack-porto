<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\eMediaType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'projectMedia';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('media_type')
                    ->required()
                    ->options(eMediaType::class),
                Forms\Components\TextInput::make('media_url')
                    ->required()
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('media_description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('media_url')
                    ->placeholder('-')
                    ->copyable()
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->limit(20),
                Tables\Columns\TextColumn::make('media_description')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('media_type')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
