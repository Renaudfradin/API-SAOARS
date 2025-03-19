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
            Stat::make('Total Characters', Character::count()),
            Stat::make('Total Weapons', Weapon::count()),
            Stat::make('Total Attacks', Attack::count()),
            Stat::make('Total Banners', Banner::count()),
            Stat::make('Total Equipments', Equipment::count()),
            Stat::make('Total Imaginations', Imagination::count()),
            Stat::make('Total Abilities', Ability::count()),
        ];
    }
}
