<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Models\Character;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function afterCreate(): void
    {
        $characterIds = $this->data['character_ids'] ?? [];
        if ($characterIds) {
            Character::whereIn('id', $characterIds)->update(['banner_id' => $this->record->id]);
            $this->record->update(['character_ids' => $characterIds]);
        }
    }
}
