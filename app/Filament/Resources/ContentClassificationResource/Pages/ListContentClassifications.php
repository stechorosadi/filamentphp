<?php

namespace App\Filament\Resources\ContentClassificationResource\Pages;

use App\Filament\Resources\ContentClassificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentClassifications extends ListRecords
{
    protected static string $resource = ContentClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
