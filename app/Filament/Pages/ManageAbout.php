<?php

namespace App\Filament\Pages;

use App\Models\About;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class ManageAbout extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'About';

    protected static ?string $title = 'About';

    protected static ?string $slug = 'about';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected string $view = 'filament.pages.manage-about';

    public function mount(): void
    {
        $record = $this->getRecord();
        $this->form->fill($record->attributesToArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->record($this->getRecord());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('heading')
                    ->label('Heading')
                    ->maxLength(255),
                Textarea::make('body')
                    ->label('Body')
                    ->columnSpanFull()
                    ->rows(6),
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->disk(config('filesystems.uploads_disk'))
                    ->image()
                    ->directory('about')
                    ->visibility('public'),
                KeyValue::make('links')
                    ->label('Links (e.g. label => URL)')
                    ->keyLabel('Label')
                    ->valueLabel('URL')
                    ->columnSpanFull(),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ])
                            ->alignment(Alignment::Start),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $record = $this->getRecord();
        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title('About saved')
            ->send();
    }

    public function getRecord(): About
    {
        return About::firstOrCreate([], [
            'heading' => '',
            'body' => '',
            'avatar' => null,
            'links' => [],
        ]);
    }
}
