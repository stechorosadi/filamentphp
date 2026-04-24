<?php

namespace App\Filament\Resources\ContentResource\RelationManagers;

use App\Models\ContentImage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\HtmlString;
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
                ->imageEditor()
                ->automaticallyResizeImagesToWidth(1000)
                ->automaticallyResizeImagesMode('contain')
                ->automaticallyUpscaleImagesWhenResizing(false)
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
                    ->imageHeight(60)
                    ->action(
                        Action::make('previewImage')
                            ->modalHeading(fn (ContentImage $record): string => $record->caption ?: 'Image Preview')
                            ->modalContent(fn (ContentImage $record): HtmlString => new HtmlString(
                                '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">' .
                                '<img src="' . asset('storage/' . $record->path) .
                                '" style="max-width:100%;max-height:700px;object-fit:contain;border-radius:0.5rem;box-shadow:0 4px 12px rgba(0,0,0,0.15);">' .
                                '</div>'
                            ))
                            ->modalWidth('xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Close')
                    ),

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
