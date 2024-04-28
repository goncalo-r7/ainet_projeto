@extends('layouts.main')

@section('header-title', 'New Discipline')

@section('main')
    <form method="POST" action="{{ route('disciplines.store') }}">
        @csrf
        @include('disciplines.shared.fields')
        <div>
            <button type="submit" name="ok">Save new discipline</button>
        </div>
    </form>
@endsection
