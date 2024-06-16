@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">

        <div class="max-w-[8rem] mx-0">
            <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                Date:</label>

        <div id="date-input-container">
            <div class="relative mb-3">
                <div class="absolute inset-y-0 end-0 top-0 flex items-center pr-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <input datepicker type="text" name="date[]"
                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    value="{{ old('date', $screening->date ?? now()->format('Y-m-d')) }}" required />
            </div>
            <button type="button" id="add-date-input"
                class="bg-blue-500 text-white px-3 py-1 rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Add Date
            </button>
        </div>






        <div class="max-w-[8rem] mx-0">
            <label for="time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                time:</label>

            <div id="time-input-container">
                @foreach (old('start_time', [$screening->start_time]) as $startTime)
                    <div class="relative mb-3">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pr-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input name="start_time[]" type="time"
                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            value="{{ date('H:i', strtotime($startTime)) }}" required />
                    </div>
                @endforeach
            </div>

            <!-- Button to add more time inputs -->
            <button type="button" id="add-time-input"
                class="bg-blue-500 text-white px-3 py-1 rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Add More
            </button>
        </div>



        <x-field.select name="movie_id" label="Movie" :readonly="$readonly"
            value="{{ old('movie_id', $screening->movie_id) }}" :options="$movies" />

        <x-field.select name="theater_id" label="Theater" :readonly="$readonly"
            value="{{ old('theater_id', $screening->theater_id) }}" :options="$theaters" />
    </div>
</div>
<script>
    document.getElementById('add-time-input').addEventListener('click', function() {
        var container = document.getElementById('time-input-container');
        var newInput = document.createElement('div');
        newInput.className = 'relative mb-3';
        var currentTime = new Date();
        var formattedTime = currentTime.getHours() + ':' + ('0' + currentTime.getMinutes()).slice(-
            2); // Example formatting
        newInput.innerHTML = `
        <div class="absolute inset-y-0 end-0 top-0 flex items-center pr-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <input name="start_time[]" type="time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="${formattedTime}" required />
    `;
        container.appendChild(newInput);
    });
</script>
<script>
    document.getElementById('add-date-input').addEventListener('click', function() {
        var container = document.getElementById('date-input-container');
        var newInput = document.createElement('div');
        newInput.className = 'relative mb-3';
        var currentDate = new Date();
        var formattedDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) +
            '-' + ('0' + currentDate.getDate()).slice(-2); // Example formatting
        newInput.innerHTML = `
            <div class="absolute inset-y-0 end-0 top-0 flex items-center pr-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <input datepicker type="text" name="date[]" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="${formattedDate}" required />
        `;
        container.appendChild(newInput);
    });
</script>
