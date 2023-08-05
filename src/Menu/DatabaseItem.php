<?php

namespace Sweet1s\MoonShineDatabase\Menu;

use Illuminate\Support\Facades\Gate;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\CustomPage;
use Sweet1s\MoonShineDatabase\Http\Controllers\MoonShineDatabaseController;

class DatabaseItem
{
    public static function make(): MenuItem
    {
        $item = MenuItem::make(
            'moonshine-database::ui.database',
            CustomPage::make('moonshine-database::ui.database', config('moonshine-database.slug'), 'moonshine-database::database.index', fn() => [
                'tables' => (new MoonShineDatabaseController())->index()
            ])
                ->translatable()
        )->icon(config('moonshine-database.icon'))
            ->translatable();

        if (config('moonshine-database.auth.enable')) {
            $item = $item->canSee(fn() => Gate::check(config('moonshine-database.auth.permissions.viewAny')));
        }

        return $item;
    }
}
