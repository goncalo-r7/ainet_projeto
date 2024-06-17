<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
        if ($this->user()->type === 'C')
        {

            $rules = array_merge($rules, [
                   'nif' => ['nullable', 'string', 'size:9','regex:/^[0-9]+$/'],
                    'payment_type' => ['nullable', 'in:VISA,PAYPAL,MBWAY'],

            ]);
            $newPaymentType = $this->input('payment_type');
            if ($newPaymentType === 'VISA') {
                $rules['payment_ref'] = 'required|string|digits:19'; // 16 digits + 3 CVC
            } elseif ($newPaymentType === 'PAYPAL') {
                $rules['payment_ref'] = 'required|email';
            } elseif ($newPaymentType === 'MBWAY') {
                $rules['payment_ref'] = 'required|integer|regex:/^9\d{8}$/'; // guarantee it starts with a 9 and 8 more digits
            }
        }




        return $rules;
    }


    public function messages(): array
    {
        return [
            'nif.regex' => 'The NIF must contain only numbers.',

        ];
    }
}


