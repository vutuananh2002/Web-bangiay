<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends FormRequest
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
            'email' => [
                'bail',
                'required',
                'email',
                'regex:/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                Rule::exists(User::class),
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng điền email của bạn.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.regex' => 'Địa chỉ email không hợp lệ.',
            'email.exists' => 'Địa chỉ email này không tồn tại.',
        ];
    }
}
