<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class StatStageRepeater
{
    public static function make(): Repeater
    {
        return Repeater::make('statStages')
            ->relationship()
            ->label('Paliers')
            ->columnSpanFull()
            ->maxItems(5)
            ->schema([
                TextInput::make('stage')->label('Stade')->numeric()->required()->minValue(1)->maxValue(5),
                TextInput::make('hp')->label('HP')->numeric()->required(),
                TextInput::make('mp')->label('MP')->numeric()->required(),
                TextInput::make('atk')->label('ATK')->numeric()->required(),
                TextInput::make('matk')->label('MATK')->numeric()->required(),
                TextInput::make('def')->label('DEF')->numeric()->required(),
                TextInput::make('mdef')->label('MDEF')->numeric()->required(),
                TextInput::make('crit')->label('CRIT')->numeric()->required(),
                TextInput::make('spd')->label('SPD')->numeric()->required(),
            ])
            ->columns(3);
    }
}
