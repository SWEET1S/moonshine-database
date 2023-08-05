<?php

namespace Sweet1s\MoonShineDatabase\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class MoonShineDatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/moonshine-database.php', 'moonshine-database'
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/moonshine-database.php');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-database');

        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'moonshine-database');

        $this->publishes([
            __DIR__ . '/../../config/moonshine-database.php' => config_path('moonshine-database.php'),
        ]);
    }
}
