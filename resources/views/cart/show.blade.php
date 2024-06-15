@extends('layouts.main')

@section('header-title', 'Shopping Cart')

@section('main')
@php use App\Models\Customer; @endphp
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @empty($cart)
                <h3 class="text-xl w-96 text-center">Cart is Empty</h3>
            @else
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-tickets.table :tickets="$cart"
                    :showView="true"
                    {{-- :showEdit="false"
                    :showDelete="false"
                    :showAddCart="false" --}}
                    :showRemoveFromCart="true"
                    />
            </div>
            <div class="mt-12">
                <div class="flex justify-between space-x-12 items-end">
                    <div>
                        @php
                        $user = Auth::user();
                        $customerId = Customer::where('id', $user->id)->first();
                        $conf = DB::table('configuration')->first();
                        $price = $conf->ticket_price * count($cart);
                        $isCustomer = false;
                        if ($customerId) {
                            $isCustomer = true;
                            $discount = $conf->registered_customer_ticket_discount * count($cart);
                            $customerName = $user->name;
                            $customerEmail = $user->email;
                            $customerNif = $customerId->nif;
                            $paymentType = $customerId->payment_type;
                            $paymentRef = $customerId->payment_ref;
                        }
                        @endphp
                        <h3 class="mb-4 text-xl"><b>Shopping Cart Confirmation</b></h3>
                        <form action="{{ route('cart.confirm') }}" method="post">
                            @csrf
                                <h4 class="mb-4 text-lg">Price:
                                @php
                                    if ($isCustomer) {
                                        echo "<s>" . $price . "</s> " . ($price - $discount);
                                    } else {
                                        echo $price;
                                    }
                                @endphp €</h4>
                                <x-field.input name="customer_name" label="Name" width="lg"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerName : old('customer_name') }}"/>
                                <x-field.input name="customer_email" label="E-Mail" width="lg"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerEmail : old('customer_email') }}"/>
                                <x-field.input name="customer_nif" label="NIF (optional)" width="sm"
                                                :readonly="$isCustomer"
                                                value="{{ $isCustomer ? $customerNif : old('customer_nif') }}"/><br>
                                <h3 class="mb-4 text-xl">Payment Details</h3>
                                <x-field.radio-group name="payment_type" label="Payment Type" width="lg"
                                                    :options="['VISA' => 'Visa Card',
                                                               'PAYPAL' => 'PayPal',
                                                               'MBWAY' => 'MB Way']" 
                                                    :value="$paymentType ?? old('payment_type')"/>
                                                    {{-- if not null, value is $customerPaymentType --}}
                                <x-field.input name="payment_ref" label="Payment Reference" width="md"
                                                :readonly="false"
                                                value="{{ $paymentRef ?? old('payment_ref') }}"/>
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
