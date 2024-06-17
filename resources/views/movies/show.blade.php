@extends('layouts.main')

@section('header-title', $movie->title)

@section('main')


<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create',App\Models\Movie::class)
                    <x-button
                        href="{{ route('movies.create', ['movie' => $movie]) }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('edit',$movie)
                    <x-button
                        href="{{ route('movies.edit', ['movie' => $movie]) }}"
                        text="Edit"
                        type="primary"/>
                    @endcan
                    @can('delete',$movie)
                    <form method="POST" action="{{ route('movies.destroy', ['movie' => $movie]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                    @endcan
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Movie "{{ $movie->title }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('movies.shared.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Sessions
                </h3>
                <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                    <x-screenings.table :screenings="$screenings"
                    :screeningSoldOut="$screeningSoldOut"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    :showSeat="true"
                    :showMovie="false"
                    />
                </div>


            </section>
        </div>
    </div>
</div>
@endsection
