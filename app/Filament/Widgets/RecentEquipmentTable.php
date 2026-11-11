<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EquipmentResource;
use App\Models\Equipment;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentEquipmentTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Derniers équipements')
            ->query(fn (): Builder => Equipment::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')->label('Nom')->limit(36),
                TextColumn::make('type')->label('Type'),
                TextColumn::make('created_at')->label('Ajout')->since(),
            ])
            ->recordUrl(fn (Equipment $record): string => EquipmentResource::getUrl('view', ['record' => $record]))
            ->headerActions([
                Action::make('all')
                    ->label('Tout voir')
                    ->icon(Heroicon::OutlinedArrowRight)
                    ->url(EquipmentResource::getUrl())
                    ->link(),
            ])
            ->emptyStateHeading('Aucun équipement')
            ->emptyStateDescription('Les équipements ajoutés apparaîtront ici.');
    }
}
