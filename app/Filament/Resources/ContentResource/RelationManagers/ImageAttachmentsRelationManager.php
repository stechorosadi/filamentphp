<?php

namespace App\Filament\Resources\ContentResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImageAttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'imageAttachments';

    protected static ?string $title = 'Image Attachments';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            FileUpload::make('path')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('content-images')
                ->acceptedFileTypes(['image/jpeg', 'image/png'])
                ->maxSize(1024)
                ->required()
                ->columnSpanFull(),

            TextInput::make('caption')
                ->label('Caption / Title')
                ->maxLength(255)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                ImageColumn::make('path')
                    ->label('Image')
                    ->disk('public')
                    ->imageHeight(60),

                TextColumn::make('caption')
                    ->label('Caption')
                    ->placeholder('—'),

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
