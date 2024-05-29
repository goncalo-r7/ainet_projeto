@extends('layouts.main')

@section('header-title',  $screeningSession->movie->title . 
' - Sessão das ' . date('H:i', strtotime($screeningSession->start_time)) . ', ' . date('d-m-Y', strtotime($screeningSession->start_time)))

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
            
            
            {{-- <form id="seat-form" method="POST" action="/submit-seats">
                <x-button type="submit" class="mt-4 px-4 py-2" text='Comprar' type='primary'></x-button>
                <div class="flex flex-col gap-4 mb-4 overflow-auto">
                @foreach ($seatsByRow as $row => $seats)
                    <div class="flex items-center gap-4">
                        @foreach ($seats as $seat)
                            @php
                                $isTaken = $screeningSession->tickets->where('seat_id', $seat->id)->isNotEmpty();
                            @endphp
                            
                            <label class="flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    name="selectedSeats[]"
                                    value="{{ $seat->id }}"
                                    class="seat-checkbox 
                                    {{ $isTaken ? 'cursor-not-allowed' : ''}}"
                                    {{ $isTaken ? ' checked ' : '' }}
                                    {{ $isTaken ? ' disabled ' : ''}}
                                >
                                <span>{{ $seat->row . $seat->seat_number }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
                </div>
            </form> --}}
            
            {{-- when the form is submitted, the IDs of the selected seats will be sent as an array with the name "selectedSeats" --}}
            {{-- action="{{ route('cart.add', ['discipline' => $discipline]) }}" --}}
            <form method="POST" action="">
                <x-button type="submit" class="mt-4 px-4 py-2" text='Comprar' type='primary'/>
                <table class="table-auto border-collapse">
                    <div class="flex flex-col gap-4 mb-4 overflow-auto">
                    @foreach ($seatsByRow as $row => $seats)
                        <div class="flex items-center gap-4">
                            <tr class="flex items-center space-x-2">
                            @foreach ($seats as $seat)
                                @php
                                    $isTaken = $screeningSession->tickets->where('seat_id', $seat->id)->isNotEmpty();
                                @endphp
                                
                                <td class="px-2 py-2 text-left hidden sm:table-cell text-gray-900 dark:text-gray-100">
                                <input
                                    type="checkbox"
                                    name="selectedSeats[]"
                                    value="{{ $seat->id }}"
                                    class="{{ $isTaken ? 'cursor-not-allowed' : ''}}"
                                    {{ $isTaken ? ' checked ' : '' }}
                                    {{ $isTaken ? ' disabled ' : ''}}
                                >
                                <span>{{ $seat->row . $seat->seat_number }}</span>
                                </td>                           
                            @endforeach
                        </tr>
                        </div>
                    @endforeach
                    </div>
                </table>
            </form>
            {{-- <x-button
                                class="flex h-10 w-14"
                                text="{{ $seat->row . $seat->seat_number }}"    
                                type="{{ $isTaken ? 'secondary' : 'success'}}"
                                
                                /> --}}
           
            
        </div>
    </div>
@endsection