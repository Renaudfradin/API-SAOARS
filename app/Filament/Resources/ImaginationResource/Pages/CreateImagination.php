<?php

namespace App\Filament\Resources\ImaginationResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\ImaginationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateImagination extends CreateRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = ImaginationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
        ];
    }
}
