<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model
{
    use HasCatalogTranslations;
    use HasFactory;

    /**
     * @var list<string>
     */
    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'from',
        'to',
        'img',
        'character_ids',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'from' => 'datetime',
            'to' => 'datetime',
            'character_ids' => 'array',
            'fanadata_id' => 'integer',
        ];
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'banner_id');
    }
}
