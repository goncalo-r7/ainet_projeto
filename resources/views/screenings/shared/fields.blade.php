@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">


        <div class="relative max-w-sm">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                </svg>
            </div>
            <input datepicker type="text" name="date" value="{{ old('date', $screening->date) }}" id="id_date"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Select date" {{ $readonly ? 'readonly' : '' }}>
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
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input name="start_time[]" type="time"
                               class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               value="{{ date('H:i', strtotime($startTime)) }}" {{ $readonly ? 'readonly' : '' }}  required />
                    </div>
                @endforeach
            </div>

            @if($mode=='edit')
            <button type="button" id="add-time-input"
                class="bg-blue-500 text-white px-3 py-1 rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Add More
            </button>
            @endif

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
    newInput.innerHTML = `
        <div class="absolute inset-y-0 end-0 top-0 flex items-center pr-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
        <input name="start_time[]" type="time" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
    `;
    container.appendChild(newInput);
});
</script>
