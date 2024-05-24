@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp
<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="genre_code" label="Genre_code" width="md"
                        :readonly="$readonly || ($mode == 'edit')"
                        value="{{ old('genre_code', $movie->genre_code) }}"/>
        <x-field.radio-group name="title" label="Title of Movie" :readonly="$readonly"
                        value="{{ old('type', $movie->title) }}"
                        :options="[
                            'Degree' => 'Degree',
                            'Master' => 'Master',
                            'TESP' => 'TESP'
                        ]"/>
        <x-field.input name="title" label="Title" :readonly="$readonly"
                        value="{{ old('title', $movie->title) }}"/>
        <x-field.input name="title" label="Title (Portuguese)" :readonly="$readonly"
                    value="{{ old('title', $movie->title) }}"/>

    </div>

</div>
