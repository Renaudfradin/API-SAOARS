<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'type' => $this->type instanceof \BackedEnum ? $this->type->value : $this->type,
            'type_equipment' => $this->type_equipment instanceof \BackedEnum ? $this->type_equipment->value : $this->type_equipment,
            'start' => $this->start,
            'image' => $this->portraitUrl(),
            'image2' => $this->thumbnailUrl(),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
