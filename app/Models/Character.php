<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Models\Concerns\HasCatalogTranslations;
use App\Models\Concerns\HasStatStages;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Character extends Model
{
    use HasCatalogTranslations;
    use HasFactory;
    use HasStatStages;

    /**
     * @var list<string>
     */
    public array $translatable = [
        'name',
        'description',
        'profile',
        'ultime',
        'ultime_description',
        'enhance',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'profile',
        'element',
        'weapon_type',
        'atk1',
        'atk2',
        'atk3',
        'hp',
        'mp',
        'atk',
        'matk',
        'def',
        'mdef',
        'crit',
        'spd',
        'ultime',
        'ultime_description',
        'enhance',
        'enhance_atk',
        'enhance_atk2',
        'start',
        'cost',
        'image',
        'image2',
        'artwork',
        'artwork_thumbnail',
        'artwork_large',
        'artwork_limit_break',
        'artwork_limit_break_thumbnail',
        'special_partner',
        'banner_id',
        'weapon_id',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'element' => Element::class,
            'weapon_type' => WeaponType::class,
            'banner_id' => 'integer',
            'fanadata_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (Character $character): void {
            if (! $character->wasRecentlyCreated && ! $character->wasChanged('banner_id')) {
                return;
            }

            if ($character->wasRecentlyCreated && $character->banner_id === null) {
                return;
            }

            $character->syncBannerMembership();
        });
    }

    public function attack(): HasOne
    {
        return $this->hasOne(Attack::class);
    }

    public function faceUrl(): ?string
    {
        $image = (string) $this->image;

        if ($this->fanadata_id && ($image === '' || str_contains($image, '01K1DGKE5PS9B0Q3556JBBPKDB'))) {
            return 'https://saoub.fanadata.com/assets/characters/thumbnail/150/character_'.(int) $this->fanadata_id.'.png';
        }

        return Media::url($this->image);
    }

    public function artworkUrl(string $size = 'card', string $suffix = ''): ?string
    {
        $stored = $this->getAttribute($this->artworkColumn($size, $suffix));

        if (filled($stored)) {
            return Media::url($stored);
        }

        if (! $this->fanadata_id) {
            return null;
        }

        $folder = match ($size) {
            'thumbnail' => 'thumbnail/',
            'large' => 'large/',
            default => '',
        };

        return 'https://saoub.fanadata.com/assets/characters/artwork/'.$folder.'character_'.(int) $this->fanadata_id.$suffix.'.png';
    }

    public function artworkColumn(string $size = 'card', string $suffix = ''): string
    {
        return match (true) {
            $size === 'thumbnail' && $suffix === '_4' => 'artwork_limit_break_thumbnail',
            $size === 'large' && $suffix === '_4' => 'artwork_limit_break',
            $size === 'thumbnail' => 'artwork_thumbnail',
            $size === 'large' => 'artwork_large',
            default => 'artwork',
        };
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * A character belongs to one banner. Keep banners.character_ids aligned
     * with banner_id when the link is changed from the character side.
     */
    public function syncBannerMembership(): void
    {
        $id = (int) $this->id;
        $bannerId = $this->banner_id ? (int) $this->banner_id : null;

        Banner::query()->orderBy('id')->each(function (Banner $banner) use ($id, $bannerId): void {
            $ids = self::bannerMemberIds($banner->character_ids);
            $contains = in_array($id, $ids, true);
            $shouldContain = $bannerId !== null && $banner->id === $bannerId;

            if ($contains === $shouldContain) {
                return;
            }

            $banner->character_ids = $shouldContain
                ? [...$ids, $id]
                : array_values(array_filter($ids, fn (int $existing): bool => $existing !== $id));
            $banner->saveQuietly();
        });
    }

    /**
     * @return list<int>
     */
    public static function bannerMemberIds(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($value)) {
            return [];
        }

        $ids = [];

        foreach ($value as $memberId) {
            if (! is_numeric($memberId)) {
                continue;
            }

            $ids[] = (int) $memberId;
        }

        return array_values(array_unique($ids));
    }

    public function weapon(): BelongsTo
    {
        return $this->belongsTo(Weapon::class);
    }

    public function specialPartner(): HasOne
    {
        return $this->hasOne(Character::class, 'special_partner');
    }
}
