@extends("moonshine::layouts.app")

@section('sidebar-inner')
    @parent
@endsection

@section("header-inner")
    @parent

    <x-moonshine::breadcrumbs
        :items="[
        '/' => ':::heroicons.outline.home',
        url($prefix) => trans('moonshine-database::ui.show'),
        '' => ucfirst($table->getName())
    ]"
    />

@endsection

@section('content')

    @push('styles')
        <style>
            input {
                margin-bottom: 0 !important;
            }

            .choices__list.choices__list--dropdown {
                min-width: max-content;
            }
        </style>
    @endpush

    @if($errors->any())
        <x-moonshine::alert type="error">{{ $errors->first() }}</x-moonshine::alert>
    @endif

    <x-moonshine::form.label>
        {{ trans('moonshine-database::ui.table_name') }} : {{ ucfirst($table->getName()) }}
    </x-moonshine::form.label>

    <x-moonshine::table
        :crudMode="true"
        x-data="addRemove()"
    >

        <x-slot:thead>
            <tr>
                <td>
                    {{ trans('moonshine-database::ui.name') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.type') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.length') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.not_null') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.unsigned') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.auto_inc') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.index') }}
                </td>
                <td>
                    {{ trans('moonshine-database::ui.by_default') }}
                </td>
                <td></td>
            </tr>
        </x-slot:thead>

        <x-slot:tbody>

            @foreach($table->getColumns() as $column)
                <tr>
                    <td>
                        <x-moonshine::form.label>
                            {{ $column->getName() }}
                        </x-moonshine::form.label>
                    </td>
                    <td>
                        <x-moonshine::form.label>
                            {{ $column->getType()->getName() }}
                        </x-moonshine::form.label>
                    </td>
                    <td>
                        <x-moonshine::form.label>
                            {{ $column->getLength() ?? '-' }}
                        </x-moonshine::form.label>
                    </td>
                    <td>
                        <x-moonshine::boolean :value="$column->getNotNull()" />
                    </td>
                    <td>
                        <x-moonshine::boolean :value="$column->getUnsigned()" />
                    </td>
                    <td>
                        <x-moonshine::boolean :value="$column->getAutoincrement()" />
                    </td>
                    <td>
                        <x-moonshine::form.label>
                            {{ $column->index !=- '0' ? $column->index : '-' }}
                        </x-moonshine::form.label>
                    </td>
                    <td >
                        <x-moonshine::form.label>
                            {{ $column->getDefault() ?? '-' }}
                        </x-moonshine::form.label>
                    </td>
                    <td></td>
                </tr>
            @endforeach

        </x-slot:tbody>
    </x-moonshine::table>

@endsection
