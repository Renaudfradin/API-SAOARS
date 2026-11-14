<?php

namespace App\Providers;

use Filament\Tables\Table;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (getenv('LARAVEL_SAIL')) {
            $compiled = '/tmp/laravel-views';

            if (! is_dir($compiled)) {
                mkdir($compiled, 0777, true);
            }

            @chmod($compiled, 0777);

            if (is_writable($compiled)) {
                config(['view.compiled' => $compiled]);
            }
        }
    }

    public function boot(): void
    {
        $this->configureRateLimiting();

        Table::configureUsing(function (Table $table): void {
            $table->columnManager();
        });

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('rate_limiting.api.limit', 60))
                ->by($request->ip())
                ->response($this->tooManyRequestsResponse(...));
        });

        RateLimiter::for('strict', function (Request $request) {
            return Limit::perMinute(config('rate_limiting.strict.limit', 20))
                ->by($request->ip())
                ->response($this->tooManyRequestsResponse(...));
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('rate_limiting.login.limit', 5))
                ->by($request->ip())
                ->response($this->tooManyRequestsResponse(...));
        });
    }

    /**
     * @param  array<string, mixed>  $headers
     */
    protected function tooManyRequestsResponse(Request $request, array $headers): Response
    {
        return response()->json([
            'message' => 'Trop de requêtes. Veuillez réessayer plus tard.',
        ], 429, $headers);
    }
}
