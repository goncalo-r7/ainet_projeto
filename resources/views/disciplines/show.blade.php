@extends('layouts.main')

@section('header-title', $discipline->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('disciplines.create', ['discipline' => $discipline]) }}"
                        text="New"
                        type="success"/>
                    <x-button
                        href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}"
                        text="Edit"
                        type="primary"/>
                    <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                    <form method="POST" action="{{ route('cart.add', ['discipline' => $discipline]) }}">
                        @csrf
                        <x-button
                            element="submit"
                            text="Add to cart"
                            type="dark"/>
                    </form>
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Discipline "{{ $discipline->name }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('disciplines.shared.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Teachers
                </h3>
                <x-teachers.table :teachers="$discipline->teachers"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    class="pt-4"
                    />
            </section>
        </div>
    </div>
</div>
@endsection
