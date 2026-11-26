<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogTranslations;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCharacter extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['person_name', 'title'];

    protected $fillable = [
        'fanadata_id',
        'person_key',
        'person_name',
        'title',
        'type',
        'image',
        'image_large',
    ];

    protected function casts(): array
    {
        return [
            'fanadata_id' => 'integer',
            'type' => 'integer',
        ];
    }

    public function thumbnailUrl(): ?string
    {
        if (filled($this->image)) {
            return Media::url($this->image);
        }

        if (! $this->fanadata_id) {
            return null;
        }

        return 'https://saoub.fanadata.com/assets/home/characters/thumbnail/home_character_'.(int) $this->fanadata_id.'.png';
    }

    public function imageUrl(): ?string
    {
        if (filled($this->image_large)) {
            return Media::url($this->image_large);
        }

        if (! $this->fanadata_id) {
            return null;
        }

        return 'https://saoub.fanadata.com/assets/home/characters/home_character_'.(int) $this->fanadata_id.'.png';
    }
}
