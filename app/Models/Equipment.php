<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\EquipmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'type_equipment',
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
        'start',
    ];

    protected $casts = [
        'type' => EquipmentType::class,
        'type_equipment' => Element::class,
    ];
}
