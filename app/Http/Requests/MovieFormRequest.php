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
            'year' => 'required|integer|gte:0',
            'synopsis' => 'required|string',
            'genre_code' => 'required|string',
            'trailer_url' => 'nullable|string|starts_with:https://www.youtube.com/watch?v=',

        //   'image_file' => 'sometimes|image|mimes:png,jpg,jpeg|max:4096',// maxsize = 4Mb
            'photo_filename' => 'sometimes|image|mimes:png,jpg,jpeg|max:4096',// maxsize = 4Mb
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [

        ];
    }
}
