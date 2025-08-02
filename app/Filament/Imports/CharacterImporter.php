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
                ->label('Nom')
                ->requiredMapping(),

            ImportColumn::make('slug')
                ->label('Slug')
                ->requiredMapping(),

            ImportColumn::make('description')
                ->label('Description')
                ->requiredMapping(),

            ImportColumn::make('profile')
                ->label('Profil')
                ->requiredMapping(),

            ImportColumn::make('element')
                ->label('Élément')
                ->requiredMapping(),

            ImportColumn::make('weapon_type')
                ->label('Type d\'arme')
                ->requiredMapping(),

            ImportColumn::make('atk1')
                ->label('Attaque 1')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('atk2')
                ->label('Attaque 2')
                ->numeric(),

            ImportColumn::make('atk3')
                ->label('Attaque 3')
                ->numeric(),

            ImportColumn::make('hp')
                ->label('Points de vie')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('mp')
                ->label('Points de magie')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('atk')
                ->label('Attaque')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('matk')
                ->label('Attaque magique')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('def')
                ->label('Défense')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('mdef')
                ->label('Défense magique')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('crit')
                ->label('Critique')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('spd')
                ->label('Vitesse')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('ultime')
                ->label('Ultime')
                ->requiredMapping(),

            ImportColumn::make('ultime_description')
                ->label('Description ultime')
                ->requiredMapping(),

            ImportColumn::make('enhance')
                ->label('Amélioration'),

            ImportColumn::make('enhance_atk')
                ->label('Amélioration attaque')
                ->numeric(),

            ImportColumn::make('enhance_atk2')
                ->label('Amélioration attaque 2')
                ->numeric(),

            ImportColumn::make('start')
                ->label('Étoiles')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('cost')
                ->label('Coût')
                ->requiredMapping()
                ->numeric(),

            ImportColumn::make('special_partner')
                ->label('Partenaire spécial')
                ->requiredMapping(),

            ImportColumn::make('image')
                ->label('Image')
                ->requiredMapping(),

            ImportColumn::make('image2')
                ->label('Image 2')
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): ?Character
    {
        return new Character;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'L\'import des personnages est terminé et '.number_format($import->successful_rows).' '.str('ligne')->plural($import->successful_rows).' importée(s).';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('ligne')->plural($failedRowsCount).' n\'ont pas pu être importée(s).';
        }

        return $body;
    }
}
