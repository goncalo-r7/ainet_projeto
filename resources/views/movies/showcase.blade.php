@extends('layouts.main')

@section('header-title', 'List of Movies')

@section('main')

    <x-movies.filter-card
        :screenings="$screenings->pluck('date', 'movie_id')->toArray()"
        class="mb-6"
    />
                <div class="flex flex-col">
                    @each('movies.shared.card', $movies, 'movie')
                </div>

            <div class="mt-4">
                {{ $movies->links() }}
    </div>
@endsection
