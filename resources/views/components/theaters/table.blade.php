<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">Name</th>
        </tr>
        </thead>
        <tbody>
        @if(sizeof($theaters) != 0)
        @foreach ($theaters as $theater)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $theater->name }}</td>
                @if($showView)
                    <td>
                        <x-table.icon-show class="ps-3 px-0.5"
                        href="{{ route('theaters.show', ['theater' => $theater]) }}"/>
                    </td>
                @endif
                @if($showEdit)
                    <td>
                        <x-table.icon-edit class="px-0.5"
                        href="{{ route('theaters.edit', ['theater' => $theater]) }}"/>
                    </td>
                @endif
                @if($showDelete)
                    <td>
                        <x-table.icon-delete class="px-0.5"
                        action="{{ route('theaters.destroy', ['theater' => $theater]) }}"/>
                    </td>
                @endif
            </tr>
        @endforeach
        @else
        <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
        <td colspan="6"><div class="text-center table-cell text-gray-900 dark:text-gray-100">
                    <img src="{{ url('storage/theaters/unavailable.svg') }}" class="mx-auto w-16">
                    <br>
                    <p class="text-center text-gray-900 dark:text-gray-100 font-bold">NO THEATERS AVAILABLE FOR THIS NAME</p>
                </div>
                </td>
                </tr>
        @endif
        </tbody>
    </table>
</div>
