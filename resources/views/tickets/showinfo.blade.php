@extends('layouts.main')

@section('header-title', $ticket->id)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>

                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Price "{{ $ticket->price }}"
                    </h2>
                </header>

                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Sessions
                </h3>

            </section>
        </div>
    </div>
</div>
@endsection
