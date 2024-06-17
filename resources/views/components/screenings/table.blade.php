{{-- o nome dos teatros ficaram repetidos uma vez que ao nao repetir o nome nao poderiamos manter a linha vermelha, ficando mais atrativo desta forma --}}

<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-center">Theatre</th>
                @if ($showMovie)
                <th class="px-2 py-2 text-center">Movie</th>
                @endif
                <th class="px-2 py-2 text-center hidden md:table-cell">Date</th>
                <th class="px-2 py-2 text-center hidden lg:table-cell">Start time</th>
                <th class="px-2 py-2 text-center"></th>
                @if ($showView)
                    <th></th>
                @endif
                @if ($showEdit)
                    <th></th>
                @endif
                @if ($showDelete)
                    <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($screenings as $screening)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500 {{ $screeningSoldOut[$screening->id] ? 'bg-red-500' : '' }}">
                    <td class="px-2 py-2 text-center">{{ $screening->theater->name }}</td>
                    @if ($showMovie)
                    <td class="px-2 py-2 text-center">{{ $screening->movie->title }}</td>
                     @endif
                    <td class="px-2 py-2 text-center hidden md:table-cell">{{ $screening->date }}</td>
                    <td class="px-2 py-2 text-center hidden md:table-cell">{{ substr($screening->start_time, 0, 5) }}</td>
                    <td class="px-2 py-2 flex justify-center items-center">
                        @if (!$screeningSoldOut[$screening->id] && $showSeat)
                        <x-table.icon-seat class="px-0.5"
                            href="{{ route('seats.index', ['screening' => $screening->id]) }}" />
                        @endif
                        @can('validate',$screening)
                        <x-table.icon-qrcode class="px-0.5 "
                            href="{{ route('tickets.verify', ['screening' => $screening->id]) }}" />
                        @endcan
                        @if ($showView)
                        @can('view',$screening)
                        <td>
                            <x-table.icon-show class="ps-3 px-0.5" href="{{ route('screenings.show', ['screening' => $screening]) }}" />
                        </td>
                        @endcan

                        @endif
                        @if ($showEdit)
                        @can('update',$screening)

                            <td>
                                <x-table.icon-edit class="px-0.5" href="{{ route('screenings.edit', ['screening' => $screening]) }}" />
                            </td>
                        @endcan
                        @endif
                        @if ($showDelete)
                        @can('delete',$screening)
                            <td>
                                <x-table.icon-delete class="px-0.5" action="{{ route('screenings.destroy', ['screening' => $screening]) }}" />
                            </td>
                        @endcan
                        @endif
                    </td>

            </tr>
            @endforeach
        </tbody>
    </table>

</div>
