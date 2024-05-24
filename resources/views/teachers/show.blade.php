@extends('layouts.main')

@section('header-title', 'Teacher "' . $teacher->user->name . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('teachers.create', ['teacher' => $teacher]) }}"
                        text="New"
                        type="success"/>
                    <x-button
                        href="{{ route('teachers.edit', ['teacher' => $teacher]) }}"
                        text="Edit"
                        type="primary"/>
                    <form method="POST" action="{{ route('teachers.destroy', ['teacher' => $teacher]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Teacher "{{ $teacher->user->name }}"
                    </h2>
                </header>
                @include('teachers.shared.fields', ['mode' => 'show'])

                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Disciplines
                </h3>
                <x-disciplines.table :disciplines="$teacher->disciplines"
                    :showView="true"
                    :showEdit="false"
                    :showDelete="false"
                    :showAddToCart="true"
                    class="pt-4"
                    />

            </section>
        </div>
    </div>
</div>
@endsection
