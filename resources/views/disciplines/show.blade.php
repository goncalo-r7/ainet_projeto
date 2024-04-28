@extends('layouts.main')

@section('header-title', 'Discipline "' . $discipline->name . '"')

@section('main')
    <div>
        @include('disciplines.shared.fields', ['readonlyData' => true])
    </div>
@endsection
