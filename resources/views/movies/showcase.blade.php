@extends('layouts.main')

@section('header-title', 'List of Movies')

@section('main')
    <div class="flex flex-col">
        @each('movies.shared.card', $movies, 'movie')
    </div>

     <!-- Links de Paginação -->
     <div class="mt-4">
        {{ $movies->links() }}
    </div>
@endsection
