<?php

namespace App\Http\Requests\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ManagePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('manage_permission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role_id' => [
                'bail',
                'required',
                Rule::exists(Role::class, 'id'),
            ],
            'permission_id' => [
                'bail',
                'required',
                'array'
            ],
            'permission_id.*' => [
                'bail',
                'required',
                Rule::exists(Permission::class, 'id'),
            ]
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => 'Vui lòng chọn role cần gán (hoặc gỡ) permission',
            'role_id.exists' => 'Role đã chọn không tồn tại hoặc đã bị xóa.',
            'permission_id.required' => 'Vui lòng chọn permission cần gán (hoặc gỡ).',
            'permission_id.array' => 'Permission không hợp lệ (phải bao gồm một danh sách permission).',
            'permission_id.*.required' => 'Vui lòng chọn permission cần gán (hoặc gỡ).',
            'permission_id.*.exists' => 'Permission đã chọn không tồn tại hoặc đã bị xóa.', 
        ];
    }
}
