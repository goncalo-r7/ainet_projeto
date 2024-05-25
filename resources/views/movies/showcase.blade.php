@extends('layouts.main')

@section('header-title', 'List of Movies')

@section('main')

    <x-movies.filter-card
    :screenings="$movies->flatMap(function ($movie) {
        return $movie->screeningsRef->map(function ($screening) {
            return ['date' => $screening->date, 'start_time' => $screening->start_time];
        });
    })->toArray()"


        {{-- se sobrar tempo tentar resolver (perguntar ao stor) --}}
        class="mb-6"
    />
                <div class="flex flex-col">
                    @each('movies.shared.card', $movies, 'movie')
                </div>

            <div class="mt-4">
                {{ $movies->links() }}
    </div>
@endsection
