<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ScreeningFormRequest extends FormRequest
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



        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'year.required' => 'ECTS is required',
            'year.integer' => 'ECTS must be an integer',
        ];
    }
}
