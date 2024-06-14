@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="price" label="Price" width="md" :readonly="true" value="{{ $ticket->price }}€" />

        <x-field.input name="status" label="Status" width="md" :readonly="true" value="{{ $ticket->status }}" />

        <x-field.input name="theaters" label="Theaters" width="md" :readonly="true"
            value="{{ $ticket->seat->theater->name }}" />
        @php
            $date = date('Y/m/d', strtotime($ticket->screening->date));
            $time = date('H\hi', strtotime($ticket->screening->start_time));
            $formattedDateTime = $date . ' - ' . $time;
        @endphp

        <x-field.input name="screening_date" label="Screening Date" width="md" :readonly="true"
            value="{{ $formattedDateTime }}" />
        <x-field.input name="movie_title" label="Movie Title" width="md" :readonly="true"
            value="{{ $ticket->screening->movie->title }}" />

        <x-field.input name="seat_row" label="Seat Row" width="md" :readonly="true"
            value="{{ $ticket->seat->row }} {{ $ticket->seat->seat_number }}" />
    </div>

    <div class="grow mt-6 space-y-4 flex flex-col items-center">
        <x-field.input name="customer_name" label="Customer Name" width="md" :readonly="true"
            value="{{ $ticket->purchase->customer_name }}" />

        <div class="pb-6">
            <x-field.image name="image_file" label="Customer Image" width="md" :readonly="$readonly"
                deleteTitle="Delete Image" :deleteAllow="$mode == 'edit' && $ticket->purchase->customer->user->imageExists" deleteForm="form_to_delete_image" :imageUrl="$ticket->purchase->customer->user->imageUrl" />
        </div>

        <!-- Warning message -->
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            <p class="font-bold">Please be advised that before granting entry to any screening session,<br> employees must verify the following criteria:</p>
            <ul class="list-disc ml-8">
                <li>Compliance with age restrictions for the film being screened.</li>
                <li>Sobriety of the individual entering the premises.</li>
                <li>Adherence to any other specific entry requirements as specified.</li>
            </ul>
            <p class="mt-2">Failure to meet any of these criteria constitutes grounds for refusal of entry.</p>
        </div>


        <x-button type="submit" text="Invalidate ticket"
            href="{{ route('tickets.invalidate', ['ticket' => $ticket]) }}" />


    </div>
</div>
