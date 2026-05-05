<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Models\Tag;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Tag Details')
                ->schema([
                    TextInput::make('slug')
                        ->required()
                        ->readOnly()
                        ->unique(Tag::class, 'slug', ignoreRecord: true)
                        ->columnSpanFull(),

                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('Indonesian (ID)')
                                ->schema([
                                    TextInput::make('name.id')
                                        ->label('Tag Name (ID)')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                                        ->columnSpanFull(),
                                ]),

                            Tab::make('English (EN)')
                                ->schema([
                                    TextInput::make('name.en')
                                        ->label('Tag Name (EN)')
                                        ->maxLength(255)
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
