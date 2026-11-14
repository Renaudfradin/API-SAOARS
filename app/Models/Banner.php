<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogTranslations;
use App\Support\Locales;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'from',
        'to',
        'img',
        'character_ids',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'from' => 'datetime',
            'to' => 'datetime',
            'character_ids' => 'array',
            'fanadata_id' => 'integer',
        ];
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'banner_id');
    }

    public function artworkUrl(?string $locale = null): ?string
    {
        if (! $this->usesSharedArtwork()) {
            return Media::url($this->img);
        }

        if (! $this->fanadata_id) {
            return Media::url($this->img);
        }

        $suffix = Locales::FANADATA_SUFFIX[$locale ?? 'en'] ?? 'en';

        return 'https://saoub.fanadata.com/assets/banners/'.$suffix.'/scout_'.(int) $this->fanadata_id.'.jpg';
    }

    public function artworkFallbackUrl(): ?string
    {
        if (! $this->fanadata_id || ! $this->usesSharedArtwork()) {
            return null;
        }

        return 'https://saoub.fanadata.com/assets/banners/en/scout_'.(int) $this->fanadata_id.'.jpg';
    }

    private function usesSharedArtwork(): bool
    {
        $img = (string) $this->img;

        return $img === '' || str_contains($img, '01K1D9Y9RGT9R6R5NSS9J17C3C');
    }
}
