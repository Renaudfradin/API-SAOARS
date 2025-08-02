<?php

namespace App\Enums;

enum Element: string
{
    case Neutre = 'neutral';
    case Eau = 'water';
    case Feu = 'fire';
    case Vent = 'wind';
    case Terre = 'earth';
    case Lumière = 'light';
    case Obscurité = 'darkness';
}
