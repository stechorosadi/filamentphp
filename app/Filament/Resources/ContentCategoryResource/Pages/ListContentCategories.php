<?php

namespace App\Filament\Resources\ContentCategoryResource\Pages;

use App\Filament\Resources\ContentCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentCategories extends ListRecords
{
    protected static string $resource = ContentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
