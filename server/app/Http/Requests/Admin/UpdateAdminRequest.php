<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\Admin;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('update_admin', $this->admin);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'bail',
                'nullable',
                'string',
                'min:2',
                'max:50',
            ],
            'phone_number' => [
                'bail',
                'nullable',
                'regex:/^0([1-9]{9})$/',
                Rule::unique(Admin::class)->ignore($this->admin),
            ],
            'description' => [
                'bail',
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng điền tên admin.',
            'name.string' => 'Tên admin không hợp lệ.',
            'name.min' => 'Tên admin tối thiểu :min ký tự',
            'name.max' => 'Tên admin tối đa :max ký tự',
            'phone_number.required' => 'Vui lòng điền số điện thoại.',
            'phone_number.unique' => 'Số điện thoại này đã tồn tại.',
            'phone_number.regex' => 'Số điện thoại không hợp lệ.',
        ];
    }
}
