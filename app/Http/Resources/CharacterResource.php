<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Attack;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'description' => $this->text('description'),
            'slug' => $this->slug,
            'profile' => $this->text('profile'),
            'element' => $this->element instanceof \BackedEnum ? $this->element->value : $this->element,
            'weapon_type' => $this->weapon_type instanceof \BackedEnum ? $this->weapon_type->value : $this->weapon_type,
            'atk1' => $this->attackType($this->atk1),
            'atk2' => $this->attackType($this->atk2),
            'atk3' => $this->attackType($this->atk3),
            'start' => $this->start,
            'image' => Media::url($this->image),
            'image2' => Media::url($this->image2),
            'artwork' => $this->artworkUrl(),
            'artwork_thumbnail' => $this->artworkUrl('thumbnail'),
            'artwork_large' => $this->artworkUrl('large'),
            'artwork_limit_break' => (int) $this->start === 4 ? $this->artworkUrl('large', '_4') : null,
            'artwork_limit_break_thumbnail' => (int) $this->start === 4 ? $this->artworkUrl('thumbnail', '_4') : null,
            'created_at' => $this->created_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }

    private function attackType(mixed $attackId): mixed
    {
        if (! $attackId) {
            return null;
        }

        $type = Attack::find($attackId)?->type_atk;

        return $type instanceof \BackedEnum ? $type->value : $type;
    }
}
