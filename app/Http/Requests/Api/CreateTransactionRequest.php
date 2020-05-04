<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTransactionRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail($attribute.' must be greater than 0.');
                    }
                },
            ],
            'type' => ['required', 'string', Rule::in(['income', 'outcome'])],
            'currencyId' => ['required', 'integer', 'exists:currencies,id'],
        ];
    }
}
