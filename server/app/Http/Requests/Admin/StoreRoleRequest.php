<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('create_role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role' => [
                'bail',
                'required',
                'numeric',
                Rule::unique(Role::class),
            ],
            'name' => [
                'bail',
                'required',
                'string',
                Rule::unique(Role::class),
            ],
        ];
    }

    public function messages()
    {
        return [
            'role.required' => 'Vui lòng điền mã Role.',
            'role.numeric' => 'Mã Role không hợp lệ.',
            'role.unique' => 'Mã role này đã tồn tại.',
            'name.required' => 'Vui lòng điền tên Role.',
            'name.unique' => 'Tên Role này đã tồn tại.',
        ];
    }
}
