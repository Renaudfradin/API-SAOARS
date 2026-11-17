<?php

namespace App\Console\Commands;

use App\Services\Translation\EmptyCatalogTranslator;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

class TranslateEmptyCatalogCommand extends Command
{
    protected $signature = 'catalog:translate-empty {--model= : Limite la traduction à un modèle : Attack, Ability, Banner, Character, Equipment, Imagination ou Weapon}';

    protected $description = 'Traduit avec Cursor les champs traduisibles encore vides, sans remplacer les textes déjà enregistrés';

    public function handle(EmptyCatalogTranslator $translator): int
    {
        $only = $this->option('model');

        try {
            $models = $translator->models(is_string($only) ? $only : null);
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $updated = 0;
        $fields = 0;
        $skipped = 0;
        $unchanged = 0;
        $failed = 0;

        $this->line('Traduction des champs vides avec Cursor.');

        foreach ($models as $class) {
            foreach ($translator->records($class) as $record) {
                $french = $translator->pendingFrench($record);
                $title = $translator->label($record);

                if ($french === []) {
                    $skipped++;

                    continue;
                }

                $this->line($title.' : envoi à Cursor ('.implode(', ', array_keys($french)).').');

                try {
                    $written = $translator->fill($record, $french);
                } catch (Throwable $exception) {
                    report($exception);
                    $failed++;
                    $this->error($title.' : '.$exception->getMessage());

                    continue;
                }

                if ($written === 0) {
                    $unchanged++;
                    $this->warn($title.' : aucun champ vide n’a été rempli.');

                    continue;
                }

                $updated++;
                $fields += $written;
                $this->line($title.' : '.$written.' champ'.($written > 1 ? 's' : '').' rempli'.($written > 1 ? 's' : '').'.');
            }
        }

        $this->newLine();
        $this->line('Fiches mises à jour : '.$updated);
        $this->line('Champs remplis : '.$fields);
        $this->line('Fiches déjà complètes : '.$skipped);
        $this->line('Fiches sans nouveau texte : '.$unchanged);
        $this->line('Échecs : '.$failed);
        $this->info('Les traductions sont terminées.');

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
