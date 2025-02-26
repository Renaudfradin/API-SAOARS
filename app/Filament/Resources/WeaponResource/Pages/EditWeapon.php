<?php

namespace App\Filament\Resources\WeaponResource\Pages;

use App\Filament\Resources\WeaponResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWeapon extends EditRecord
{
    protected static string $resource = WeaponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
