<div>
    <figure
        class="h-auto md:h-72 flex flex-col md:flex-row rounded-none sm:rounded-xl bg-white dark:bg-gray-900 my-4 p-8 md:p-0">
        <a class="h-48 w-48 md:h-72 md:w-72 md:min-w-72 md:max-w-72 mx-auto md:m-0"
            href="{{ route('movies.show', ['movie' => $movie]) }}">
            <img class="h-full aspect-auto mx-auto rounded-full md:rounded-l-xl md:rounded-r-none"
                src="{{ $movie->imageUrl }}">
        </a>
        <div class=" h-auto p-6 text-center md:text-left space-y-1 flex flex-col md:flex-row md:justify-end flex-grow max-h-[400px]">
            <div class="flex-grow mr-4">
                <a class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight"
                    href="{{ route('movies.show', ['movie' => $movie]) }}">
                    {{ $movie->title }}
                </a>
                <figcaption class="font-medium">
                    <div class="flex justify-center md:justify-start font-base text-base space-x-6 text-gray-700 dark:text-gray-300">
                        <div>{{ $movie->genre->name }}</div>
                        <div>Year: {{ $movie->year }} </div>
                    </div>
                </figcaption>
                <div class="overflow-y-auto">
                    <p class="pt-4 font-light text-gray-700 dark:text-gray-300">
                        {{ $movie->synopsis }}
                    </p>
                </div>
            </div>
            <div class="h-full flex flex-col"> <!-- Add these classes here -->
                <figcaption class="font-">
                    <div
                        class="text-center md:text-left font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                        Next Sessions
                    </div>
                </figcaption>
                <div class="overflow-y-auto w-full md:w-48 h-full ">
                    <figcaption class="font-">

                        @php
                            $screeningsByDate = [];
                            foreach ($movie->screeningsRef as $screening) {
                                $date = new DateTime($screening['date']);
                                $formattedDate = $date->format('d') . ' ' . $date->format('F');
                                $time = (new DateTime($screening['start_time']))->format('H\hi');

                                if (!isset($screeningsByDate[$formattedDate])) {
                                    $screeningsByDate[$formattedDate] = [];
                                }
                                $screeningsByDate[$formattedDate][] = $time;
                            }
                        @endphp

                        @foreach ($screeningsByDate as $formattedDate => $times)
                            <div class="space-y-1">
                                <div class="font-bold text-gray-700 dark:text-gray-300">{{ $formattedDate }}</div>
                                <div
                                    class="flex flex-col justify-center md:justify-start font-base text-base text-gray-700 dark:text-gray-300">
                                    @foreach ($times as $time)
                                        <div>{{ $time }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </figcaption>
                </div>
            </div>
        </div>
    </figure>
</div>
