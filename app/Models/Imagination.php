<?php

namespace App\Models;

use App\Enums\Element;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'element',
    ];

    protected $casts = [
        'element' => Element::class,
    ];
}
