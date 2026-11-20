<?php

use App\Models\Character;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('banners')->select('id', 'character_ids')->orderBy('id')->get() as $banner) {
            $ids = Character::bannerMemberIds($banner->character_ids);

            if ($ids === []) {
                continue;
            }

            DB::table('characters')
                ->whereIn('id', $ids)
                ->update(['banner_id' => $banner->id]);
        }
    }

    public function down(): void
    {
        foreach (DB::table('banners')->select('id', 'character_ids')->orderBy('id')->get() as $banner) {
            $ids = Character::bannerMemberIds($banner->character_ids);

            if ($ids === []) {
                continue;
            }

            DB::table('characters')
                ->where('banner_id', $banner->id)
                ->whereIn('id', $ids)
                ->update(['banner_id' => null]);
        }
    }
};
