<?php

namespace App\Filament\Pages;

use App\Filament\Actions\TranslateAction;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Services\ImageConverter;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManagePersonalSiteSettings extends Page
{
    protected string $view = 'filament.pages.manage-personal-site-settings';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-circle';

    protected static \UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Personal Site';

    protected static ?string $title = 'Personal Site Settings';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            SiteSetting::personal()->attributesToArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make('Identity')
                    ->schema([
                        Select::make('personal_member_id')
                            ->label('Profile Person')
                            ->options(fn () => TeamMember::where('is_visible', true)
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn ($m) => [$m->id => $m->fullName()]))
                            ->searchable()
                            ->nullable()
                            ->helperText('The team member whose profile is shown as the homepage in Personal Site Mode.'),

                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('Indonesian (ID)')
                                    ->schema([
                                        TextInput::make('site_title.id')
                                            ->label('Name / Brand (ID)')
                                            ->required()
                                            ->maxLength(100),

                                        TextInput::make('site_tagline.id')
                                            ->label('Tagline (ID)')
                                            ->maxLength(160),

                                        Textarea::make('site_description.id')
                                            ->label('Meta Description (ID)')
                                            ->rows(3)
                                            ->maxLength(300)
                                            ->helperText('Used in <meta name="description"> and Open Graph tags.'),
                                    ]),

                                Tab::make('English (EN)')
                                    ->schema([
                                        TextInput::make('site_title.en')
                                            ->label('Name / Brand (EN)')
                                            ->maxLength(100),

                                        TextInput::make('site_tagline.en')
                                            ->label('Tagline (EN)')
                                            ->maxLength(160),

                                        Textarea::make('site_description.en')
                                            ->label('Meta Description (EN)')
                                            ->rows(3)
                                            ->maxLength(300)
                                            ->helperText('Used in <meta name="description"> and Open Graph tags.'),
                                    ]),
                            ])
                            ->columnSpanFull(),

                        TranslateAction::make(['site_title', 'site_tagline', 'site_description']),
                    ])
                    ->columns(1),

                Section::make('Introduction & Biography')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('Indonesian (ID)')
                                    ->schema([
                                        RichEditor::make('vision.id')
                                            ->label('Introduction (ID)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),

                                        RichEditor::make('mission.id')
                                            ->label('Biography (ID)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),
                                    ]),

                                Tab::make('English (EN)')
                                    ->schema([
                                        RichEditor::make('vision.en')
                                            ->label('Introduction (EN)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),

                                        RichEditor::make('mission.en')
                                            ->label('Biography (EN)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),

                        TranslateAction::make([], ['mission', 'vision']),
                    ])
                    ->columns(1),

                Section::make('Branding')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo / Profile Brand Image')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->maxSize(2048)
                            ->automaticallyResizeImagesToHeight(128)
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->saveUploadedFileUsing(fn (TemporaryUploadedFile $file) => ImageConverter::toWebp($file, 'site'))
                            ->helperText('PNG, JPG or WebP · max 2 MB · auto-resized to 128px height · saved as WebP'),

                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/x-icon', 'image/png'])
                            ->maxSize(1024)
                            ->automaticallyResizeImagesToWidth(32)
                            ->automaticallyResizeImagesToHeight(32)
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->helperText('.ico or PNG · max 1 MB · auto-resized to 32×32px'),
                    ])
                    ->columns(2),

                Section::make('Contact Info')
                    ->description('Displayed in the site header and on the personal homepage contact strip.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('hello@yourname.com'),

                        TextInput::make('contact_address')
                            ->label('Address')
                            ->maxLength(255)
                            ->placeholder('City, Country'),

                        TextInput::make('contact_phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50)
                            ->placeholder('+62 812 3456 7890'),

                        TextInput::make('contact_working_hours')
                            ->label('Availability')
                            ->maxLength(100)
                            ->placeholder('Mon–Fri, 09:00–17:00'),

                        Textarea::make('maps_embed_url')
                            ->label('Google Maps Embed URL')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Paste only the src URL from the Google Maps embed iframe.'),
                    ])
                    ->columns(2),

                Section::make('Theme Colors')
                    ->description('Customize the personal site color palette. Changes apply instantly — no rebuild needed.')
                    ->schema([
                        ColorPicker::make('color_light_bg')
                            ->label('Light Background')
                            ->helperText('Main page background in light mode.'),

                        ColorPicker::make('color_dark_bg')
                            ->label('Dark Background')
                            ->helperText('Main page background in dark mode.'),

                        ColorPicker::make('color_light_text')
                            ->label('Light Text')
                            ->helperText('Primary text color in light mode.'),

                        ColorPicker::make('color_dark_text')
                            ->label('Dark Text')
                            ->helperText('Primary text color in dark mode.'),

                        ColorPicker::make('color_accent')
                            ->label('Accent — Light Mode')
                            ->helperText('Buttons, links, and highlights in light mode.'),

                        ColorPicker::make('color_accent_dark')
                            ->label('Accent — Dark Mode')
                            ->helperText('Buttons, links, and highlights in dark mode.'),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::personal()->update($data);

        Cache::forget('site_setting_en');
        Cache::forget('site_setting_id');

        Notification::make()
            ->title('Personal site settings saved.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }
}
