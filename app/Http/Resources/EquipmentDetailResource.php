<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentDetailResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'type' => $this->type,
            'type_equipment' => $this->type_equipment,
            'hp' => $this->hp,
            'mp' => $this->mp,
            'atk' => $this->atk,
            'matk' => $this->matk,
            'def' => $this->def,
            'mdef' => $this->mdef,
            'crit' => $this->crit,
            'spd' => $this->spd,
            'stats' => $this->statPayload(),
            'effect_1' => $this->text('effect_1'),
            'effect_2' => $this->text('effect_2'),
            'image' => Media::url($this->image),
            'image2' => Media::url($this->image2),
            'start' => $this->start,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
