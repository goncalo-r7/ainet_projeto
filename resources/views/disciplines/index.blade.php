@extends('layouts.main')

@section('header-title', 'List of Disciplines')

@section('main')
    <p>
        <a href="{{ route('disciplines.create') }}">Create a new discipline</a>
    </p>
    <table>
        <thead>
            <tr>
                <th>Abbreviation</th>
                <th>Name</th>
                <th>Course</th>
                <th>Year</th>
                <th>Semester</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disciplines as $discipline)
                <tr>
                    <td>{{ $discipline->abbreviation }}</td>
                    <td>{{ $discipline->name }}</td>
                    <td>{{ $discipline->course }}</td>
                    <td>{{ $discipline->year }}</td>
                    <td>{{ $discipline->semester }}</td>
                    <td>
                        <a href="{{ route('disciplines.show', ['discipline' => $discipline]) }}">View</a>
                    </td>
                    <td>
                        <a href="{{ route('disciplines.edit', ['discipline' => $discipline]) }}">Update</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('disciplines.destroy', ['discipline' => $discipline]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" name="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
