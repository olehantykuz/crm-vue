<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'limit' => ['required', 'numeric',
                function ($attribute, $value, $fail) {
                    if ($value <= 0) {
                        $fail($attribute.' must be greater than 0.');
                    }
                },
            ],
            'currencyId' => ['required', 'integer', 'exists:currencies,id'],
        ];
    }
}
