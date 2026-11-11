<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ImportSaoarsDump extends Command
{
    protected $signature = 'saoars:import {path=/Users/renaud/Downloads/saoars.sql : Chemin du dump Postgres}';

    protected $description = 'Importe le dump Postgres saoars.sql dans la base configurée';

    public function handle(): int
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('saoars.sql est un dump Postgres. DB_CONNECTION doit valoir pgsql.');

            return self::FAILURE;
        }

        $path = (string) $this->argument('path');

        if (! is_file($path)) {
            $this->error('Dump introuvable : '.$path);

            return self::FAILURE;
        }

        $connection = config('database.connections.pgsql');

        $process = new Process([
            'psql',
            '--set',
            'ON_ERROR_STOP=1',
            '--host',
            (string) $connection['host'],
            '--port',
            (string) $connection['port'],
            '--username',
            (string) $connection['username'],
            '--dbname',
            (string) $connection['database'],
            '--file',
            $path,
        ]);
        $process->setTimeout(null);
        $process->setEnv(['PGPASSWORD' => (string) $connection['password']]);

        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error('Import interrompu.');

            return self::FAILURE;
        }

        $repair = new Process([
            'psql',
            '--set',
            'ON_ERROR_STOP=1',
            '--host',
            (string) $connection['host'],
            '--port',
            (string) $connection['port'],
            '--username',
            (string) $connection['username'],
            '--dbname',
            (string) $connection['database'],
            '--command',
            <<<'SQL'
                DO $$
                DECLARE
                    row record;
                    max_id bigint;
                    seq text;
                BEGIN
                    FOR row IN
                        SELECT table_name
                        FROM information_schema.columns
                        WHERE table_schema = 'public'
                          AND column_name = 'id'
                    LOOP
                        seq := pg_get_serial_sequence(row.table_name, 'id');
                        IF seq IS NULL THEN
                            CONTINUE;
                        END IF;
                        EXECUTE format('SELECT MAX(id) FROM %I', row.table_name) INTO max_id;
                        IF max_id IS NULL THEN
                            PERFORM setval(seq, 1, false);
                        ELSE
                            PERFORM setval(seq, max_id, true);
                        END IF;
                    END LOOP;
                END $$;
            SQL,
        ]);
        $repair->setTimeout(null);
        $repair->setEnv(['PGPASSWORD' => (string) $connection['password']]);
        $repair->run();

        if (! $repair->isSuccessful()) {
            $this->error($repair->getErrorOutput());

            return self::FAILURE;
        }

        $this->info('Dump importé. Lance les migrations pour les traductions et les paliers.');

        return self::SUCCESS;
    }
}
