<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\WeaponType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Weapon extends Model
{
    use HasFactory;

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
        'characters_id',
        'image',
        'image2',
        'start',
    ];

    protected $casts = [
        'type' => WeaponType::class,
        'element_weapons' => Element::class,
        'characters_id' => 'integer',
    ];

    public function character(): HasOne
    {
        return $this->hasOne(Character::class);
    }
}
