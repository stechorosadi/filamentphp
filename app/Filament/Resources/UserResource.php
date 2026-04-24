<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                // Left group: identity + password
                Grid::make(1)
                    ->schema([
                        Section::make('Profile Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(User::class, 'email', ignoreRecord: true),

                                Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        
                        Section::make('Profile Image')
                            ->schema([
                                FileUpload::make('avatar_url')
                                    ->label('Avatar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['1:1'])
                                    ->automaticallyResizeImagesToWidth(200)
                                    ->automaticallyResizeImagesToHeight(200)
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyUpscaleImagesWhenResizing()
                                    ->imagePreviewHeight('150')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(1),

                // Right group: avatar + roles
                Grid::make(1)
                    ->schema([
                        Section::make('Password')
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->minLength(8)
                                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state)),
                            ])
                            ->visible(fn (string $operation): bool => $operation === 'create'),

                        Section::make('Change Password')
                            ->description('Leave all fields blank to keep the current password.')
                            ->schema([
                                TextInput::make('current_password')
                                    ->password()
                                    ->revealable()
                                    ->dehydrated(false)
                                    ->requiredWith('new_password')
                                    ->rules([
                                        fn (?Model $record): \Closure => function (string $attribute, mixed $value, \Closure $fail) use ($record): void {
                                            if (filled($value) && !Hash::check($value, $record?->password)) {
                                                $fail('The current password is incorrect.');
                                            }
                                        },
                                    ]),

                                TextInput::make('new_password')
                                    ->password()
                                    ->revealable()
                                    ->minLength(8)
                                    ->dehydrated(false)
                                    ->confirmed()
                                    ->requiredWith('current_password'),

                                TextInput::make('new_password_confirmation')
                                    ->password()
                                    ->revealable()
                                    ->label('Confirm New Password')
                                    ->dehydrated(false)
                                    ->requiredWith('new_password'),
                            ])
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                    ])
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (User $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'
                    )
                    ->action(
                        Action::make('previewAvatar')
                            ->label('Preview Avatar')
                            ->modalHeading(fn (User $record): string => $record->name)
                            ->modalContent(fn (User $record): HtmlString => new HtmlString(
                                '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">' .
                                '<img src="' . (filled($record->avatar_url)
                                    ? Storage::disk('public')->url($record->avatar_url)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF&size=256'
                                ) . '" style="width:200px;height:200px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.15);">' .
                                '</div>'
                            ))
                            ->modalWidth('sm')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Close')
                    ),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->separator(','),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Profile')
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->label('Avatar')
                            ->disk('public')
                            ->circular()
                            ->defaultImageUrl(fn (User $record): string =>
                                'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF'
                            )
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email')->copyable(),
                            ]),
                    ]),

                Section::make('Roles & Access')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Roles')
                            ->badge()
                            ->separator(',')
                            ->columnSpanFull(),
                    ]),

                Section::make('Timestamps')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->dateTime('M j, Y g:i A')
                                    ->placeholder('Not verified'),

                                TextEntry::make('created_at')
                                    ->label('Created')
                                    ->dateTime('M j, Y g:i A'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->since(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EducationHistoryRelationManager::class,
            RelationManagers\PublicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view'   => Pages\ViewUser::route('/{record}'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
