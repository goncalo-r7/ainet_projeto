@extends('layouts.main')

@section('header-title', 'Theater "' . $theater->name . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    <x-button href="{{ route('theaters.create', ['theater' => $theater]) }}" text="New" type="success" />
                    <x-button href="{{ route('theaters.show', ['theater' => $theater]) }}" text="View" type="info" />
                    <form method="POST" action="{{ route('theaters.destroy', ['theater' => $theater]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button element="submit" text="Delete" type="danger" />
                    </form>
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Edit theater "{{ $theater->name }}"
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                        Click on "Save" button to store the information.
                    </p>
                </header>
                {{-- TODO: check what this first form does --}}
                <form method="POST" action="{{ route('theaters.update', ['theater' => $theater]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('theaters.shared.fields', ['mode' => 'edit'])
                    <div class="flex">
                        <h3 class="pb-4 me-5 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Theater Seats
                        </h3>
                    </div>
                    @csrf
                    <div class="flex items-center space-x-2">
                        <span class="flex">
                            <input id="row_insert" type="checkbox" name="row_insert" value="row_insert" class="hidden peer">
                            <label for="row_insert" class="block h-full p-3
                                bg-white border-2 border-gray-200 hover:bg-gray-50 cursor-pointer
                                rounded-lg dark:hover:text-gray-300
                                peer-checked:border-blue-600 peer-checked:bg-blue-200 dark:peer-checked:bg-blue-400  hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600
                                dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                Insert 1 Row
                            </label>
                        </span>
                        <span class="flex">
                            <input id="col_insert" type="checkbox" name="col_insert" value="col_insert" class="hidden peer">
                            <label for="col_insert" class="block h-full p-3
                                bg-white border-2 border-gray-200 hover:bg-gray-50 cursor-pointer
                                rounded-lg dark:hover:text-gray-300
                                peer-checked:border-blue-600 peer-checked:bg-blue-200 dark:peer-checked:bg-blue-400  hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600
                                dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                Insert 1 Column
                            </label>
                        </span>
                    </div>
                                
                    <x-theaters.seats :seatsByRow="$theater->seats->groupBy('row')->toArray()" class="pt-2" />
                    <div class="flex mt-6">
                        <x-button element="submit" type="dark" text="Save" class="uppercase" />
                        <x-button element="a" type="light" text="Cancel" class="uppercase ms-4" href="{{ url()->full() }}" />
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<form class="hidden" id="form_to_delete_photo" method="POST" action="{{ route('theaters.photo.destroy', ['theater' => $theater]) }}">
    @csrf
    @method('DELETE')
</form>
@endsection