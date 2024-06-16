<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">ID</th>
            <th class="px-2 py-2 text-left">Theater name</th>
            <th class="px-2 py-2 text-left">Seat</th>
            <th class="px-2 py-2 text-left">Movie</th>
            <th class="px-2 py-2 text-left">Date</th>
            <th class="px-2 py-2 text-left">Status</th>
            <th class="px-2 py-2 text-left">Price</th>
        </tr>
        </thead>
        <tbody>
        @if(sizeof($tickets) != 0)
        @foreach ($tickets as $ticket)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{$ticket->id ?? "UNKOWN ID"}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->seat->theater->name ?? "UNKOWN THEATER"}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->seat->row}}{{$ticket->seat->seat_number ?? "UNKOWN SEAT"}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->screening->movie->title ?? "UNKOWN MOVIE"}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->screening->date ?? "UNKOWN DATE"}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->status}}</td>
                <td class="px-2 py-2 text-left">{{$ticket->price ?? "UNKNOWN PRICE"}} €</td>
                <td>
            <x-table.icon-download class="ps-3 px-0.5"
                        href="{{ route('tickets.download', ['ticket' => $ticket]) }}"/>
                    </td>
            </tr>
        @endforeach
        @else
        <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
        <td colspan="6"><div class="text-center table-cell text-gray-900 dark:text-gray-100">
                    <img src="{{ url('storage/theaters/unavailable.svg') }}" class="mx-auto w-16">
                    <br>
                    <p class="text-center text-gray-900 dark:text-gray-100 font-bold">NO TICKETS AVAILABLE FOR THIS ID</p>
                </div>
                </td>
                </tr>
        @endif
        
        </tbody>
    </table>
</div>
