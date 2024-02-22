<?php

namespace App\Http\Requests\Admin;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('create_permission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permission' => [
                'bail',
                'required',
                'numeric',
                Rule::unique(Permission::class),
            ],
            'name' => [
                'bail',
                'required',
                'string',
                Rule::unique(Permission::class),
            ],
        ];
    }

    public function messages()
    {
        return [
            'permission.required' => 'Vui lòng điền mã Permission.',
            'permission.numeric' => 'Mã Permission không hợp lệ.',
            'permission.unique' => 'Mã Permission này đã tồn tại.',
            'name.required' => 'Vui lòng điền tên Permission.',
            'name.unique' => 'Tên Permission này đã tồn tại.',
        ];
    }
}
