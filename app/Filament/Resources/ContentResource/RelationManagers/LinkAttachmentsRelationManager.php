<?php

namespace App\Filament\Resources\ContentResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LinkAttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'linkAttachments';

    protected static ?string $title = 'Link Attachments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('url')
                ->label('URL')
                ->url()
                ->required()
                ->maxLength(2048)
                ->columnSpanFull(),

            TextInput::make('label')
                ->label('Label')
                ->maxLength(255)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('url')
                    ->label('URL')
                    ->copyable()
                    ->limit(50),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
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
