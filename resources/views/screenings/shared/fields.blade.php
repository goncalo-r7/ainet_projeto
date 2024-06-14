@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="date" label="Date" :readonly="$readonly"
            value="{{ old('date', $screening->date) }}"/>
{{-- fazer isto para escolher os movies
        <x-field.select name="genre_code" label="Genre" :readonly="$readonly"
            value="{{ old('genre', $movie->genre_code) }}"
            :options="$genres"/> --}}


        <x-field.input name="start_time" label="Start time" :readonly="$readonly"
                        value="{{ old('start_time', $screening->start_time) }}"/>




</div>
