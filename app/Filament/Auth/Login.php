<?php

namespace App\Filament\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\RateLimiter;

class Login extends BaseLogin
{
    protected function rateLimit($maxAttempts, $decaySeconds = 60, $method = null, $component = null)
    {
        $limiter = RateLimiter::limiter('login');

        if (! is_callable($limiter)) {
            parent::rateLimit(
                (int) config('rate_limiting.login.limit', $maxAttempts),
                $decaySeconds,
                $method,
                $component,
            );

            return;
        }

        $limits = array_values(array_filter(
            Arr::wrap($limiter(request())),
            fn (mixed $limit): bool => $limit instanceof Limit,
        ));

        foreach ($limits as $limit) {
            $key = md5('login'.$limit->key);

            if (RateLimiter::tooManyAttempts($key, $limit->maxAttempts)) {
                throw new TooManyRequestsException(
                    $component ?? static::class,
                    $method ?? 'authenticate',
                    (string) request()->ip(),
                    RateLimiter::availableIn($key),
                );
            }
        }

        foreach ($limits as $limit) {
            RateLimiter::hit(md5('login'.$limit->key), $limit->decaySeconds);
        }
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->default('demo@gmail.com');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->default('demo@gmail.comdemo@gmail.com');
    }
}
