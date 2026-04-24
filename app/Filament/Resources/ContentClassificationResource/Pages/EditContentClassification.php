<?php

namespace App\Filament\Resources\ContentClassificationResource\Pages;

use App\Filament\Resources\ContentClassificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentClassification extends EditRecord
{
    protected static string $resource = ContentClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
