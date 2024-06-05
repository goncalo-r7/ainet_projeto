<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class MovieFormRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'year' => 'required|integer|between:1900,3000',
            'synopsis' => 'required|string',
            'genre' => 'required|string',
            'trailer_url' => 'nullable|string|starts_with:https://www.youtube.com/watch?v=',

            'image_file' => 'sometimes|image|mimes:png|max:4096', // maxsize = 4Mb

        ];
        // if (strtolower($this->getMethod()) == 'post') {
        //     // This will merge 2 arrays:
        //     // (adds the "abbreviation" rule to the $rules array)
        //     $rules = array_merge($rules, [
        //         'abbreviation' => 'required|string|max:20|unique:courses,abbreviation',
        //     ]);
        // } ATENCAO A ISTO
        return $rules;
    }

    public function messages(): array
    {
        return [
            'ECTS.required' => 'ECTS is required',
            'ECTS.integer' => 'ECTS must be an integer',
            'ECTS.min' => 'ECTS must be equal or greater that 1',
        ];
    }
}
