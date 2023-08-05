<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Sweet1s\MoonShineDatabase\Http\Controllers\MoonShineDatabaseController;

$middlewares = collect(config('moonshine.route.middleware'))
    ->reject(static fn($middleware): bool => $middleware === 'web')
    ->toArray();

$prefix = config('moonshine.route.prefix') . "/" . config('moonshine.route.custom_page_slug') . "/" . config('moonshine-database.slug');

Route::middleware($middlewares)->group(function () use ($prefix) {

    View::share('prefix', $prefix);

    Route::middleware('auth.moonshine')->prefix($prefix)->group(function () {

        Route::resource('moonshine-database', MoonShineDatabaseController::class);

    });

});
