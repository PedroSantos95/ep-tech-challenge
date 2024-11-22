<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'email' => ['required_without:phone', 'nullable', 'email:rfc,dns'],
            'phone' => ['required_without:email', 'nullable', 'regex:/^[0-9\s\+]+$/'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'postcode' => ['nullable', 'string'],
        ];
    }
}
