<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                    ])
                    ->columns(2),

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

                Section::make('Profile Image')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->image()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->imagePreviewHeight('150')
                            ->columnSpanFull(),
                    ]),

                Section::make('Roles')
                    ->schema([
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
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
