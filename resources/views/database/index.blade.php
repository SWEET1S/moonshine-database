<x-moonshine::link
    href="{{ route('moonshine-database.create') }}"
    icon="add"
    :filled="true"
>
    {{ trans('moonshine::ui.create') }}
</x-moonshine::link>

<x-moonshine::table
    :crudMode="true"
>

    <x-slot:thead>
        <tr>
            <th>#</th>
            <th>{{ trans('moonshine-database::ui.table_name') }}</th>
            <th></th>
        </tr>
    </x-slot:thead>

    <x-slot:tbody>
        @foreach($tables as $table)

            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>{{ $table }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        <x-moonshine::link
                            href="{{ route('moonshine-database.show', $table) }}"
                            icon="heroicons.eye"
                        >
                        </x-moonshine::link>

                        <x-moonshine::link
                            href="{{ route('moonshine-database.edit', $table) }}"
                            :filled="true"
                            icon="heroicons.pencil"
                        >
                        </x-moonshine::link>

                        <x-moonshine::modal :title="trans('moonshine::ui.deleting')">

                            <form action="{{ route('moonshine-database.destroy', $table) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <div>
                                    {{ trans('moonshine::ui.confirm_message') }}
                                </div>

                                <button type="submit" class="btn btn-primary btn-pink mt-5">
                                    {{ trans('moonshine::ui.confirm') }}
                                </button>
                            </form>

                            <x-slot name="outerHtml">
                                <x-moonshine::link @click.prevent="toggleModal;" icon="heroicons.trash"
                                                   class="btn-pink">
                                </x-moonshine::link>
                            </x-slot>
                        </x-moonshine::modal>
                    </div>
                </td>
            </tr>

        @endforeach
    </x-slot:tbody>
</x-moonshine::table>
