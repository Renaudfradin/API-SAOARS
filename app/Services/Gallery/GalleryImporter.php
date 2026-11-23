<?php

namespace App\Services\Gallery;

use App\Models\Character;
use App\Models\HomeBackground;
use App\Models\HomeCharacter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GalleryImporter
{
    /**
     * @return array{characters: int, home_characters: int, home_backgrounds: int, missing: int, skipped: int, failed: int}
     */
    public function run(string $type = 'all', bool $force = false, ?callable $report = null): array
    {
        $counts = [
            'characters' => 0,
            'home_characters' => 0,
            'home_backgrounds' => 0,
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
