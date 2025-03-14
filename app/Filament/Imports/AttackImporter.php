<?php

namespace App\Filament\Imports;

use App\Models\Attack;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AttackImporter extends Importer
{
    protected static ?string $model = Attack::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('mp_cost')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('type_atk')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Attack
    {
        // return Attack::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Attack;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your attack import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
