<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Models\Concerns\HasCatalogTranslations;
use App\Models\Concerns\HasStatStages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Weapon extends Model
{
    use HasCatalogTranslations;
    use HasFactory;
    use HasStatStages;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'effect_1', 'effect_2', 'effect_3'];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'element_weapons',
        'hp',
        'mp',
        'atk',
        'matk',
        'def',
        'mdef',
        'crit',
        'spd',
        'effect_1',
        'effect_2',
        'effect_3',
        'skills',
        'characters_id',
        'image',
        'image2',
        'start',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => WeaponType::class,
            'element_weapons' => Element::class,
            'characters_id' => 'integer',
            'fanadata_id' => 'integer',
            'skills' => 'array',
        ];
    }

    public function character(): HasOne
    {
        return $this->hasOne(Character::class);
    }
}
