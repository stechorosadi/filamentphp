<?php

namespace App\Filament\Resources\Tags\Tables;

use App\Models\Tag;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('contents'))
            ->columns([
                TextColumn::make('name')
                    ->getStateUsing(fn (Tag $record): string => $record->getTranslation('name', 'id', false))
                    ->searchable(query: fn ($query, string $search) => $query->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(name, '$.id')) LIKE ?", ["%{$search}%"]
                    ))
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('contents_count')
                    ->label('Articles')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
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
}
