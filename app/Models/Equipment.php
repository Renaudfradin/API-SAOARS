<?php

namespace App\Models;

use App\Enums\Element;
use App\Enums\EquipmentType;
use App\Models\Concerns\HasCatalogTranslations;
use App\Models\Concerns\HasStatStages;
use App\Support\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasCatalogTranslations;
    use HasFactory;
    use HasStatStages;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'effect_1', 'effect_2'];

    protected $fillable = [
        'name',
        'slug',
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
        'image',
        'image2',
        'fanadata_id',
    ];

    protected function casts(): array
    {
        return [
            'type' => EquipmentType::class,
            'type_equipment' => Element::class,
            'fanadata_id' => 'integer',
        ];
    }

    public function portraitUrl(): ?string
    {
        return Media::url($this->image) ?? $this->fanadataAsset(false);
    }

    public function thumbnailUrl(): ?string
    {
        return Media::url($this->image2) ?? $this->fanadataAsset(true);
    }

    private function fanadataAsset(bool $thumbnail): ?string
    {
        if (! $this->fanadata_id) {
            return null;
        }

        $id = (int) $this->fanadata_id;
        $base = 'https://saoub.fanadata.com/assets/equipments/';

        return $thumbnail
            ? $base.'thumbnail/stuff_'.$id.'.png'
            : $base.'stuff_'.$id.'.jpg';
    }
}
