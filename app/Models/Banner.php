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
        'image_locales',
        'character_ids',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'from' => 'datetime',
            'to' => 'datetime',
            'image_locales' => 'array',
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
        $available = $this->artworkSuffixes();

        if ($available !== [] && $this->fanadata_id) {
            $suffix = Locales::FANADATA_SUFFIX[$locale ?? 'en'] ?? 'en';
            $chosen = in_array($suffix, $available, true) ? $suffix : $this->preferredSuffix($available);

            return Media::url($this->localizedArtworkPath($chosen));
        }

        if (! $this->usesSharedArtwork() || ! $this->fanadata_id) {
            return Media::url($this->img);
        }

        $suffix = Locales::FANADATA_SUFFIX[$locale ?? 'en'] ?? 'en';

        return 'https://saoub.fanadata.com/assets/banners/'.$suffix.'/scout_'.(int) $this->fanadata_id.'.jpg';
    }

    public function artworkFallbackUrl(): ?string
    {
        if (! $this->fanadata_id) {
            return null;
        }

        $available = $this->artworkSuffixes();

        if ($available !== []) {
            return in_array('en', $available, true)
                ? Media::url($this->localizedArtworkPath('en'))
                : null;
        }

        if (! $this->usesSharedArtwork()) {
            return null;
        }

        return 'https://saoub.fanadata.com/assets/banners/en/scout_'.(int) $this->fanadata_id.'.jpg';
    }

    public function localizedArtworkPath(string $suffix): string
    {
        return 'banner/'.$suffix.'/scout_'.(int) $this->fanadata_id.'.jpg';
    }

    /**
     * Locales whose file is actually on disk. A path stored in img counts
     * even before image_locales has been backfilled.
     *
     * @return list<string>
     */
    private function artworkSuffixes(): array
    {
        $stored = $this->image_locales;

        if (is_array($stored) && $stored !== []) {
            return array_values(array_unique(array_map(strval(...), $stored)));
        }

        if (preg_match('#^banner/([^/]+)/scout_\d+\.jpg$#', (string) $this->img, $matches) === 1) {
            return [$matches[1]];
        }

        return [];
    }

    /**
     * @param  list<string>  $available
     */
    private function preferredSuffix(array $available): string
    {
        foreach (['fr', 'en'] as $suffix) {
            if (in_array($suffix, $available, true)) {
                return $suffix;
            }
        }

        return $available[0];
    }

    private function usesSharedArtwork(): bool
    {
        $img = (string) $this->img;

        return $img === '' || str_contains($img, '01K1D9Y9RGT9R6R5NSS9J17C3C');
    }
}
