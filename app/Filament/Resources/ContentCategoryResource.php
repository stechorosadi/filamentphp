<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentCategoryResource\Pages;
use App\Models\ContentCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ContentCategoryResource extends Resource
{
    protected static ?string $model = ContentCategory::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';

    protected static \UnitEnum|string|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Category Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                    TextInput::make('slug')
                        ->required()
                        ->readOnly()
                        ->unique(ContentCategory::class, 'slug', ignoreRecord: true),
                ])
                ->columns(2),

            Section::make('Media')
                ->schema([
                    Select::make('icon')
                        ->searchable()
                        ->options(fn (): array => collect(glob(base_path('vendor/blade-ui-kit/blade-heroicons/resources/svg/*.svg')))
                            ->mapWithKeys(fn (string $path): array => [
                                'heroicon-'.basename($path, '.svg') => basename($path, '.svg'),
                            ])
                            ->toArray()
                        )
                        ->placeholder('Select a Heroicon')
                        ->helperText('Browse all icons at heroicons.com'),

                    FileUpload::make('image')
                        ->image()
                        ->disk('public')
                        ->directory('content-categories')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/png'])
                        ->maxSize(1024)
                        ->imageAspectRatio('1:1')
                        ->automaticallyCropImagesToAspectRatio()
                        ->automaticallyResizeImagesToWidth('100')
                        ->automaticallyResizeImagesToHeight('100')
                        ->automaticallyResizeImagesMode('cover')
                        ->helperText('PNG only · 1:1 ratio · max 1 MB'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->square()
                    ->imageSize(40),
                TextColumn::make('icon')
                    ->label('Icon')
                    ->formatStateUsing(fn (?string $state): HtmlString => new HtmlString(
                        $state
                            ? svg($state, '', ['style' => 'width:2rem;height:2rem;display:inline-block;vertical-align:middle'])->toHtml()
                            : '<span class="text-gray-400">—</span>'
                    ))
                    ->tooltip(fn ($record): string => $record->icon ?? ''),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('created_at')->dateTime('M j, Y')->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentCategories::route('/'),
            'create' => Pages\CreateContentCategory::route('/create'),
            'edit' => Pages\EditContentCategory::route('/{record}/edit'),
        ];
    }
}
