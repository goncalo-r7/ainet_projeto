@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="title" label="Title of Movie" :readonly="$readonly"
            value="{{ old('title', $movie->title) }}"/>

        <x-field.select name="genre_code" label="Genre" :readonly="$readonly"
            value="{{ old('genre_code', $movie->genre_code) }}"
            :options="$genres"/>


        <x-field.input name="year" label="Year" :readonly="$readonly"
                        value="{{ old('year', $movie->year) }}"/>

        @if ($movie->trailer_url)
        <div class="bg-grey-950 text-grey p-4 rounded-lg shadow-md h-96 sm:h-112 md:h-128 lg:h-144">
            <iframe
                class="h-full w-full rounded-lg"
                src="{{ Str::replace('watch?v=', 'embed/', $movie->trailer_url) }}"
                width="100%"
                height="100%"
                title="Trailer of movie"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>
        </div>
        @endif
        @if ($mode == 'edit' || $mode == 'create' )
        <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
            <p class="font-bold">Attention:</p>
            <p>Trailer URL will update only after confirmation on save.</p>
        </div>
        <div class="mt-4">
            <x-field.input name="trailer_url" label="Trailer URL"
                value="{{ old('trailer_url', $movie->trailer_url) }}"/>
        </div>
        @endif
        <x-field.text-area name="synopsis" label="Synopsis"
            :readonly="$readonly"
            value="{{ old('synopsis', $movie->synopsis) }}"/>

    </div>
    <div class="pb-6">
        <x-field.image
            name="poster_filename"
            label="Movie Image"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Image"
            :deleteAllow="($mode == 'edit') && ($movie->imageExists)"
            deleteForm="form_to_delete_image"
            :imageUrl="$movie->imageUrl"/>
    </div>

</div>
