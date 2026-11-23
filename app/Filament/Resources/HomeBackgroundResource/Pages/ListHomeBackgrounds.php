<?php

namespace App\Filament\Resources\HomeBackgroundResource\Pages;

use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\HomeBackgroundResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeBackgrounds extends ListRecords
{
    use SelectsCatalogLocale;

    protected static string $resource = HomeBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
