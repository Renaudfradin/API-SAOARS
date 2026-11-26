<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCharacterResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fanadata_id' => $this->fanadata_id,
            'person' => $this->text('person_name'),
            'person_key' => $this->person_key,
            'name' => $this->text('title'),
            'type' => $this->type,
            'image' => $this->thumbnailUrl(),
            'image_large' => $this->imageUrl(),
            ...$this->translationMeta(),
        ];
    }
}
