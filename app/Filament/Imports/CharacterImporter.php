<?php

namespace App\Filament\Imports;

use App\Models\Character;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CharacterImporter extends Importer
{
    protected static ?string $model = Character::class;

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
            ImportColumn::make('profile')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('element')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('atk1')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('atk2')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('atk3')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('hp')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('mp')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('atk')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('matk')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('def')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('mdef')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('crit')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('spd')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('ultime')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('ultime_description')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('enhance')
                ->rules(['max:255']),
            ImportColumn::make('enhance_atk')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('enhance_atk2')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('start')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('cost')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('special_partner')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('image')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Character
    {
        // return Character::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Character;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your character import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
