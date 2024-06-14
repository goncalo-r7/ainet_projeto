<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">

                <div>
                    <x-field.input name="id" label="Id of Screening" class="grow"
                        value="{{ $id }}"/>
                </div>
                <div>
                    <x-field.input name="movie" label="Movie title" class="grow"
                        value="{{ $movie }}"/>
                </div>
                <div>
                    <x-field.input name="theater" label="Theater name" class="grow"
                        value="{{ $theater }}"/>
                </div>
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
