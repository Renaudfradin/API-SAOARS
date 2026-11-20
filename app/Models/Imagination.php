<?php

namespace App\Models;

use App\Enums\Element;
use App\Models\Concerns\HasCatalogTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagination extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'element',
        'character',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'element' => Element::class,
            'character' => 'string',
        ];
    }
}
