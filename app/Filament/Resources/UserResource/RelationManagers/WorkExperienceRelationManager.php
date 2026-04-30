<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\UserExperience;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkExperienceRelationManager extends RelationManager
{
    protected static string $relationship = 'workExperience';

    protected static ?string $title = 'Work Experience';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                TextInput::make('company')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('job_title')
                    ->label('Job Title')
                    ->maxLength(255),

                TextInput::make('department')
                    ->label('Department')
                    ->maxLength(255),

                TextInput::make('start_year')
                    ->label('Start Year')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(now()->year)
                    ->required(),

                TextInput::make('end_year')
                    ->label('End Year')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(now()->year + 10)
                    ->placeholder('Present'),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                TextColumn::make('company')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('job_title')
                    ->label('Job Title')
                    ->placeholder('—'),

                TextColumn::make('department')
                    ->label('Department')
                    ->placeholder('—'),

                TextColumn::make('start_year')
                    ->label('Period')
                    ->formatStateUsing(fn (UserExperience $record): string => $record->start_year.' – '.($record->end_year ?? 'Present')
                    ),
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
