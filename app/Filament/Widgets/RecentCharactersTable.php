<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CharacterResource;
use App\Models\Character;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentCharactersTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Derniers personnages')
            ->query(fn (): Builder => Character::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('name')->label('Nom')->limit(36),
                TextColumn::make('element')->label('Élément'),
                TextColumn::make('created_at')->label('Ajout')->since(),
            ])
            ->recordUrl(fn (Character $record): string => CharacterResource::getUrl('view', ['record' => $record]))
            ->headerActions([
                Action::make('all')
                    ->label('Tout voir')
                    ->icon(Heroicon::OutlinedArrowRight)
                    ->url(CharacterResource::getUrl())
                    ->link(),
            ])
            ->emptyStateHeading('Aucun personnage')
            ->emptyStateDescription('Les personnages ajoutés apparaîtront ici.');
    }
}
