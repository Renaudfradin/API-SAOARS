<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Imports\AbilityImporter;
use App\Filament\Resources\AbilityResource;
use App\Jobs\ImportCsv;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListAbilities extends ListRecords
{
    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(AbilityImporter::class)
                ->job(ImportCsv::class),

            Actions\CreateAction::make(),
        ];
    }
}
