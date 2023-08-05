@extends("moonshine::layouts.app")

@section('sidebar-inner')
    @parent
@endsection

@section("header-inner")
    @parent

    <x-moonshine::breadcrumbs
        :items="[
        '/' => ':::heroicons.outline.home',
        '' => trans('moonshine::ui.create'),
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

    <form action="{{ route('moonshine-database.store') }}" method="POST">
        @csrf

        <x-moonshine::form.label required :name="trans('moonshine-database::ui.table_name')" required="true">
            {{ trans('moonshine-database::ui.table_name') }}
        </x-moonshine::form.label>
        <x-moonshine::form.input
            name="table[name]"
            class="mt-2"
            required
            :placeholder="trans('moonshine-database::ui.table_name')"
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

                <tr>
                    <td x-data="{ name: `first_column[name]` }">
                        <x-moonshine::form.input
                            x-bind:name="name"
                            :placeholder="trans('moonshine-database::ui.name')"
                            value="id"
                        />
                    </td>
                    <td x-data="{ name: `first_column[type]` }">
                        <x-moonshine::tooltip placement="bottom"
                                              :content="trans('moonshine-database::ui.select_tooltip')">
                            <x-moonshine::form.select
                                x-bind:name="name"
                                :searchable="true"
                            >
                                <x-slot:options>

                                    @foreach($types as $key => $value)
                                        <option
                                            value="{{ $value }}" {{ $key == 'bigint' ? 'selected' : '' }}>{{ class_basename($value) }}</option>
                                    @endforeach

                                </x-slot:options>
                            </x-moonshine::form.select>
                        </x-moonshine::tooltip>
                    </td>
                    <td x-data="{ name: `first_column[length]` }">
                        <x-moonshine::form.input
                            x-bind:name="name"
                            type="number"
                            :placeholder="trans('moonshine-database::ui.length')"
                        />
                    </td>
                    <td x-data="{ name: `first_column[not_null]` }">
                        <x-moonshine::form.switcher
                            :onValue="true"
                            :offValue="false"
                            x-bind:name="name"
                            checked="true"
                        />
                    </td>
                    <td x-data="{ name: `first_column[unsigned]` }">
                        <x-moonshine::form.switcher
                            :onValue="true"
                            :offValue="false"
                            x-bind:name="name"
                            checked="true"
                        />
                    </td>
                    <td x-data="{ name: `first_column[auto_inc]` }">
                        <x-moonshine::form.switcher
                            :onValue="true"
                            :offValue="false"
                            x-bind:name="name"
                            checked="true"
                        />
                    </td>
                    <td x-data="{ name: `first_column[index]` }">
                        <x-moonshine::form.select
                            x-bind:name="name"
                            value="PRIMARY"
                            :values="[
                                    '0' => trans('moonshine-database::ui.select_index'),
                                    'PRIMARY' => 'PRIMARY',
                                    'INDEX' => 'INDEX',
                                    'UNIQUE' => 'UNIQUE',
                                ]"
                            :searchable="true"
                        />
                    </td>
                    <td x-data="{ name: `first_column[by_default]` }">
                        <x-moonshine::form.input
                            x-bind:name="name"
                            :placeholder="trans('moonshine-database::ui.by_default')"
                        />
                    </td>
                    <td></td>
                </tr>

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

                                        @foreach($types as $key => $value)
                                            <option
                                                value="{{ $value }}">{{ class_basename($value) }}</option>
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
            {{ trans('moonshine::ui.create') }}
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
