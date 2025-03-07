<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            `first_name` => 'max:50',
            `last_name` => 'max:50',
            `birthdate` => 'date',
            `password` => 'password|max:255',
            `document` => 'max:20',
            `email` => 'unique:users,email|required',
            `phone` => 'nullable|max:20|min:11',
            `local_phone` => 'nullable|max:20|min:11',
            `profession` => 'nullable|max:100',
            `domicile_address` => 'nullable|max:255',
            'additional_address' => 'nullable|max:255'
        ];

    }
}
