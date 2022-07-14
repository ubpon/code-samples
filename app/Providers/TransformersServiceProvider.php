<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class TransformersServiceProvider extends ServiceProvider
{
    /**
     * Register the application transformers as services.
     *
     * @return void
     */
    public function register(): void
    {
        foreach (\config('transformers.transformers', []) as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
