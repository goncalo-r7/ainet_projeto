<div {{ $attributes }}>
<div class="overflow-auto">
                    @if(sizeof($seatsByRow) != 0)
                    <table class="table-auto border-collapse w-full">
                        @foreach ($seatsByRow as $row => $seats)
                            <tr class="items-center space-x-1">
                                @foreach ($seats as $seat)
                                    <td class="px-1 py-1 text-center">
                                    
                                    <label class="block h-full p-2
                                        bg-white border-2 border-gray-200 rounded-lg
                                        dark:text-gray-400 dark:bg-gray-800">
                                        {{ $seat['row'] . $seat['seat_number'] }}
                                    </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="px-2 py-2 text-center sm:table-cell text-gray-900 dark:text-gray-100" colspan="2">
                    <img src="{{ url('storage/theaters/unavailable.svg') }}" class="mx-auto w-16">
                    <br>
                    <p class="text-center text-gray-900 dark:text-gray-100 font-bold">NO SEATS AVAILABLE FOR THIS THEATRE</p>
                </div>
                @endif
                </div>
</div>
