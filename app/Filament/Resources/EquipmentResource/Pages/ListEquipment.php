<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Imports\EquipmentImporter;
use App\Filament\Resources\EquipmentResource;
use App\Jobs\ImportCsv;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListEquipment extends ListRecords
{
    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(EquipmentImporter::class)
                ->job(ImportCsv::class),

            Actions\CreateAction::make(),
        ];
    }
}
