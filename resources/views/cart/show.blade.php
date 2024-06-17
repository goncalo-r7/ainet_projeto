@extends('layouts.main')

@section('header-title', 'Shopping Cart')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @empty($cart)
                <h3 class="text-xl w-96 text-center">Cart is Empty</h3>
            @else
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-tickets.table :ticketdata="$ticketdata"
                    :showView="true"
                    :showRemoveFromCart="true"
                    />
            </div>
            <div class="mt-12">
                <div class="flex justify-between space-x-12 items-end">
                    <div>
                        <h4 class="mb-4 text-lg">Price:
                            @php
                                if ($isCustomer) {
                                    echo "<s>" . $price . "</s> " . ($price - $discount);
                                } else {
                                    echo $price;
                                }
                            @endphp €</h4>
                        <h3 class="mb-4 text-xl"><b>Shopping Cart Confirmation</b></h3>
                        <form action="{{ route('cart.confirm') }}" method="post">
                            @csrf
                                {{-- attributes with readonly won't be sent --}}
                                <x-field.input name="customer_name" label="Name" width="lg"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerData['name'] : old('customer_name') }}"/>
                                <x-field.input name="customer_email" label="E-Mail" width="lg"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerData['email'] : old('customer_email') }}"/>
                                <x-field.input name="customer_nif" label="NIF (optional)" width="sm"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerData['nif'] : old('customer_nif') }}"/><br>

                                <h3 class="mb-4 text-xl">Payment Details</h3>
                                <x-field.radio-group name="payment_type" label="Payment Type" width="lg"
                                                    :options="['VISA' => 'Visa Card',
                                                               'PAYPAL' => 'PayPal',
                                                               'MBWAY' => 'MB Way']" 
                                                    value="{{ $isCustomer ? $customerData['payment_type'] : old('payment_type') }}"/>
                                <x-field.input name="payment_ref" label="Payment Reference" width="md"
                                                :readonly="false"
                                                value="{{ $isCustomer ? $customerData['payment_ref'] : old('payment_ref') }}"/>
                                <x-button element="submit" type="dark" text="Confirm" class="mt-4"/>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('cart.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-button element="submit" type="danger" text="Clear Cart" class="mt-4"/>
                        </form>
                    </div>
                </div>
            </div>
            @endempty
        </div>
    </div>
@endsection
