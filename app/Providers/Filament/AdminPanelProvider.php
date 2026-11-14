<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\FontProviders\BunnyFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->spa()
            ->id('admin')
            ->path('admin')
            ->login()
            ->topbar(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Albert Sans', provider: BunnyFontProvider::class)
            ->serifFont('Lora', provider: BunnyFontProvider::class)
            ->brandLogo(asset('brand/icon.png'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('brand/icon.png'))
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->colors([
                'primary' => [
                    50 => 'oklch(0.975 0.018 150)',
                    100 => 'oklch(0.945 0.035 150)',
                    200 => 'oklch(0.90 0.05 152)',
                    300 => 'oklch(0.82 0.07 152)',
                    400 => 'oklch(0.70 0.09 152)',
                    500 => 'oklch(0.58 0.10 152)',
                    600 => 'oklch(0.48 0.095 152)',
                    700 => 'oklch(0.40 0.08 152)',
                    800 => 'oklch(0.34 0.065 152)',
                    900 => 'oklch(0.28 0.05 152)',
                    950 => 'oklch(0.20 0.035 152)',
                ],
                'gray' => [
                    50 => 'oklch(0.975 0.012 82)',
                    100 => 'oklch(0.952 0.018 80)',
                    200 => 'oklch(0.915 0.022 76)',
                    300 => 'oklch(0.86 0.026 72)',
                    400 => 'oklch(0.73 0.028 68)',
                    500 => 'oklch(0.60 0.026 64)',
                    600 => 'oklch(0.50 0.022 60)',
                    700 => 'oklch(0.42 0.018 55)',
                    800 => 'oklch(0.32 0.014 50)',
                    900 => 'oklch(0.25 0.012 48)',
                    950 => 'oklch(0.18 0.010 45)',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn (): string => 'Contenu')
                    ->icon('heroicon-o-newspaper'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
