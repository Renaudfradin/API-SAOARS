<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'image' => $this->artworkUrl($this->requestedLocale()),
            'created_at' => $this->created_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
