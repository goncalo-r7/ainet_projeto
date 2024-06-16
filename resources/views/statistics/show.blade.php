@extends('layouts.main')

@section('header-title', 'Statistics')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('main')


    <div class="flex flex-col space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="max-full">
                <section>

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Statistics
                        </h2>
                    </header>

                    <h1>Statistics</h1>
                    <p>Number of sessions today: {{ $numSessionsToday }}</p>


                    @include('statistics.graphs.graphbar')

                </section>
            </div>
        </div>
    </div>
@endsection
