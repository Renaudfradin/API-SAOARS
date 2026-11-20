<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\EquipmentResource;
use App\Filament\Resources\WeaponResource;
use App\Filament\Widgets\BrandHeroWidget;
use App\Filament\Widgets\RecentCharactersTable;
use App\Filament\Widgets\RecentEquipmentTable;
use App\Filament\Widgets\RecentWeaponsTable;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected ?string $subheading = 'Vue d\'ensemble de SAO:UB';

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewCharacters')
                ->label('Personnages')
                ->icon(Heroicon::OutlinedUser)
                ->url(fn (): string => CharacterResource::getUrl()),
            Action::make('viewWeapons')
                ->label('Armes')
                ->icon(Heroicon::OutlinedBolt)
                ->url(fn (): string => WeaponResource::getUrl()),
            Action::make('viewEquipment')
                ->label('Équipements')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->url(fn (): string => EquipmentResource::getUrl()),
            Action::make('createCharacter')
                ->label('Nouveau personnage')
                ->icon(Heroicon::OutlinedPlus)
                ->url(fn (): string => CharacterResource::getUrl('create'))
                ->visible(fn (): bool => CharacterResource::canCreate()),
        ];
    }

    public function getWidgets(): array
    {
        return [
            BrandHeroWidget::class,
            StatsOverviewWidget::class,
            RecentCharactersTable::class,
            RecentWeaponsTable::class,
            RecentEquipmentTable::class,
        ];
    }
}
