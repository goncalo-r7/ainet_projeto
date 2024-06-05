@extends('layouts.main')

@section('header-title',  $screeningSession->movie->title .
' - Sessão das ' . date('H:i', strtotime($screeningSession->start_time)) . ', ' . date('d-m-Y', strtotime($screeningSession->start_time))
. ' - Sala ' . $screeningSession->theater->name)

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @php
                // Group seats by row
                $seatsByRow = $screeningSession->theater->seats->groupBy('row');
            @endphp
            @csrf
            <!-- Ao submeter, criar tickets e adicionar ao carrinho-->


            {{-- when the form is submitted, the IDs of the selected seats will be sent as an array with the name "selectedSeats" --}}
            {{-- action="{{ route('cart.add', ['discipline' => $discipline]) }}" --}}
            <form method="POST" action="{{ route('cart.add', ['screening' => $screeningSession]) }}">
                @csrf
                <div class="flex justify-center items-center">
                    <x-button element="submit" class="mt-4 px-4 py-2" text='Adicionar ao carrinho' type='primary'/>
                </div>
                <div class="overflow-auto">
                    <table class="table-auto border-collapse">
                        <div class="flex flex-col gap-4 mb-4">
                        @foreach ($seatsByRow as $row => $seats)
                            <div class="flex items-center gap-4">
                                <tr class="flex items-center space-x-2">
                                @foreach ($seats as $seat)
                                    @php
                                        $isTaken = $screeningSession->tickets->where('seat_id', $seat->id)->isNotEmpty();
                                    @endphp
                                    <td class="px-2 py-2 text-left hidden sm:table-cell text-gray-900 dark:text-gray-100">


                                    <input
                                        id="{{ $seat->id }}"
                                        type="checkbox"
                                        name="selectedSeats[{{ $seat->id }}]"
                                        value="{{ $seat->id }}"
                                        class="hidden peer"
                                        {{ $isTaken ? 'disabled' : '' }}
                                    > 
                                    <label for="{{ $seat->id }}" class="inline-flex items-center justify-between w-full p-5 
                                        {{ $isTaken ? 'bg-red-400 border-red-500 hover:bg-red-500 cursor-not-allowed' : 'bg-white border-2 border-gray-200 hover:bg-gray-50 cursor-pointer' }} 
                                        rounded-lg dark:hover:text-gray-300     
                                        peer-checked:border-blue-600 peer-checked:bg-blue-200  hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 
                                        dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                        {{ $seat->row . $seat->seat_number }}
                                    </label>

                                    </td>
                                @endforeach
                            </tr>
                            </div>
                        @endforeach
                        </div>
                    </table>
                </div>
            </form>
            {{-- <x-button
                                class="flex h-10 w-14"
                                text="{{ $seat->row . $seat->seat_number }}"
                                type="{{ $isTaken ? 'secondary' : 'success'}}"

                                /> --}}


        </div>
    </div>
@endsection
