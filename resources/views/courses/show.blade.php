@extends('layouts.main')

@section('header-title', 'Course "' . $course->name . '"')

@section('main')
    <div>
        @include('courses.shared.fields', ['readonlyData' => true])
    </div>
@endsection
