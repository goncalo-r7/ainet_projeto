@extends('layouts.main')

@section('header-title', 'List of Movies')

@section('main')

    <x-movies.filter-card
        :filterAction="route('movies.showcase')"
        :resetUrl="route('movies.showcase')"
        :genres="$genres->pluck('name','name')->toArray()"
        :genre="old('genre', $filterByGenre)"
        :title="request('title')"
        :synopsis="request('synopsis')"
        class="mb-6"
    />
    <div class="flex flex-col">
        @each('movies.shared.card', $movies, 'movie')
    </div>

    <div class="mt-4">
        {{ $movies->links() }}
    </div>
@endsection
