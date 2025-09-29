<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Models\Character;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['character_ids'] = $this->record->character_ids ?? [];
        return $data;
    }

    protected function afterSave(): void
    {
        $characterIds = $this->data['character_ids'] ?? [];
        
        DB::transaction(function () use ($characterIds) {
            Character::where('banner_id', $this->record->id)->update(['banner_id' => null]);
            if ($characterIds) Character::whereIn('id', $characterIds)->update(['banner_id' => $this->record->id]);
            $this->record->update(['character_ids' => $characterIds]);
        });
    }
}
