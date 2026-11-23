<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PLACEHOLDER_IMAGE = 'character/01K1DGKE5PS9B0Q3556JBBPKDB.png';

    private const PLACEHOLDER_IMAGE2 = 'character/01K1DGKEP6YXJ0YPMEX0GFCZ0B.png';

    public function up(): void
    {
        DB::table('characters')
            ->whereNotNull('artwork')
            ->where(function ($query): void {
                $query->where('image', self::PLACEHOLDER_IMAGE)
                    ->orWhere('image', '')
                    ->orWhereNull('image');
            })
            ->update(['image' => DB::raw('artwork')]);

        DB::table('characters')
            ->whereNotNull('artwork_limit_break')
            ->where(function ($query): void {
                $query->where('image2', self::PLACEHOLDER_IMAGE2)
                    ->orWhere('image2', '')
                    ->orWhereNull('image2');
            })
            ->update(['image2' => DB::raw('artwork_limit_break')]);

        DB::table('characters')
            ->whereNull('artwork_limit_break')
            ->where('image2', self::PLACEHOLDER_IMAGE2)
            ->update(['image2' => '']);

        DB::table('characters')
            ->whereNull('artwork')
            ->where('image', self::PLACEHOLDER_IMAGE)
            ->update(['image' => '']);
    }

    public function down(): void
    {
        DB::table('characters')
            ->whereColumn('image', 'artwork')
            ->update(['image' => self::PLACEHOLDER_IMAGE]);

        DB::table('characters')
            ->whereColumn('image2', 'artwork_limit_break')
            ->update(['image2' => self::PLACEHOLDER_IMAGE2]);
    }
};
