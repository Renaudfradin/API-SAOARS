<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbilityResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'descripton' => $this->text('descripton'),
            'description' => $this->text('descripton'),
            'type' => $this->type,
            'start' => $this->start,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
