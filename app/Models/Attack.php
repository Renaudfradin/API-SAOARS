<?php

namespace App\Models;

use App\Enums\AttackType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'mp_cost',
        'type_atk',
    ];

    protected $casts = [
        'type_atk' => AttackType::class,
    ];

    // public function character(): BelongsTo
    // {
    //     return $this->belongsTo(Character::class, 'character_id');
    // }
}
