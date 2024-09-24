<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectMediaResource\RelationManagers\ProjectRelationManager;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Info')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->placeholder('empty')
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->placeholder('empty')
                            ->required(),
                        Forms\Components\TextInput::make('project_url')
                            ->placeholder('empty')
                            ->url(),
                        Forms\Components\TextInput::make('repo_url')
                            ->placeholder('empty')
                            ->url(),
                        Forms\Components\Toggle::make('is_featured')
                            ->required(),
                    ])
                    ->columnSpan(['lg' => 2])
                    ->columns(2),
                Forms\Components\Section::make('Project Thumbnail')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->disk('r2')
                            ->directory('project-images')
                            ->visibility('private')
                            ->image()
                            ->imageEditor(),
                    ])
                    ->columnSpan(['lg' => 1]),
                Forms\Components\Section::make('Project Descriptions')
                    ->schema([
                        Forms\Components\Builder::make('description')
                            ->blocks([
                                Forms\Components\Builder\Block::make('Paragraph')
                                    ->schema([
                                        Forms\Components\RichEditor::make('text')
                                            ->hiddenLabel(),
                                    ])
                                    ->icon('heroicon-m-bars-3-bottom-left'),
                                Forms\Components\Builder\Block::make('image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->hiddenLabel()
                                            ->disk('r2')
                                            ->directory('project-images')
                                            ->visibility('private')
                                            ->image()
                                            ->imageEditor()
                                    ])
                                    ->icon('heroicon-m-photo'),
                            ])
                            ->required(),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_url')
                    ->placeholder('-')
                    ->copyable()
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('repo_url')
                    ->placeholder('-')
                    ->copyable()
                    ->icon('heroicon-m-clipboard')
                    ->iconPosition(IconPosition::After)
                    ->iconColor('primary')
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->disk('r2')
                    ->alignCenter()
                    ->visibility('private'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProjectMediaRelationManager::class,
            RelationManagers\CategoriesRelationManager::class,
            RelationManagers\TechStackRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
