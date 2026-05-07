<?php

namespace App\Filament\Pages;

use App\Filament\Actions\TranslateAction;
use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;

class ManageSiteSettings extends Page
{
    protected string $view = 'filament.pages.manage-site-settings';

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static \UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            SiteSetting::instance()->attributesToArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->schema([
                Section::make('Identity')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('Indonesian (ID)')
                                    ->schema([
                                        TextInput::make('site_title.id')
                                            ->label('Site Title (ID)')
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
                                            ->label('Site Title (EN)')
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

                Section::make('Mission & Vision')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('Indonesian (ID)')
                                    ->schema([
                                        RichEditor::make('mission.id')
                                            ->label('Mission (ID)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),

                                        RichEditor::make('vision.id')
                                            ->label('Vision (ID)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),
                                    ]),

                                Tab::make('English (EN)')
                                    ->schema([
                                        RichEditor::make('mission.en')
                                            ->label('Mission (EN)')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList'])
                                            ->columnSpanFull(),

                                        RichEditor::make('vision.en')
                                            ->label('Vision (EN)')
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
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                            ->maxSize(2048)
                            ->automaticallyResizeImagesToHeight(128)
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->helperText('PNG or JPG · max 2 MB · auto-resized to 128px height'),

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

                Section::make('Social Media')
                    ->description('Add your social media profile URLs. Leave blank to hide.')
                    ->schema([
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->maxLength(255)
                            ->prefix('🌐')
                            ->placeholder('https://facebook.com/yourpage'),

                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->prefix('🌐')
                            ->placeholder('https://instagram.com/yourhandle'),

                        TextInput::make('x_url')
                            ->label('X (Twitter)')
                            ->url()
                            ->maxLength(255)
                            ->prefix('🌐')
                            ->placeholder('https://x.com/yourhandle'),

                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255)
                            ->prefix('🌐')
                            ->placeholder('https://youtube.com/@yourchannel'),
                    ])
                    ->columns(1),

                Section::make('Contact Info')
                    ->description('Displayed in the top bar above the navigation on the frontend.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('info@example.com'),

                        TextInput::make('contact_address')
                            ->label('Address')
                            ->maxLength(255)
                            ->placeholder('123 Main St, City, Country'),

                        TextInput::make('contact_phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50)
                            ->placeholder('+62 21 1234 5678'),

                        TextInput::make('contact_working_hours')
                            ->label('Working Hours')
                            ->maxLength(100)
                            ->placeholder('Mon–Fri, 09:00–17:00'),

                        Textarea::make('maps_embed_url')
                            ->label('Google Maps Embed URL')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('https://www.google.com/maps/embed?pb=...')
                            ->helperText('Paste only the src URL from the Google Maps embed iframe: Share → Embed a map → copy the value inside src="…"'),
                    ])
                    ->columns(2),

                Section::make('Theme Colors')
                    ->description('Customize the site color palette. Changes apply instantly — no rebuild needed.')
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

        SiteSetting::instance()->update($data);

        Cache::forget('site_setting_en');
        Cache::forget('site_setting_id');

        Notification::make()
            ->title('Settings saved.')
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
