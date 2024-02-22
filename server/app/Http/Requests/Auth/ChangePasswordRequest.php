<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => [
                'bail',
                'required',
                'string',
                'min:6',
                'max:10',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*.,])[A-Za-z0-9!@#$%^&*.,]{6,10}$/',
            ],
            'new_password' => [
                'bail',
                'required',
                'string',
                'min:6',
                'max:10',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*.,])[A-Za-z0-9!@#$%^&*.,]{6,10}$/',
                'confirmed'
            ],
            'logout' => [
                'bail',
                'nullable',
                'boolean',
            ],
        ];
    }
}
