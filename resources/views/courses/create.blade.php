@extends('layouts.main')

@section('header-title', 'New Course')

@section('main')
    <form method="POST" action="{{ route('courses.store') }}">
        @csrf
        @include('courses.shared.fields')
        <div>
            <button type="submit" name="ok">Save new course</button>
        </div>
    </form>
@endsection
