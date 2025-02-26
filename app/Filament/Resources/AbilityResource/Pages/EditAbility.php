<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Resources\AbilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbility extends EditRecord
{
    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
