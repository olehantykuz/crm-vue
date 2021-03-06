<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'currencyId' => ['required', 'integer', 'exists:currencies,id'],
            'monthlyBudget' => ['required', 'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail($attribute.' must be greater than 0.');
                    }
                },
            ],
        ];
    }
}
