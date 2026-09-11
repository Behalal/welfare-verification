<?php

declare(strict_types=1);

namespace Welfare\Verification;

use Illuminate\Support\ServiceProvider;

class VerificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/verification.php',
            'verification'
        );

        $this->app->singleton(VerificationManager::class, function ($app) {
            return new VerificationManager($app);
        });

        $this->app->alias(VerificationManager::class, 'welfare.verification');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/verification.php' => config_path('verification.php'),
            ], 'verification-config');
        }
    }
}
