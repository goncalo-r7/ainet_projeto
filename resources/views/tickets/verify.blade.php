@extends('layouts.main')

@section('header-title', 'Verify ticket')

@section('main')
    <div class="flex justify-center">
        <div
            class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">

            <h3 class="mb-4 text-xl">Verify Ticket for Screening: {{ $screening->id }} </h3>
            <form action="{{ route('tickets.verify', ['screening' => $screening->id]) }}" method="POST">
                @csrf

                <!-- Qrcode URL -->
                <div>
                    <x-input-label for="qrcode_url" :value="__('QRCode URL')" />
                    <x-text-input id="qrcode_url" class="block mt-1 w-full" type="text" name="qrcode_url" :value="old('qrcode_url')"
                        required autofocus />
                    <x-input-error :messages="$errors->get('qrcode_url')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-start mt-4">
                    <x-primary-button>
                        {{ __('Verify Ticket') }}
                    </x-primary-button>
                </div>
            </form>



        </div>
    </div>


@endsection
