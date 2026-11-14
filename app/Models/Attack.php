<?php

namespace App\Models;

use App\Enums\AttackType;
use App\Models\Concerns\HasCatalogTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
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
        'mp_cost',
        'type_atk',
        'character_id',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'type_atk' => AttackType::class,
            'fanadata_id' => 'integer',
        ];
    }
}
