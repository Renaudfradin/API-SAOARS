<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AbilityResource;
use App\Filament\Resources\AttackResource;
use App\Filament\Resources\BannerResource;
use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\EquipmentResource;
use App\Filament\Resources\ImaginationResource;
use App\Filament\Resources\WeaponResource;
use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\Weapon;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Catalogue';

    protected ?string $description = 'Personnages, armes, équipements et le reste du bestiaire';

    protected function getColumns(): int|array|null
    {
        return [
            'default' => 1,
            'sm' => 2,
            'xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Personnages', number_format(Character::query()->count()))
                ->description('Fiches')
                ->icon(Heroicon::OutlinedUser)
                ->url(CharacterResource::getUrl()),
            Stat::make('Armes', number_format(Weapon::query()->count()))
                ->description('Arsenal')
                ->icon(Heroicon::OutlinedBolt)
                ->url(WeaponResource::getUrl()),
            Stat::make('Équipements', number_format(Equipment::query()->count()))
                ->description('Armures et accessoires')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->url(EquipmentResource::getUrl()),
            Stat::make('Attaques', number_format(Attack::query()->count()))
                ->description('Sword skills')
                ->icon(Heroicon::OutlinedFire)
                ->url(AttackResource::getUrl()),
            Stat::make('Aptitudes', number_format(Ability::query()->count()))
                ->description('Compétences')
                ->icon(Heroicon::OutlinedSparkles)
                ->url(AbilityResource::getUrl()),
            Stat::make('Bannières', number_format(Banner::query()->count()))
                ->description('Recrutements')
                ->icon(Heroicon::OutlinedFlag)
                ->url(BannerResource::getUrl()),
            Stat::make('Imaginations', number_format(Imagination::query()->count()))
                ->description('Illustrations')
                ->icon(Heroicon::OutlinedPhoto)
                ->url(ImaginationResource::getUrl()),
        ];
    }
}
