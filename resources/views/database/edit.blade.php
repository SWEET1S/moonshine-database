@extends("moonshine::layouts.app")

@section('sidebar-inner')
    @parent
@endsection

@section("header-inner")
    @parent

    <x-moonshine::breadcrumbs
        :items="[
        '/' => ':::heroicons.outline.home',
        url($prefix) => trans('moonshine-database::ui.database'),
        '' => ucfirst($table)
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

    <form action="{{ route('moonshine-database.update', $table) }}" method="POST">
        @csrf
        @method('PUT')

        <x-moonshine::form.label required :name="trans('moonshine-database::ui.table_name')" required="true">
            {{ trans('moonshine-database::ui.table_name') }}
        </x-moonshine::form.label>
        <x-moonshine::form.input
            name="table[name]"
            class="mt-2"
            required
            :placeholder="trans('moonshine-database::ui.table_name')"
            value="{{ $table }}"
        />
        <x-moonshine::form.input
            name="table[name_old]"
            class="mt-2"
            type="hidden"
            required
            :placeholder="trans('moonshine-database::ui.table_name')"
            value="{{ $table }}"
        />

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
                @foreach($columns as $column)

                    <tr x-data="{ removeRow(){ this.$el.closest('tr').remove(); } }">
                        <td>
                            <x-moonshine::form.input
                                name="{{ $column->getName() }}[name]"
                                :placeholder="trans('moonshine-database::ui.name')"
                                :value="$column->getName()"
                            />
                        </td>
                        <td>
                            <x-moonshine::tooltip placement="bottom"
                                                  :content="trans('moonshine-database::ui.select_tooltip')">
                                <x-moonshine::form.select
                                    name="{{ $column->getName() }}[type]"
                                    :searchable="true"
                                    :value="$column->getType()->getName()"
                                >
                                    <x-slot:options>

                                        @foreach($column->getType()->getTypesMap() as $key => $value)
                                            <option
                                                value="{{ $value }}" {{ $column->getType()->getName() == $key ? 'selected' : '' }}>{{ class_basename($value) }}</option>
                                        @endforeach

                                    </x-slot:options>
                                </x-moonshine::form.select>
                            </x-moonshine::tooltip>
                        </td>
                        <td>
                            <x-moonshine::form.input
                                name="{{ $column->getName() }}[length]"
                                type="number"
                                :placeholder="trans('moonshine-database::ui.length')"
                                :value="$column->getLength()"
                            />
                        </td>
                        <td>
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                name="{{ $column->getName() }}[not_null]"
                                :checked="$column->getNotnull()"
                            />
                        </td>
                        <td>
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                name="{{ $column->getName() }}[unsigned]"
                                :checked="$column->getUnsigned()"
                            />
                        </td>
                        <td>
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                name="{{ $column->getName() }}[auto_inc]"
                                :checked="$column->getAutoincrement()"
                            />
                        </td>
                        <td>
                            <x-moonshine::form.select
                                name="{{ $column->getName() }}[index]"
                                :values="[
                                    '0' => trans('moonshine-database::ui.select_index'),
                                    'PRIMARY' => 'PRIMARY',
                                    'INDEX' => 'INDEX',
                                    'UNIQUE' => 'UNIQUE',
                                ]"
                                :value="$column->index"
                                :searchable="true"
                            />
                        </td>
                        <td>
                            <x-moonshine::form.input
                                name="{{ $column->getName() }}[by_default]"
                                :placeholder="trans('moonshine-database::ui.by_default')"
                                :value="$column->getDefault()"
                            />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">

                                <button type="submit" class="btn btn-primary btn-pink mt-5"
                                        @click.prevent="removeRow()">
                                    <x-moonshine::icon icon="heroicons.trash"/>
                                </button>

                            </div>
                        </td>
                    </tr>

                @endforeach
                <template x-for="(field, index) in fields" :key="field.id">
                    <tr>
                        <td x-data="{ name: `${index}_column[name]` }">
                            <x-moonshine::form.input
                                x-bind:name="name"
                                :placeholder="trans('moonshine-database::ui.name')"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[type]` }">
                            <x-moonshine::tooltip placement="bottom"
                                                  :content="trans('moonshine-database::ui.select_tooltip')">
                                <x-moonshine::form.select
                                    x-bind:name="name"
                                    :searchable="true"
                                >
                                    <x-slot:options>

                                        @foreach($column->getType()->getTypesMap() as $key => $value)
                                            <option
                                                value="{{ $value }}" {{ $column->getType()->getName() == $key ? 'selected' : '' }}>{{ class_basename($value) }}</option>
                                        @endforeach

                                    </x-slot:options>
                                </x-moonshine::form.select>
                            </x-moonshine::tooltip>
                        </td>
                        <td x-data="{ name: `${index}_column[length]` }">
                            <x-moonshine::form.input
                                x-bind:name="name"
                                type="number"
                                :placeholder="trans('moonshine-database::ui.length')"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[not_null]` }">
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                x-bind:name="name"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[unsigned]` }">
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                x-bind:name="name"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[auto_inc]` }">
                            <x-moonshine::form.switcher
                                :onValue="true"
                                :offValue="false"
                                x-bind:name="name"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[index]` }">
                            <x-moonshine::form.select
                                x-bind:name="name"
                                :values="[
                                    '0' => trans('moonshine-database::ui.select_index'),
                                    'PRIMARY' => 'PRIMARY',
                                    'INDEX' => 'INDEX',
                                    'UNIQUE' => 'UNIQUE',
                                ]"
                                :searchable="true"
                            />
                        </td>
                        <td x-data="{ name: `${index}_column[by_default]` }">
                            <x-moonshine::form.input
                                x-bind:name="name"
                                :placeholder="trans('moonshine-database::ui.by_default')"
                            />
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">

                                <button class="btn btn-primary btn-pink mt-5" @click.prevent="removeField(field)">
                                    <x-moonshine::icon icon="heroicons.trash"/>
                                </button>

                            </div>
                        </td>
                    </tr>
                </template>
            </x-slot:tbody>
            <x-slot:tfoot>
                <td colspan="9">
                    <div class="flex justify-end">
                        <button class="btn btn-primary btn-pink mt-5" @click.prevent="addNewField()">
                            <x-moonshine::icon icon="heroicons.plus"/>
                        </button>
                    </div>
                </td>
            </x-slot:tfoot>
        </x-moonshine::table>

        <button type="submit" class="btn btn-primary mt-5">
            {{ trans('moonshine::ui.save') }}
        </button>
    </form>

@endsection

@push('scripts')
    <script>
        function addRemove() {
            return {
                fields: [],
                addNewField() {
                    this.fields.push({id: new Date().getTime() + this.fields.length});
                },
                removeField(field) {
                    this.fields.splice(this.fields.indexOf(field), 1);
                }
            }
        }
    </script>
@endpush
