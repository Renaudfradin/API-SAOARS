<?php

namespace App\Console\Commands;

use App\Services\Gallery\GalleryImporter;
use Illuminate\Console\Command;

class GalleriesImportCommand extends Command
{
    protected $signature = 'galleries:import {--type=all : characters, home-characters, home-backgrounds, or all} {--force : Re-upload files that are already stored}';

    protected $description = 'Télécharge les images de galerie FanaData et les dépose sur Scaleway dans galleries/';

    public function handle(GalleryImporter $importer): int
    {
        $type = (string) $this->option('type');
        $allowed = ['all', 'characters', 'home-characters', 'home-backgrounds'];

        if (! in_array($type, $allowed, true)) {
            $this->error('Type inconnu. Valeurs : '.implode(', ', $allowed));

            return self::FAILURE;
        }

        $counts = $importer->run($type, (bool) $this->option('force'), function (string $message): void {
            $this->line($message);
        });

        foreach ($counts as $name => $count) {
            $this->line($name.': '.$count);
        }

        return self::SUCCESS;
    }
}
