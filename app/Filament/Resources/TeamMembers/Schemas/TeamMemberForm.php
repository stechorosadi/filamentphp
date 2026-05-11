<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Enums\TeamMemberStatus;
use App\Filament\Actions\TranslateAction;
use App\Models\User;
use App\Services\ImageConverter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Member')
                    ->schema([
                        Select::make('user_id')
                            ->label('Linked User Account')
                            ->options(User::orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->live()
                            ->helperText('Leave blank to enter a name manually.'),

                        TextInput::make('nickname')
                            ->label('Nickname / URL Slug')
                            ->required()
                            ->maxLength(80)
                            ->rules(fn ($record) => [
                                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                                Rule::unique('team_members', 'nickname')->ignore($record?->id),
                            ])
                            ->helperText('Lowercase letters, numbers, and hyphens only. Used in the public URL, e.g. "john-doe".'),

                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('Enter name manually')
                            ->maxLength(150)
                            ->required(fn (Get $get): bool => blank($get('user_id')))
                            ->visible(fn (Get $get): bool => blank($get('user_id')))
                            ->helperText('Required when no user account is linked.'),

                        Textarea::make('word_of_wisdom')
                            ->label('Word of Wisdom')
                            ->placeholder('Share a wise word about being an archivist…')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('employee_number')
                            ->label('Employee Number')
                            ->maxLength(50),
                    ])
                    ->columns(2),

                Section::make('Titles & Position')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('Indonesian (ID)')
                                    ->schema([
                                        TextInput::make('front_title.id')
                                            ->label('Front Title (ID)')
                                            ->placeholder('Dr., Prof., Ir.')
                                            ->maxLength(50),

                                        TextInput::make('back_title.id')
                                            ->label('Back Title (ID)')
                                            ->placeholder('M.Sc., S.H., Ph.D.')
                                            ->maxLength(100),

                                        TextInput::make('position.id')
                                            ->label('Position (ID)')
                                            ->required()
                                            ->maxLength(150)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Tab::make('English (EN)')
                                    ->schema([
                                        TextInput::make('front_title.en')
                                            ->label('Front Title (EN)')
                                            ->placeholder('Dr., Prof., Ir.')
                                            ->maxLength(50),

                                        TextInput::make('back_title.en')
                                            ->label('Back Title (EN)')
                                            ->placeholder('M.Sc., S.H., Ph.D.')
                                            ->maxLength(100),

                                        TextInput::make('position.en')
                                            ->label('Position (EN)')
                                            ->maxLength(150)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpanFull(),

                        TranslateAction::make(['front_title', 'back_title', 'position']),
                    ]),

                Section::make('Photo')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Team Photo')
                            ->image()
                            ->disk('public')
                            ->directory('team')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->maxSize(5120)
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1000')
                            ->automaticallyResizeImagesToHeight('1000')
                            ->saveUploadedFileUsing(fn (TemporaryUploadedFile $file) => ImageConverter::toWebp($file, 'team'))
                            ->helperText('PNG, JPG or WebP · max 5 MB · resized to 1000×1000px · saved as WebP'),
                    ])
                    ->columns(1),

                Section::make('Social Media')
                    ->description('Leave blank to hide the icon on the frontend.')
                    ->schema([
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()->maxLength(255)->placeholder('https://instagram.com/handle'),

                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()->maxLength(255)->placeholder('https://facebook.com/handle'),

                        TextInput::make('x_url')
                            ->label('X (Twitter)')
                            ->url()->maxLength(255)->placeholder('https://x.com/handle'),

                        TextInput::make('threads_url')
                            ->label('Threads')
                            ->url()->maxLength(255)->placeholder('https://threads.net/@handle'),

                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()->maxLength(255)->placeholder('https://youtube.com/@channel'),
                    ])
                    ->columns(2),

                Section::make('Display')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                TeamMemberStatus::Active->value => 'Active',
                                TeamMemberStatus::Transferred->value => 'Transferred',
                                TeamMemberStatus::Retired->value => 'Retired',
                            ])
                            ->default(TeamMemberStatus::Active->value)
                            ->required()
                            ->helperText('Former members (Transferred / Retired) are shown in a separate section on the team page.'),

                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),

                        Toggle::make('is_visible')
                            ->label('Visible on Team Page')
                            ->default(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
