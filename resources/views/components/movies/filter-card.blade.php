<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <div>
                    <x-field.select name="genre" label="Genre"
                        value="{{ $genre }}"
                        :options="$listGenres"/>
                </div>
                <div>
                    <x-field.input name="title" label="Title" class="grow"
                        value="{{ $title }}"/>
                </div>
                <div>
                    <x-field.input name="synopsis" label="Synopsis" class="grow"
                        value="{{ $synopsis }}"/>
                </div>
                @can('filter', App\Models\Movie::class)
                <div class="mt-4">
                    <label class="inline-flex items-center ">
                        <input type="checkbox" name="allMoviesBool" {{ $allMoviesBool ? 'checked' : '' }} class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">All Movies</span>
                    </label>
                </div>
                @endcan
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Cancel" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
