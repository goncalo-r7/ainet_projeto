@extends('layouts.main')

@section('header-title', 'List of Tickets')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <x-searchbar
                :filterAction="route('tickets.index')"
                :resetUrl="route('tickets.index')"
                :filter="$filter"
                name="ticket"
                label="Ticket"
                class="mb-6"
                />
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
            <x-tickets.tickets-table :tickets="$tickets"/>
            </div>
        </div>
    </div>
    @if(sizeof($tickets) != 0)
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
    @endif
@endsection
