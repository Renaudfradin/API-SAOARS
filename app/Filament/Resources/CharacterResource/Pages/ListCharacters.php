<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Imports\CharacterImporter;
use App\Filament\Resources\CharacterResource;
use App\Jobs\ImportCsv;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCharacters extends ListRecords
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CharacterImporter::class)
                ->job(ImportCsv::class),

            CreateAction::make(),
        ];
    }
}
