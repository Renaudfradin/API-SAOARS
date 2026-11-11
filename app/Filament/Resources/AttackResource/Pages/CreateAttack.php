<?php

namespace App\Filament\Resources\AttackResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\AttackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttack extends CreateRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = AttackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
        ];
    }
}
