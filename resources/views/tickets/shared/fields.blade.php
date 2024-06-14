@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="price" label="Price" width="md" :readonly="true" value="{{ $ticket->price }}" />

        <x-field.input name="status" label="Status" width="md" :readonly="true" value="{{ $ticket->status }}" />

        <x-field.input name="theaters" label="Theaters" width="md" :readonly="true"
            value="{{ $ticket->seat->theater->name }}" />

        <x-field.input name="screening_date" label="Screening Date" width="md" :readonly="true"
        value="{{ $ticket->screening->date }} {{ $ticket->screening->start_time }}"/>

        <x-field.input name="movie_title" label="Movie Title" width="md" :readonly="true"
            value="{{ $ticket->screening->movie->title }}" />

        <x-field.input name="seat_row" label="Seat Row" width="md" :readonly="true"
            value="{{ $ticket->seat->row }} {{ $ticket->seat->seat_number }}" />



        @if ($ticket->purchase && $ticket->purchase->customer_id)
            <x-field.input name="customer_name" label="Customer Name" width="md" :readonly="true"
                value="{{ $ticket->purchase->customer->user->name }}" />
        @else
            <x-field.input name="customer_name" label="Customer Name" width="md" :readonly="true" value="N/A" />
        @endif



    </div>
