<?php

namespace App\Models;

use App\Enums\Element;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'profile',
        'element',
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
        'special_partner',
    ];

    protected $casts = [
        'element' => Element::class,
    ];

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
