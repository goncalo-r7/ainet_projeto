@extends('layouts.main')

@section('header-title', $screeningSession->movie->title .
' - Screening at ' . date('H:i', strtotime($screeningSession->start_time)) . ', ' . date('d-m-Y', strtotime($screeningSession->date)) .
' - Theater ' . $screeningSession->theater->name)

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @csrf
            {{-- when the form is submitted, the IDs of the selected seats will be sent as an array with the name "selectedSeats" --}}
            <form method="POST" action="{{ route('cart.add', ['screening' => $screeningSession]) }}">
                @csrf
                <div class="flex justify-center items-center">
                    <x-button element="submit" class="mt-4 px-4 py-2" text='Adicionar ao carrinho' type='primary'/>
                </div>
                <div class="overflow-auto">
                    <table class="table-auto border-collapse w-full">
                        @foreach ($seatsByRow as $row => $seats)
                            <tr class="items-center space-x-1">
                                @foreach ($seats as $seat)
                                    @php
                                        $isTaken = $screeningSession->tickets->where('seat_id', $seat->id)->isNotEmpty();
                                        $isInCart = false;
                                        if ($cart){
                                            $isInCart = $cart->contains(function ($item) use ($seat, $screeningSession) {
                                                return $item['screening_id'] == $screeningSession->id && $item['seat_id'] == $seat->id;
                                            });
                                        }
                                    @endphp
                                    <td class="px-1 py-1 text-center">

                                    <input
                                        id="{{ $seat->id }}"
                                        type="checkbox"
                                        name="selectedSeats[{{ $seat->id }}]"
                                        value="{{ $seat->id }}"
                                        class="hidden peer"
                                        {{ $isTaken ? 'disabled' : '' }}
                                    >
                                    <label for="{{ $seat->id }}" class="block w-full h-full p-3
                                        {{ $isTaken ? 'bg-red-400 border-red-500 hover:bg-red-500 cursor-not-allowed' : '' }}
                                        {{ !$isTaken && !$isInCart ? 'bg-white border-2 border-gray-200 hover:bg-gray-50 cursor-pointer' : ''}}
                                        rounded-lg dark:hover:text-gray-300
                                        peer-checked:border-blue-600 peer-checked:bg-blue-200 dark:peer-checked:bg-blue-400  hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600
                                        dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700
                                        {{ $isInCart ? 'bg-gray-400 border-gray-300 hover:bg-gray-500 dark:bg-gray-500 dark:border-gray-200 dark:hover:bg-red-400' : '' }}">
                                        {{ $seat->row . $seat->seat_number }}
                                    </label>

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
