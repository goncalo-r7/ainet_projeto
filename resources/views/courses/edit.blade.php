@extends('layouts.main')

@section('header-title', 'Update Course "' . $course->name . '"')

@section('main')
    <form method="POST" action="{{ route('courses.update', ['course' => $course]) }}">
        @csrf
        @method('PUT')
        @include('courses.shared.fields')
        <div>
            <button type="submit" name="ok">Save course</button>
        </div>
    </form>
@endsection

