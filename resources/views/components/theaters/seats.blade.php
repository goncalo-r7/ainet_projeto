<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left text-gray-900 dark:text-gray-100">Seat Number</th>
            <th class="px-2 py-2 text-right hidden md:table-cell text-gray-900 dark:text-gray-100">Row</th>
        </tr>
        </thead>
        <tbody>
        @if($seats->count())
            @foreach ($seats as $seat)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left hidden sm:table-cell text-gray-900 dark:text-gray-100">{{ $seat->seat_number }}</td>
                    <td class="px-2 py-2 text-left text-gray-900 dark:text-gray-100">{{ $seat->row }}</td>
                </tr>
            @endforeach
        @else
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-center sm:table-cell text-gray-900 dark:text-gray-100" colspan="2">
                    <img src="{{ url('storage/photos/unavailable.svg') }}" class="mx-auto w-16">
                    <br>
                    <p class="text-center text-gray-900 dark:text-gray-100 font-bold">NO SEATS AVAILABLE FOR THIS THEATRE</p>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
