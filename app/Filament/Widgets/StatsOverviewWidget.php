<?php

namespace App\Filament\Widgets;

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\Weapon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Personnages', Character::count()),
            Stat::make('Armes', Weapon::count()),
            Stat::make('Attaques', Attack::count()),
            Stat::make('Bannières', Banner::count()),
            Stat::make('Équipements', Equipment::count()),
            Stat::make('Imagination', Imagination::count()),
            Stat::make('Abilités', Ability::count()),
        ];
    }
}
