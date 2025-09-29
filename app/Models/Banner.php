<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'from',
        'to',
        'img',
        'character_ids',
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'character_ids' => 'array',
    ];

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'banner_id');
    }
}
