<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StatStage extends Model
{
    protected $fillable = [
        'statable_type',
        'statable_id',
        'stage',
        'hp',
        'mp',
        'atk',
        'matk',
        'def',
        'mdef',
        'crit',
        'spd',
    ];

    protected function casts(): array
    {
        return [
            'stage' => 'integer',
            'hp' => 'integer',
            'mp' => 'integer',
            'atk' => 'integer',
            'matk' => 'integer',
            'def' => 'integer',
            'mdef' => 'integer',
            'crit' => 'integer',
            'spd' => 'integer',
        ];
    }

    public function statable(): MorphTo
    {
        return $this->morphTo();
    }
}
