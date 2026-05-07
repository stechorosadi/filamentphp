<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use App\Enums\TeamMemberStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->fullName() ?: '?').'&background=4F772D&color=ECF39E')
                    ->action(
                        Action::make('previewPhoto')
                            ->label('Preview Photo')
                            ->modalHeading(fn ($record): string => $record->fullName())
                            ->modalContent(fn ($record): HtmlString => new HtmlString(
                                '<div style="display:flex;justify-content:center;align-items:center;width:100%;padding:1rem;">'.
                                '<img src="'.(filled($record->photo)
                                    ? Storage::disk('public')->url($record->photo)
                                    : 'https://ui-avatars.com/api/?name='.urlencode($record->fullName() ?: '?').'&background=4F772D&color=ECF39E&size=256'
                                ).'" style="width:200px;height:200px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.15);">'.
                                '</div>'
                            ))
                            ->modalWidth('sm')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Close')
                    ),

                TextColumn::make('full_name')
                    ->label('Name')
                    ->state(fn ($record) => $record->fullName())
                    ->searchable(query: fn ($query, $search) => $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    )
                    ->sortable(query: fn ($query, $direction) => $query
                        ->orderByRaw("COALESCE(team_members.name, '') {$direction}")
                    ),

                TextColumn::make('position')
                    ->getStateUsing(fn ($record): string => $record->getTranslation('position', 'id', false) ?? '')
                    ->searchable(query: fn ($query, string $search) => $query->whereRaw(
                        "JSON_UNQUOTE(JSON_EXTRACT(position, '$.id')) LIKE ?", ["%{$search}%"]
                    ))
                    ->sortable(),

                TextColumn::make('employee_number')
                    ->label('Employee #')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (TeamMemberStatus $state): string => match ($state) {
                        TeamMemberStatus::Active => 'success',
                        TeamMemberStatus::Transferred => 'warning',
                        TeamMemberStatus::Retired => 'gray',
                    })
                    ->formatStateUsing(fn (TeamMemberStatus $state): string => $state->label())
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visible')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        TeamMemberStatus::Active->value => 'Active',
                        TeamMemberStatus::Transferred->value => 'Transferred',
                        TeamMemberStatus::Retired->value => 'Retired',
                    ]),
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
