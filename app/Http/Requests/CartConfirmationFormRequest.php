<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\Payment;

class CartConfirmationFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'customer_name' => [
                Rule::requiredIf(function () {
                    $isCustomer = Customer::find(Auth::id());
                    return !$isCustomer;
                }),
                'string',
                'max:80',
            ],
            'customer_email' => [
                Rule::requiredIf(function () {
                    $isCustomer = Customer::find(Auth::id());
                    $isCustomer = DB::table('customers')->where('id', Auth::id())->first();
                    return !$isCustomer;
                }),
                'email',
                'max:80',
            ],
            'customer_nif' => 'nullable|digits:9',
            'payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',

        ];

        if ($this->payment_type === 'VISA') {
            $rules['payment_ref'] = 'required|string|digits:19'; // 16 digits + 3 CVC
        } elseif ($this->payment_type === 'PAYPAL') {
            $rules['payment_ref'] = 'required|email';
        } elseif ($this->payment_type === 'MBWAY') {
            $rules['payment_ref'] = 'required|integer|regex:/^9\d{8}$/'; // guarantee it starts with a 9 and 8 more digits
        }

        return $rules;
    }
}
