<?php

namespace App\Console\Commands;

use App\Services\Fanadata\FanadataSync;
use Illuminate\Console\Command;

class FanadataSyncCommand extends Command
{
    protected $signature = 'fanadata:sync {--type=all : weapons, equipments, characters, banners, or all} {--id= : FanaData id to sync for the chosen type}';

    protected $description = 'Fusionne les fiches locales avec les langues et les paliers de stats publiés par FanaData';

    public function handle(FanadataSync $sync): int
    {
        $type = (string) $this->option('type');
        $id = $this->option('id');
        $id = is_numeric($id) ? (int) $id : null;

        $counts = $sync->run($type, $id);

        foreach ($counts as $name => $count) {
            $this->line($name.': '.$count);
        }

        return self::SUCCESS;
    }
}
