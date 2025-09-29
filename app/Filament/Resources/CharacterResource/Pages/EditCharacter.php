<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Resources\CharacterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCharacter extends EditRecord
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
