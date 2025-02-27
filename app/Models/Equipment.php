<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
}
