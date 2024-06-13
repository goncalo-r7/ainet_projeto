@extends('layouts.main')

@section('header-title', 'Verify ticket')

@section('main')
    <div class="flex justify-center">
        <div
            class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">

{{-- //atencao luis meter isto com taiwlind e como deve ser, isto foi o chatgpt que deu --}}

            <form action="{{ route('tickets.verify') }}" method="GET">
                <label for="qrcode_url">QR Code URL:</label>
                <input type="text" id="qrcode_url" name="qrcode_url" required>
                <br>
                <label for="screening_id">Screening ID:</label>
                <input type="number" id="screening_id" name="screening_id" required>
                <br>
                <button type="submit">Verify Ticket</button>
            </form>
        </div>
    </div>
@endsection
