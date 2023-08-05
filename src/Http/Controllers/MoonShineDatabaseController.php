<?php

namespace Sweet1s\MoonShineDatabase\Http\Controllers;

use App\Http\Controllers\Controller;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use MoonShine\MoonShineUI;
use Sweet1s\MoonShineDatabase\Database\Database;

class MoonShineDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        if (config('moonshine-database.auth.enable') && !Gate::check(config('moonshine-database.auth.permissions.viewAny'))) {
            return [];
        }

        try {
            return Database::manager()->listTableNames();

        } catch (Exception $exception) {

            MoonShineUI::toast(
                $exception->getMessage(),
                'error'
            );

            return [];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.create'));
        }

        $types = Type::getTypesMap();

        return view('moonshine-database::database.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.create'));
        }

        try {
            $request = $request->except(['_token', '_method', 'search_terms']);

            Database::createTable($request);

            MoonShineUI::toast(
                trans('moonshine-database::ui.table_created'),
                'success'
            );

            return to_route('moonshine-database.edit', $request['table']['name']);

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $table
     * @return Application|Factory|View
     * @throws Exception
     */
    public function show(string $table)
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.view'));
        }

        $table = $this->getTable($table);

        return view('moonshine-database::database.show', compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $table
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(string $table)
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.update'));
        }

        if (!Schema::hasTable($table)) {
            MoonShineUI::toast(
                trans('moonshine-database::ui.table_not_exist'),
                'error'
            );

            return back();
        }

        try {
            $table = $this->getTable($table);

            return view('moonshine-database::database.edit', [
                'table' => $table->getName(),
                'columns' => $table->getColumns()
            ]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $table
     * @return RedirectResponse
     */
    public function update(Request $request, string $table)
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.update'));
        }

        try {
            $request = $request->except(['_token', '_method', 'search_terms']);

            Database::update($request);

            MoonShineUI::toast(
                trans('moonshine-database::ui.table_updated'),
                'success'
            );

            return to_route('moonshine-database.edit', $request['table']['name']);

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $table
     * @return RedirectResponse
     */
    public function destroy(string $table)
    {
        if (config('moonshine-database.auth.enable')) {
            Gate::authorize(config('moonshine-database.auth.permissions.destroy'));
        }

        try {
            Database::destroy($table);

            MoonShineUI::toast(
                trans('moonshine-database::ui.table_deleted'),
                'success'
            );

            return back();
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * @param string $table
     * @return Table
     * @throws Exception
     */
    public function getTable(string $table): Table
    {
        $table = Database::manager()->introspectTable($table);

        foreach ($table->getColumns() as $column) {
            $column->index = '0';
            foreach ($table->getIndexes() as $index) {
                if ($index->spansColumns([$column->getName()]) && $index->isPrimary() && $index->isUnique()) {
                    $column->index = 'PRIMARY';
                }
                if ($index->spansColumns([$column->getName()]) && !$index->isPrimary() && $index->isUnique()) {
                    $column->index = 'UNIQUE';
                }
                if ($index->spansColumns([$column->getName()]) && !$index->isPrimary() && !$index->isUnique()) {
                    $column->index = 'INDEX';
                }
            }
        }
        return $table;
    }

}
