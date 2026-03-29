<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn ($get) => Str::slug((string) ($get('title') ?? '')))
                    ->helperText('Generated from title when you save.'),
                Section::make('Description')
                    ->schema([
                        Builder::make('description')
                            ->hiddenLabel()
                            ->blocks([
                                Block::make('paragraph')
                                    ->label('Paragraph')
                                    ->icon(Heroicon::Bars3BottomLeft)
                                    ->schema([
                                        RichEditor::make('text')
                                            ->disableToolbarButtons([
                                                'attachFiles',
                                            ])
                                            ->hiddenLabel(),
                                    ]),
                                Block::make('code')
                                    ->label('Code')
                                    ->icon(Heroicon::CodeBracket)
                                    ->schema([
                                        Textarea::make('text')
                                            ->rows(10)
                                            ->extraInputAttributes(['class' => 'font-mono text-sm'])
                                            ->hiddenLabel(),
                                    ]),
                                Block::make('image')
                                    ->label('Image')
                                    ->icon(Heroicon::Photo)
                                    ->schema([
                                        FileUpload::make('image')
                                            ->hiddenLabel()
                                            ->disk(config('filesystems.uploads_disk'))
                                            ->directory('project-description-images')
                                            ->visibility('public')
                                            ->image()
                                            ->imageEditor(),
                                    ]),
                            ])
                            ->blockNumbers()
                            ->blockIcons()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                TextInput::make('project_url')
                    ->url()
                    ->label('Project URL'),
                TextInput::make('repo_url')
                    ->url()
                    ->label('Repository URL'),
                FileUpload::make('image')
                    ->disk(config('filesystems.uploads_disk'))
                    ->image()
                    ->directory('projects')
                    ->visibility('public'),
                Toggle::make('is_featured'),
            ]);
    }
}
