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
        'from',
        'to',
        'characters',
        'img'
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'characters' => 'array',
    ];

    public function character(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
