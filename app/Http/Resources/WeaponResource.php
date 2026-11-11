<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaponResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'type' => $this->type,
            'element_weapons' => $this->element_weapons,
            'start' => $this->start,
            'image2' => Media::url($this->image2),
            'created_at' => $this->created_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
