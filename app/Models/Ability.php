<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'descripton'];

    protected $fillable = [
        'name',
        'slug',
        'descripton',
        'type',
        'start',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'fanadata_id' => 'integer',
        ];
    }
}
