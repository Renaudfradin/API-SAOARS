<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Resources\AbilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbilities extends ListRecords
{
    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
