<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\UserEducation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EducationHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'educationHistory';

    protected static ?string $title = 'Education History';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                TextInput::make('institution')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('degree')
                    ->label('Degree / Qualification')
                    ->maxLength(255),

                TextInput::make('field_of_study')
                    ->label('Field of Study')
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

                TextInput::make('gpa')
                    ->label('GPA / Grade')
                    ->maxLength(20),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                FileUpload::make('certificate_path')
                    ->label('Certificate')
                    ->disk('public')
                    ->directory('user-certificates')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->maxSize(2048)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                TextColumn::make('institution')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('degree')
                    ->label('Degree')
                    ->placeholder('—'),

                TextColumn::make('field_of_study')
                    ->label('Field')
                    ->placeholder('—'),

                TextColumn::make('start_year')
                    ->label('Period')
                    ->formatStateUsing(fn (UserEducation $record): string =>
                        $record->start_year . ' – ' . ($record->end_year ?? 'Present')
                    ),

                TextColumn::make('gpa')
                    ->label('GPA / Grade')
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
