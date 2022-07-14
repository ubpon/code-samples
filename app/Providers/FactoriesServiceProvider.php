<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class FactoriesServiceProvider extends ServiceProvider
{
    /**
     * Register the application factories as services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach (\config('factories.factories', []) as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
