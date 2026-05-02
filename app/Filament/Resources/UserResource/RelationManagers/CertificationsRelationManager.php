<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'certifications';

    protected static ?string $title = 'Certifications';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('issuing_organization')
                    ->label('Issuing Organization')
                    ->maxLength(255),

                Select::make('category')
                    ->label('Category')
                    ->options([
                        'training' => 'Training',
                        'seminar' => 'Seminar',
                        'workshop' => 'Workshop',
                        'professional_certification' => 'Professional Certification',
                        'online_course' => 'Online Course',
                    ])
                    ->native(false),

                TextInput::make('issue_year')
                    ->label('Issue Year')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(now()->year)
                    ->required(),

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
                    ->maxSize(5120)
                    ->automaticallyResizeImagesToWidth(1024)
                    ->automaticallyResizeImagesMode('contain')
                    ->automaticallyUpscaleImagesWhenResizing(false)
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
                    ->weight('bold'),

                TextColumn::make('issuing_organization')
                    ->label('Issuer')
                    ->placeholder('—'),

                TextColumn::make('category')
                    ->placeholder('—')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'training' => 'Training',
                        'seminar' => 'Seminar',
                        'workshop' => 'Workshop',
                        'professional_certification' => 'Professional Certification',
                        'online_course' => 'Online Course',
                        default => $state ?? '—',
                    }),

                TextColumn::make('issue_year')
                    ->label('Year'),
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
