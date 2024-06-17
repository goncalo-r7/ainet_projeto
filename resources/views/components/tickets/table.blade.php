<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-center hidden sm:table-cell">Movie</th>
            <th class="px-2 py-2 text-center">Theatre</th>
            <th class="px-2 py-2 text-center hidden md:table-cell">Date</th>
            <th class="px-2 py-2 text-center hidden md:table-cell">Start Time</th>
            <th class="px-2 py-2 text-center hidden md:table-cell">Seat</th>
            {{-- @if($showView)
                <th></th>
            @endif --}}
            {{-- @if($showEdit)
                <th></th>
            @endif
            @if($showDelete)
                <th></th>
            @endif --}}
            @if($showRemoveFromCart)
                <th></th>
            @endif
        </tr>
        </thead>
        <tbody>
            {{-- $tickets is the cart --}}
            @foreach ($ticketdata as $data)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-center hidden sm:table-cell"><a href={{$data['url']}}>{{ $data['movieTitle'] }}</a></td>
                <td class="px-2 py-2 text-center">{{ $data['theaterName'] }}</td>
                <td class="px-2 py-2 text-center hidden md:table-cell">{{ date('d-m-Y', strtotime($data['screening']->date)) }}</td>
                <td class="px-2 py-2 text-center hidden md:table-cell">{{ date('H:i', strtotime($data['screening']->start_time)) }}</td>
                <td class="px-2 py-2 text-center md:table-cell">{{ $data['seat'] }}</td>
                {{-- @if($showView)
                    <td>
                        <x-table.icon-show class="ps-3 px-0.5"
                        href="{{ route('disciplines.show', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
                @if($showEdit)
                    <td>
                        <x-table.icon-edit class="px-0.5"
                        href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif --}}
                {{-- @if($showDelete)
                    <td>
                        <x-table.icon-delete class="px-0.5"
                        action="{{ route('cart.destroy') }}"/>
                    </td>
                @endif --}}
                {{--
                @if($showAddToCart)
                    <td>
                        <x-table.icon-add-cart class="px-0.5"
                            method="post"
                            action="{{ route('cart.add', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif --}}
                @if($showRemoveFromCart)
                    <td>
                        <x-table.icon-minus class="px-0.5"
                            method="delete"
                            action="{{ route('cart.remove', ['screeningId' => $data['screening']->id, 'seatId' => $data['seat_id']]) }}"/>
                    </td>
                @endif
            </tr>
        @endforeach
        {{-- @foreach ($disciplines as $discipline)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left hidden sm:table-cell">{{ $discipline->abbreviation }}</td>
                <td class="px-2 py-2 text-left">{{ $discipline->name }}</td>
                @if($showCourse)
                    <td class="px-2 py-2 text-left hidden md:table-cell">{{ $discipline->courseRef->name }}</td>
                @endif
                <td class="px-2 py-2 text-right hidden md:table-cell">{{ $discipline->year }}</td>
                <td class="px-2 py-2 text-right hidden md:table-cell">{{ $discipline->semesterDescription }}</td>
                <td class="px-2 py-2 text-right hidden lg:table-cell">{{ $discipline->ECTS }}</td>
                <td class="px-2 py-2 text-right hidden lg:table-cell">{{ $discipline->hours }}</td>
                <td class="px-2 py-2 text-center hidden lg:table-cell">{{ $discipline->optional ? 'optional' : '' }}</td>
                @if($showView)
                    <td>
                        <x-table.icon-show class="ps-3 px-0.5"
                        href="{{ route('disciplines.show', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
                @if($showEdit)
                    <td>
                        <x-table.icon-edit class="px-0.5"
                        href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
                @if($showDelete)
                    <td>
                        <x-table.icon-delete class="px-0.5"
                        action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
                @if($showAddToCart)
                    <td>
                        <x-table.icon-add-cart class="px-0.5"
                            method="post"
                            action="{{ route('cart.add', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
                @if($showRemoveFromCart)
                    <td>
                        <x-table.icon-minus class="px-0.5"
                            method="delete"
                            action="{{ route('cart.remove', ['discipline' => $discipline]) }}"/>
                    </td>
                @endif
            </tr>
        @endforeach --}}
        </tbody>
    </table>
</div>
