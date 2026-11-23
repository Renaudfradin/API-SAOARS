<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\HomeBackground;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeBackgroundResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fanadata_id' => $this->fanadata_id,
            'name' => $this->text('name'),
            'world' => $this->world,
            'world_name' => HomeBackground::WORLDS[$this->world] ?? null,
            'rarity' => $this->rarity,
            'image' => $this->thumbnailUrl(),
            'image_large' => $this->imageUrl(),
            ...$this->translationMeta(),
        ];
    }
}
