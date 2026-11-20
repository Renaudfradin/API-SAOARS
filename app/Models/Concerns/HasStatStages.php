<?php

namespace App\Models\Concerns;

use App\Models\StatStage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasStatStages
{
    public function statStages(): MorphMany
    {
        return $this->morphMany(StatStage::class, 'statable')->orderBy('stage');
    }

    /**
     * @param  list<array{stage: int, hp: int, mp: int, atk: int, matk: int, def: int, mdef: int, crit: int, spd: int}>  $stages
     */
    public function replaceStatStages(array $stages): void
    {
        if ($stages === []) {
            return;
        }

        $this->statStages()->delete();

        foreach ($stages as $stage) {
            $this->statStages()->create($stage);
        }

        $max = collect($stages)->sortBy('stage')->last();

        if (is_array($max)) {
            $this->forceFill([
                'hp' => $max['hp'],
                'mp' => $max['mp'],
                'atk' => $max['atk'],
                'matk' => $max['matk'],
                'def' => $max['def'],
                'mdef' => $max['mdef'],
                'crit' => $max['crit'],
                'spd' => $max['spd'],
            ])->save();
        }
    }
}
