@extends('layouts.main')

@section('header-title', $course->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    <x-button
                        href="{{ route('courses.create', ['course' => $course]) }}"
                        text="New"
                        type="success"/>
                    <x-button
                        href="{{ route('courses.edit', ['course' => $course]) }}"
                        text="Edit"
                        type="primary"/>
                    <form method="POST" action="{{ route('courses.destroy', ['course' => $course]) }}">
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
                        Course "{{ $course->name }}"
                    </h2>
                </header>
                <div class="mt-6 space-y-4">
                    @include('courses.shared.fields', ['mode' => 'show'])
                </div>
                <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                    Curriculum
                </h3>
                <x-courses.curriculum :disciplines="$course->disciplines"
                    :showView="true"
                    class="pt-4"
                    />
            </section>
        </div>
    </div>
</div>
@endsection
