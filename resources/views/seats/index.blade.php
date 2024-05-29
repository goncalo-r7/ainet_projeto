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

            <div class="flex flex-col gap-4 mb-4 overflow-auto">
            @foreach ($seatsByRow as $row => $seats)
                <div class="flex items-center gap-4">
                    @foreach ($seats as $seat)
                        @php
                            $isTaken = $screeningSession->tickets->where('seat_id', $seat->id)->isNotEmpty();
                        @endphp
                        <x-button
                            class="flex h-10 w-14"
                            text="{{ $seat->row . $seat->seat_number }}"    
                            type="{{ $isTaken ? 'secondary' : 'success'}}"
                            
                            />
                    @endforeach
                </div>
            @endforeach
            </div>


            {{-- <div class="flex items-center gap-4 mb-4">
                @for ($i = 0; $i < $seatsByRow; $i++)            
                    @foreach ($screeningSession->theater->seats as $seat)
                        <x-button 
                            text="{{ $seat->row . $seat->seat_number }}"    
                            type="success"/>
                    @endforeach
                    <br>
                @endfor
            </div> --}}
            
        </div>
    </div>
@endsection