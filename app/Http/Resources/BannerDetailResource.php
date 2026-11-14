<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerDetailResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        $locale = $this->requestedLocale();
        $characters = $this->linkedCharacters();

        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'characters' => $characters->map(fn (Character $character) => [
                'id' => $character->id,
                'name' => $character->translatedAttribute('name', $locale),
                'slug' => $character->slug,
                'image' => $character->faceUrl(),
            ])->all(),
            'from' => $this->from?->format('Y-m-d'),
            'to' => $this->to?->format('Y-m-d'),
            'image' => $this->artworkUrl($locale),
            'image_fallback' => $this->artworkFallbackUrl(),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }

    /**
     * Some imported rows store the JSON array a second time as a string.
     *
     * @return list<int>
     */
    private function characterIds(): array
    {
        $value = $this->character_ids;

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter(array_map(intval(...), $value)));
    }

    /**
     * @return \Illuminate\Support\Collection<int, Character>
     */
    private function linkedCharacters()
    {
        $ids = $this->characterIds();
        $related = Character::query()->where('banner_id', $this->id)->pluck('id')->all();
        $ids = array_values(array_unique([...$ids, ...array_map(intval(...), $related)]));

        if ($ids === []) {
            return collect();
        }

        return Character::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Character $character) => array_search($character->id, $ids, true))
            ->values();
    }
}
