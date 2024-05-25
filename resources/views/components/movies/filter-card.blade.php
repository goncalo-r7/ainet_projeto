<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                <!-- Add filters specific to movies and screenings -->
                <div>
                    <x-field.select name="title" label="Title"
                        value="{{ $title }}"
                        :options="$listTitles"/>
                </div>
                <div>
                    <x-field.input name="date" label="Date" class="grow"
                        value="{{ $date }}"/>
                </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Reset" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
