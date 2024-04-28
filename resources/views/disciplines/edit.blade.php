@extends('layouts.main')

@section('header-title', 'Update Discipline "' . $discipline->name . '"')

@section('main')
    <form method="POST" action="{{ route('disciplines.update', ['discipline' => $discipline]) }}">
        @csrf
        @method('PUT')
        @include('disciplines.shared.fields')
        <div>
            <button type="submit" name="ok">Save discipline</button>
        </div>
    </form>
@endsection
