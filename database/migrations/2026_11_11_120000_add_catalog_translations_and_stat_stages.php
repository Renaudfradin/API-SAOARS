<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, list<string>>
     */
    private array $translatable = [
        'characters' => ['name', 'description', 'profile', 'ultime', 'ultime_description', 'enhance'],
        'weapons' => ['name', 'effect_1', 'effect_2', 'effect_3'],
        'equipment' => ['name', 'effect_1', 'effect_2'],
        'attacks' => ['name', 'description'],
        'abilities' => ['name', 'descripton'],
        'banners' => ['name'],
        'imaginations' => ['name', 'description'],
    ];

    public function up(): void
    {
        if (Schema::hasColumn('banners', 'characters') && ! Schema::hasColumn('banners', 'character_ids')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->renameColumn('characters', 'character_ids');
            });
        }

        foreach ($this->translatable as $table => $columns) {
            foreach ($columns as $column) {
                $this->convertToTranslations($table, $column);
            }
        }

        foreach (['characters', 'weapons', 'equipment', 'attacks', 'abilities', 'banners'] as $table) {
            if (! Schema::hasColumn($table, 'fanadata_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('fanadata_id')->nullable()->unique();
                });
            }
        }

        if (! Schema::hasTable('stat_stages')) {
            Schema::create('stat_stages', function (Blueprint $table) {
                $table->id();
                $table->morphs('statable');
                $table->unsignedTinyInteger('stage');
                $table->integer('hp')->default(0);
                $table->integer('mp')->default(0);
                $table->integer('atk')->default(0);
                $table->integer('matk')->default(0);
                $table->integer('def')->default(0);
                $table->integer('mdef')->default(0);
                $table->integer('crit')->default(0);
                $table->integer('spd')->default(0);
                $table->timestamps();

                $table->unique(['statable_type', 'statable_id', 'stage']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stat_stages');

        foreach (['characters', 'weapons', 'equipment', 'attacks', 'abilities', 'banners'] as $table) {
            if (Schema::hasColumn($table, 'fanadata_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->dropUnique("{$table}_fanadata_id_unique");
                    $blueprint->dropColumn('fanadata_id');
                });
            }
        }

        if (Schema::hasColumn('banners', 'character_ids') && ! Schema::hasColumn('banners', 'characters')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->renameColumn('character_ids', 'characters');
            });
        }
    }

    private function convertToTranslations(string $table, string $column): void
    {
        if (! Schema::hasColumn($table, $column)) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement(<<<SQL
                ALTER TABLE {$table}
                ALTER COLUMN {$column} TYPE jsonb
                USING CASE
                    WHEN {$column} IS NULL THEN NULL
                    WHEN left(btrim({$column}::text), 1) = '{' THEN {$column}::jsonb
                    ELSE jsonb_build_object('fr', {$column})
                END
            SQL);

            return;
        }

        DB::table($table)->orderBy('id')->lazy()->each(function (object $row) use ($table, $column): void {
            $value = $row->{$column} ?? null;

            if (! is_string($value) || $value === '' || str_starts_with(ltrim($value), '{')) {
                return;
            }

            DB::table($table)->where('id', $row->id)->update([
                $column => json_encode(['fr' => $value], JSON_UNESCAPED_UNICODE),
            ]);
        });
    }
};
