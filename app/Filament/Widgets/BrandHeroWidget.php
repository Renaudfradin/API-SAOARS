<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class BrandHeroWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected string $view = 'filament.widgets.brand-hero';

    protected int|string|array $columnSpan = 'full';
}
