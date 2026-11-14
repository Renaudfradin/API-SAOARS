<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\WeaponResource;
use App\Models\Weapon;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentWeaponsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Dernières armes')
            ->query(fn (): Builder => Weapon::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')->label('Nom')->limit(36),
                TextColumn::make('type')->label('Type'),
                TextColumn::make('created_at')->label('Ajout')->since(),
            ])
            ->recordUrl(fn (Weapon $record): string => WeaponResource::getUrl('view', ['record' => $record]))
            ->headerActions([
                Action::make('all')
                    ->label('Tout voir')
                    ->icon(Heroicon::OutlinedArrowRight)
                    ->url(WeaponResource::getUrl())
                    ->link(),
            ])
            ->emptyStateHeading('Aucune arme')
            ->emptyStateDescription('Les armes ajoutées apparaîtront ici.');
    }
}
