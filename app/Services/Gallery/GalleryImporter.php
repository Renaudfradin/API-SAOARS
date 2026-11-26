<?php

namespace App\Services\Gallery;

use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\HomeBackground;
use App\Models\HomeCharacter;
use App\Models\Weapon;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GalleryImporter
{
    /**
     * @return array{characters: int, home_characters: int, home_backgrounds: int, banners: int, equipments: int, weapons: int, missing: int, skipped: int, failed: int}
     */
    public function run(string $type = 'all', bool $force = false, ?callable $report = null): array
    {
        $counts = [
            'characters' => 0,
            'home_characters' => 0,
            'home_backgrounds' => 0,
            'banners' => 0,
            'equipments' => 0,
            'weapons' => 0,
            'missing' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        if ($this->wants($type, 'characters')) {
            Character::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($characters) use ($force, $report, &$counts): void {
                    foreach ($characters as $character) {
                        $this->importRecord($character, $this->characterAssets($character), 'characters', $force, $report, $counts);
                    }
                });
        }

        if ($this->wants($type, 'home-characters')) {
            HomeCharacter::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($records) use ($force, $report, &$counts): void {
                    foreach ($records as $record) {
                        $this->importRecord($record, $this->homeCharacterAssets($record), 'home_characters', $force, $report, $counts);
                    }
                });
        }

        if ($this->wants($type, 'home-backgrounds')) {
            HomeBackground::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($records) use ($force, $report, &$counts): void {
                    foreach ($records as $record) {
                        $this->importRecord($record, $this->homeBackgroundAssets($record), 'home_backgrounds', $force, $report, $counts);
                    }
                });
        }

        if ($this->wants($type, 'banners')) {
            Banner::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($records) use ($force, $report, &$counts): void {
                    foreach ($records as $record) {
                        $this->importBanner($record, $force, $report, $counts);
                    }
                });
        }

        if ($this->wants($type, 'equipments')) {
            Equipment::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($records) use ($force, $report, &$counts): void {
                    foreach ($records as $record) {
                        $this->importEquipment($record, $force, $report, $counts);
                    }
                });
        }

        if ($this->wants($type, 'weapons')) {
            Weapon::query()
                ->whereNotNull('fanadata_id')
                ->orderBy('id')
                ->chunkById(50, function ($records) use ($force, $report, &$counts): void {
                    foreach ($records as $record) {
                        $this->importWeapon($record, $force, $report, $counts);
                    }
                });
        }

        return $counts;
    }

    private function wants(string $type, string $name): bool
    {
        return $type === 'all' || $type === $name;
    }

    /**
     * @param  list<array{column: string, source: string, key: string}>  $assets
     * @param  array{characters: int, home_characters: int, home_backgrounds: int, missing: int, skipped: int, failed: int}  $counts
     */
    private function importRecord(Model $record, array $assets, string $bucket, bool $force, ?callable $report, array &$counts): void
    {
        foreach ($assets as $asset) {
            $status = $this->store($record, $asset['column'], $asset['source'], $asset['key'], $force);
            $counts[$status === 'stored' ? $bucket : $status]++;

            if ($report && $status !== 'skipped') {
                $report($status.' '.$asset['key']);
            }
        }
    }

    private function store(Model $record, string $column, string $source, string $key, bool $force): string
    {
        $current = $record->getAttribute($column);

        if (! $force && filled($current) && Storage::disk('scaleway')->exists((string) $current)) {
            return 'skipped';
        }

        try {
            $response = $this->download($source);
        } catch (Throwable) {
            return 'failed';
        }

        if ($response->status() === 404) {
            return 'missing';
        }

        $contentType = (string) $response->header('Content-Type');

        if (! $response->successful() || $response->body() === '' || ($contentType !== '' && ! str_starts_with($contentType, 'image/'))) {
            return 'failed';
        }

        Storage::disk('scaleway')->put($key, $response->body(), 'public');
        $record->setAttribute($column, $key);
        $record->save();

        return 'stored';
    }

    private function download(string $source): Response
    {
        try {
            return Http::withHeaders([
                'Accept' => 'image/*,*/*',
                'Referer' => 'https://saoub.fanadata.com/',
                'User-Agent' => 'API-SAOARS gallery import',
            ])->timeout(60)->retry(2, 500, throw: false)->get($source);
        } catch (ConnectionException $exception) {
            throw $exception;
        }
    }

    /**
     * @return list<array{column: string, source: string, key: string}>
     */
    private function characterAssets(Character $character): array
    {
        $id = (int) $character->fanadata_id;
        $assets = [
            $this->asset(
                'artwork',
                'https://saoub.fanadata.com/assets/characters/artwork/character_'.$id.'.png',
                'galleries/characters/artwork/character_'.$id.'.png',
            ),
            $this->asset(
                'artwork_thumbnail',
                'https://saoub.fanadata.com/assets/characters/artwork/thumbnail/character_'.$id.'.png',
                'galleries/characters/artwork/thumbnail/character_'.$id.'.png',
            ),
            $this->asset(
                'artwork_large',
                'https://saoub.fanadata.com/assets/characters/artwork/large/character_'.$id.'.png',
                'galleries/characters/artwork/large/character_'.$id.'.png',
            ),
        ];

        if ((int) $character->start === 4) {
            $assets[] = $this->asset(
                'artwork_limit_break',
                'https://saoub.fanadata.com/assets/characters/artwork/large/character_'.$id.'_4.png',
                'galleries/characters/artwork/large/character_'.$id.'_4.png',
            );
            $assets[] = $this->asset(
                'artwork_limit_break_thumbnail',
                'https://saoub.fanadata.com/assets/characters/artwork/thumbnail/character_'.$id.'_4.png',
                'galleries/characters/artwork/thumbnail/character_'.$id.'_4.png',
            );
        }

        return $assets;
    }

    /**
     * @return list<array{column: string, source: string, key: string}>
     */
    private function homeCharacterAssets(HomeCharacter $record): array
    {
        $id = (int) $record->fanadata_id;

        return [
            $this->asset(
                'image',
                'https://saoub.fanadata.com/assets/home/characters/thumbnail/home_character_'.$id.'.png',
                'galleries/home/characters/thumbnail/home_character_'.$id.'.png',
            ),
            $this->asset(
                'image_large',
                'https://saoub.fanadata.com/assets/home/characters/home_character_'.$id.'.png',
                'galleries/home/characters/home_character_'.$id.'.png',
            ),
        ];
    }

    /**
     * @return list<array{column: string, source: string, key: string}>
     */
    private function homeBackgroundAssets(HomeBackground $record): array
    {
        $id = (int) $record->fanadata_id;

        return [
            $this->asset(
                'image',
                'https://saoub.fanadata.com/assets/home/backgrounds/thumbnail/home_background_'.$id.'.jpg',
                'galleries/home/backgrounds/thumbnail/home_background_'.$id.'.jpg',
            ),
            $this->asset(
                'image_large',
                'https://saoub.fanadata.com/assets/home/backgrounds/home_background_'.$id.'.jpg',
                'galleries/home/backgrounds/home_background_'.$id.'.jpg',
            ),
        ];
    }

    /**
     * @param  array{characters: int, home_characters: int, home_backgrounds: int, banners: int, equipments: int, weapons: int, missing: int, skipped: int, failed: int}  $counts
     */
    private function importBanner(Banner $banner, bool $force, ?callable $report, array &$counts): void
    {
        if (! $this->canReplace($banner->img)) {
            return;
        }

        $id = (int) $banner->fanadata_id;
        $stored = [];

        foreach (array_unique(Locales::FANADATA_SUFFIX) as $suffix) {
            $key = 'banner/'.$suffix.'/scout_'.$id.'.jpg';
            $status = $this->putFile(
                'https://saoub.fanadata.com/assets/banners/'.$suffix.'/scout_'.$id.'.jpg',
                $key,
                $force,
            );
            $this->count($counts, 'banners', $status, $key, $report);

            if (in_array($status, ['stored', 'skipped'], true)) {
                $stored[$suffix] = $key;
            }
        }

        $img = $stored['fr'] ?? $stored['en'] ?? null;

        if ($img !== null && $banner->img !== $img) {
            $banner->img = $img;
            $banner->save();
        }
    }

    /**
     * @param  array{characters: int, home_characters: int, home_backgrounds: int, banners: int, equipments: int, weapons: int, missing: int, skipped: int, failed: int}  $counts
     */
    private function importEquipment(Equipment $equipment, bool $force, ?callable $report, array &$counts): void
    {
        if (! $this->canReplace($equipment->image) && ! $this->canReplace($equipment->image2)) {
            return;
        }

        $id = (int) $equipment->fanadata_id;
        $portrait = 'equipment/stuff_'.$id.'.jpg';
        $thumbnail = 'equipment/thumbnail/stuff_'.$id.'.png';

        $portraitStatus = $this->putFile(
            'https://saoub.fanadata.com/assets/equipments/stuff_'.$id.'.jpg',
            $portrait,
            $force,
        );
        $this->count($counts, 'equipments', $portraitStatus, $portrait, $report);

        $thumbnailStatus = $this->putFile(
            'https://saoub.fanadata.com/assets/equipments/thumbnail/stuff_'.$id.'.png',
            $thumbnail,
            $force,
        );
        $this->count($counts, 'equipments', $thumbnailStatus, $thumbnail, $report);

        $dirty = false;

        if ($this->canReplace($equipment->image) && in_array($portraitStatus, ['stored', 'skipped'], true) && $equipment->image !== $portrait) {
            $equipment->image = $portrait;
            $dirty = true;
        }

        if ($this->canReplace($equipment->image2) && in_array($thumbnailStatus, ['stored', 'skipped'], true) && $equipment->image2 !== $thumbnail) {
            $equipment->image2 = $thumbnail;
            $dirty = true;
        }

        if ($dirty) {
            $equipment->save();
        }
    }

    /**
     * @param  array{characters: int, home_characters: int, home_backgrounds: int, banners: int, equipments: int, weapons: int, missing: int, skipped: int, failed: int}  $counts
     */
    private function importWeapon(Weapon $weapon, bool $force, ?callable $report, array &$counts): void
    {
        if (! $this->canReplace($weapon->image) && ! $this->canReplace($weapon->image2)) {
            return;
        }

        $id = (int) $weapon->fanadata_id;
        $files = [
            'render' => [
                'source' => 'https://saoub.fanadata.com/assets/weapons/render/weapon_'.$id.'.png',
                'key' => 'weapon/render/weapon_'.$id.'.png',
            ],
            'thumb_weapon' => [
                'source' => 'https://saoub.fanadata.com/assets/weapons/thumbnail/weapon_'.$id.'.png',
                'key' => 'weapon/thumbnail/weapon_'.$id.'.png',
            ],
            'thumb_stuff' => [
                'source' => 'https://saoub.fanadata.com/assets/weapons/thumbnail/stuff_'.$id.'.png',
                'key' => 'weapon/thumbnail/stuff_'.$id.'.png',
            ],
        ];
        $saved = [];

        foreach ($files as $name => $file) {
            $status = $this->putFile($file['source'], $file['key'], $force);
            $this->count($counts, 'weapons', $status, $file['key'], $report);

            if (in_array($status, ['stored', 'skipped'], true)) {
                $saved[$name] = $file['key'];
            }
        }

        $image = $saved['render'] ?? $saved['thumb_weapon'] ?? $saved['thumb_stuff'] ?? null;
        $image2 = null;

        foreach (['thumb_weapon', 'thumb_stuff'] as $name) {
            if (isset($saved[$name]) && $saved[$name] !== $image) {
                $image2 = $saved[$name];
                break;
            }
        }

        $dirty = false;

        if ($image !== null && $this->canReplace($weapon->image) && $weapon->image !== $image) {
            $weapon->image = $image;
            $dirty = true;
        }

        if ($this->canReplace($weapon->image2) && $weapon->image2 !== $image2) {
            $weapon->image2 = $image2;
            $dirty = true;
        }

        if ($dirty) {
            $weapon->save();
        }
    }

    /**
     * @param  array{characters: int, home_characters: int, home_backgrounds: int, banners: int, equipments: int, weapons: int, missing: int, skipped: int, failed: int}  $counts
     */
    private function count(array &$counts, string $bucket, string $status, string $key, ?callable $report): void
    {
        $counts[$status === 'stored' ? $bucket : $status]++;

        if ($report && $status !== 'skipped') {
            $report($status.' '.$key);
        }
    }

    private function putFile(string $source, string $key, bool $force): string
    {
        if (! $force && Storage::disk('scaleway')->exists($key)) {
            return 'skipped';
        }

        try {
            $response = $this->download($source);
        } catch (Throwable) {
            return 'failed';
        }

        if ($response->status() === 404) {
            return 'missing';
        }

        $contentType = (string) $response->header('Content-Type');

        if (! $response->successful() || $response->body() === '' || ($contentType !== '' && ! str_starts_with($contentType, 'image/'))) {
            return 'failed';
        }

        Storage::disk('scaleway')->put($key, $response->body(), 'public');

        return 'stored';
    }

    private function canReplace(?string $path): bool
    {
        if (blank($path)) {
            return true;
        }

        return str_contains($path, '01K1D9Y9RGT9R6R5NSS9J17C3C')
            || str_contains($path, '/scout_')
            || str_contains($path, 'stuff_')
            || str_contains($path, '/render/weapon_')
            || str_contains($path, '/thumbnail/weapon_')
            || str_contains($path, '/thumbnail/stuff_');
    }

    /**
     * @return array{column: string, source: string, key: string}
     */
    private function asset(string $column, string $source, string $key): array
    {
        return [
            'column' => $column,
            'source' => $source,
            'key' => $key,
        ];
    }
}
