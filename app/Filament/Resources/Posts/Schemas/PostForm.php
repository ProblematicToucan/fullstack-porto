<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug((string) ($state ?? '')))),
                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated(false)
                    ->default(fn ($get) => Str::slug((string) ($get('title') ?? '')))
                    ->helperText('Generated from title when you save.'),
                Toggle::make('is_public'),
                Section::make('Content')
                    ->schema([
                        Builder::make('content')
                            ->hiddenLabel()
                            ->blocks([
                                Block::make('paragraph')
                                    ->label(fn (?array $state): string => $state === null ? 'Paragraph' : 'Paragraph')
                                    ->icon(Heroicon::Bars3BottomLeft)
                                    ->schema([
                                        RichEditor::make('text')
                                            ->disableToolbarButtons([
                                                'attachFiles',
                                            ])
                                            ->hiddenLabel(),
                                    ]),
                                Block::make('image')
                                    ->label('Image')
                                    ->icon(Heroicon::Photo)
                                    ->schema([
                                        FileUpload::make('image')
                                            ->hiddenLabel()
                                            ->disk(config('filesystems.uploads_disk'))
                                            ->directory('post-images')
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
            ]);
    }
}
