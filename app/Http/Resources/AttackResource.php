<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttackResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'description' => $this->text('description'),
            'mp_cost' => $this->mp_cost,
            'type_atk' => $this->type_atk instanceof \BackedEnum ? $this->type_atk->value : $this->type_atk,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
