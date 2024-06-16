@extends('layouts.main')

@section('header-title', 'List of Theaters')


@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <x-searchbar
                :filterAction="route('theaters.index')"
                :resetUrl="route('theaters.index')"
                :filter="$filter"
                name="theater"
                label="Theater"
                class="mb-6"
                />
            <div class="flex items-center gap-4 mb-4">
                <x-button
                    href="{{ route('theaters.create') }}"
                    text="Insert a new theater"
                    type="success"/>
            </div>
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
            <x-theaters.table :theaters="$theaters"
                :showView="true"
                :showEdit="true"
                />
            </div>
        </div>
    </div>
    @if(sizeof($theaters) != 0)
    <div class="mt-4">
        {{ $theaters->links() }}
    </div>
    @endif
@endsection
