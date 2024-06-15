<?php

namespace App\Http\Requests;

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
            //'var' => 'required|exists:students,number' 
            // exists:students,number means that var must match a value in the number column of the students table in the database.
            'customer_name' => [
                Rule::requiredIf(function () {
                    // if user is a customer, then it is not required
                    $isCustomer = DB::table('customers')->where('id', Auth::id())->first();
                    return !$isCustomer;
                }),
                'string',
                'max:80',
            ],
            'customer_email' => [
                Rule::requiredIf(function () {
                    // if user is a customer, then it is not required
                    $isCustomer = DB::table('customers')->where('id', Auth::id())->first();
                    return !$isCustomer;
                }),
                'email',
                'max:80',
            ],
            'customer_nif' => 'nullable|digits:9',
            'payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',
            
        ];
        $ref = $rules['payment_ref'];

        if ($this->payment_type === 'VISA'){
            $card = null;
            Payment::payWithVisa($this->card_number, $this->cvc_code);
        } elseif ($this->payment_type === 'PAYPAL'){

        } elseif ($this->payment_type === 'MBWAY'){

        }

        return $rules;
    }
}
