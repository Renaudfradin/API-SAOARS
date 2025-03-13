<?php

namespace App\Filament\Resources\AttackResource\Pages;

use App\Filament\Imports\AttackImporter;
use App\Filament\Resources\AttackResource;
use App\Jobs\ImportCsv;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListAttacks extends ListRecords
{
    protected static string $resource = AttackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(AttackImporter::class)
                ->job(ImportCsv::class),

            Actions\CreateAction::make(),
        ];
    }
}
