@extends('layouts.main')

@section('header-title', 'List of Departments')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="flex items-center gap-4 mb-4">
                <x-button
                    href="{{ route('departments.create') }}"
                    text="Create a new department"
                    type="success"/>
            </div>
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <table class="table-auto border-collapse">
                    <thead>
                    <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                        <th class="px-2 py-2 text-left hidden lg:table-cell">Abbreviation</th>
                        <th class="px-2 py-2 text-left">Name</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($departments as $department)
                        <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                            <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $department->abbreviation }}</td>
                            <td class="px-2 py-2 text-left">{{ $department->name }}</td>
                            <td>
                                <x-table.icon-show class="ps-3 px-0.5"
                                   href="{{ route('departments.show', ['department' => $department]) }}"/>
                            </td>
                            <td>
                                <x-table.icon-edit class="px-0.5"
                                   href="{{ route('departments.edit', ['department' => $department]) }}"/>
                            </td>
                            <td>
                                <x-table.icon-delete class="px-0.5"
                                   action="{{ route('departments.destroy', ['department' => $department]) }}"/>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
@endsection
