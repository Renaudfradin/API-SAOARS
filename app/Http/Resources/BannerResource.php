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
            'image' => $image = $this->artworkUrl($this->requestedLocale()),
            'image_fallback' => ($fallback = $this->artworkFallbackUrl()) !== $image ? $fallback : null,
            'created_at' => $this->created_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }
}
