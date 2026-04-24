<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static \UnitEnum|string|null $navigationGroup = 'Content Management';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                // Left: content details
                Grid::make(1)
                    ->schema([
                        Section::make('Content Details')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(100)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                                        $set('slug', now()->format('Y-m-d') . '-' . Str::slug((string) $state));
                                    })
                                    ->columnSpanFull(),

                                TextInput::make('slug')
                                    ->required()
                                    ->readOnly()
                                    ->unique(Content::class, 'slug', ignoreRecord: true)
                                    ->columnSpanFull(),

                                Textarea::make('excerpt')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                RichEditor::make('content')
                                    ->required()
                                    ->extraInputAttributes(['style' => 'min-height: 480px'])
                                    ->columnSpanFull(),

                                TextInput::make('youtube_url')
                                    ->label('YouTube Embed Link')
                                    ->url()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(1),

                // Right: author + classification + images
                Grid::make(1)
                    ->schema([
                        Section::make('Author')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Author')
                                    ->relationship('user', 'name')
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(fn () => auth()->id())
                                    ->required(),
                            ]),

                        Section::make('Classification')
                            ->schema([
                                Select::make('content_classification_id')
                                    ->label('Classification')
                                    ->relationship('classification', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('content_category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->readOnly(),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Images')
                            ->schema([
                                FileUpload::make('header_image')
                                    ->label('Header Image (1000×400)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('content-headers')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['5:3'])
                                    ->automaticallyResizeImagesToWidth(1000)
                                    ->automaticallyResizeImagesToHeight(600)
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyUpscaleImagesWhenResizing(),

                                FileUpload::make('featured_image')
                                    ->label('Featured Image (1000×400)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('content-featured')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->maxSize(1024)
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['5:3'])
                                    ->automaticallyResizeImagesToWidth(1000)
                                    ->automaticallyResizeImagesToHeight(600)
                                    ->automaticallyResizeImagesMode('cover')
                                    ->automaticallyUpscaleImagesWhenResizing(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('header_image')
                    ->label('Header')
                    ->disk('public')
                    ->imageHeight(40)
                    ->action(
                        Action::make('previewHeaderImage')
                            ->label('Preview Header Image')
                            ->modalHeading(fn (Content $record): string => $record->title)
                            ->modalContent(fn (Content $record): HtmlString => new HtmlString(
                                '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">' .
                                '<img src="' . asset('storage/' . $record->header_image) .
                                '" style="max-width:100%;border-radius:0.5rem;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.15);">' .
                                '</div>'
                            ))
                            ->modalWidth('xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Close')
                    ),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('classification.name')
                    ->label('Classification')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('content_classification_id')
                    ->label('Classification')
                    ->relationship('classification', 'name'),

                SelectFilter::make('content_category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
            ])
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
        return $schema->schema([
            Section::make('Content')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            ImageEntry::make('header_image')
                                ->label('Header Image')
                                ->disk('public')
                                ->action(
                                    Action::make('previewHeaderImage')
                                        ->modalHeading('Header Image')
                                        ->modalContent(fn (Content $record): HtmlString => new HtmlString(
                                            '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">' .
                                            '<img src="' . asset('storage/' . $record->header_image) .
                                            '" style="max-width:100%;border-radius:0.5rem;box-shadow:0 4px 12px rgba(0,0,0,0.15);">' .
                                            '</div>'
                                        ))
                                        ->modalWidth('xl')
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Close')
                                ),

                            ImageEntry::make('featured_image')
                                ->label('Featured Image')
                                ->disk('public')
                                ->action(
                                    Action::make('previewFeaturedImage')
                                        ->modalHeading('Featured Image')
                                        ->modalContent(fn (Content $record): HtmlString => new HtmlString(
                                            '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">' .
                                            '<img src="' . asset('storage/' . $record->featured_image) .
                                            '" style="max-width:100%;border-radius:0.5rem;box-shadow:0 4px 12px rgba(0,0,0,0.15);">' .
                                            '</div>'
                                        ))
                                        ->modalWidth('xl')
                                        ->modalSubmitAction(false)
                                        ->modalCancelActionLabel('Close')
                                ),
                        ]),

                    TextEntry::make('title')->columnSpanFull(),
                    TextEntry::make('slug')->copyable(),
                    TextEntry::make('excerpt')->columnSpanFull(),
                    TextEntry::make('content')
                        ->html()
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'fi-content-prose']),
                    TextEntry::make('youtube_url')->label('YouTube Link')->copyable(),
                ]),

            Section::make('Classification & Author')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('user.name')->label('Author'),
                            TextEntry::make('classification.name')->label('Classification')->badge(),
                            TextEntry::make('category.name')->label('Category')->badge(),
                        ]),

                    TextEntry::make('tags.name')
                        ->label('Tags')
                        ->badge()
                        ->separator(','),
                ]),

            Section::make('Timestamps')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                            TextEntry::make('updated_at')->since(),
                        ]),
                ])
                ->collapsible(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImageAttachmentsRelationManager::class,
            RelationManagers\FileAttachmentsRelationManager::class,
            RelationManagers\LinkAttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'view'   => Pages\ViewContent::route('/{record}'),
            'edit'   => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
