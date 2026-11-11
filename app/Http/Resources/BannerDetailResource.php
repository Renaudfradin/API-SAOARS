<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Character;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerDetailResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        $ids = is_array($this->character_ids) ? $this->character_ids : [];

        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'characters' => $ids === [] ? [] : Character::query()->whereIn('id', $ids)->get()->toArray(),
            'from' => $this->from?->format('Y-m-d'),
            'to' => $this->to?->format('Y-m-d'),
            'image' => Media::url($this->img),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
