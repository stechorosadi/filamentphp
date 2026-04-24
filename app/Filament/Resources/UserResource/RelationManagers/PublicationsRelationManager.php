<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\UserPublication;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PublicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'publications';

    protected static ?string $title = 'Publications';

    public static function getTypes(): array
    {
        return [
            'book'             => 'Book',
            'journal_article'  => 'Journal Article',
            'research_paper'   => 'Research Paper',
            'conference_paper' => 'Conference Paper',
            'other'            => 'Other',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Select::make('type')
                    ->options(self::getTypes())
                    ->required()
                    ->default('other'),

                TextInput::make('publisher')
                    ->label('Publisher / Journal')
                    ->maxLength(255),

                TextInput::make('year')
                    ->label('Year Published')
                    ->numeric()
                    ->minValue(1000)
                    ->maxValue(now()->year + 5),

                TextInput::make('isbn')
                    ->label('ISBN')
                    ->maxLength(30),

                TextInput::make('doi')
                    ->label('DOI')
                    ->maxLength(255),

                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->maxLength(2048)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Description / Abstract')
                    ->rows(4)
                    ->columnSpanFull(),

                FileUpload::make('file_path')
                    ->label('File / Cover')
                    ->disk('public')
                    ->directory('user-publications')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->maxSize(5120)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::getTypes()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'book'             => 'success',
                        'journal_article'  => 'info',
                        'research_paper'   => 'warning',
                        'conference_paper' => 'danger',
                        default            => 'gray',
                    }),

                TextColumn::make('publisher')
                    ->label('Publisher / Journal')
                    ->placeholder('—')
                    ->limit(30),

                TextColumn::make('year')
                    ->label('Year')
                    ->placeholder('—'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
