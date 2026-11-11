<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Models\Concerns\HasCatalogTranslations;
use App\Models\Concerns\HasStatStages;
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
            'fanadata_id' => 'integer',
        ];
    }

    public function attack(): HasOne
    {
        return $this->hasOne(Attack::class);
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
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
