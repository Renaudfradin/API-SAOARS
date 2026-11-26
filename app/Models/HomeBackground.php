<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogTranslations;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeBackground extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['name'];

    /**
     * @var array<int, string>
     */
    public const WORLDS = [
        1 => 'SAO',
        2 => 'ALO',
        3 => 'GGO',
        4 => 'OS',
        5 => 'UW',
        6 => 'etc.',
    ];

    protected $fillable = [
        'fanadata_id',
        'name',
        'world',
        'rarity',
        'image',
        'image_large',
    ];

    protected function casts(): array
    {
        return [
            'fanadata_id' => 'integer',
            'world' => 'integer',
            'rarity' => 'integer',
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

        return 'https://saoub.fanadata.com/assets/home/backgrounds/thumbnail/home_background_'.(int) $this->fanadata_id.'.jpg';
    }

    public function imageUrl(): ?string
    {
        if (filled($this->image_large)) {
            return Media::url($this->image_large);
        }

        if (! $this->fanadata_id) {
            return null;
        }

        return 'https://saoub.fanadata.com/assets/home/backgrounds/home_background_'.(int) $this->fanadata_id.'.jpg';
    }
}
