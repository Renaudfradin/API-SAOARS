<?php

namespace App\Enums;

enum WeaponType: string
{
    case Épée = 'sword';
    case Rapière = 'rapier';
    case Dague = 'dagger';
    case Arc = 'bow';
    case DoubleÉpée = 'dual swords';
    case Gant = 'gauntlet';
    case Fusil = 'rifle';
    case Masse = 'mace';
}
