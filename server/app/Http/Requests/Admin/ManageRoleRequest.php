<?php

namespace App\Http\Requests\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ManageRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('manage_role');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'bail',
                'required',
                Rule::exists(User::class, 'id'),
            ],
            'role_id' => [
                'bail',
                'required',
                'array',
            ],
            'role_id.*' => [
                'bail',
                'required',
                Rule::exists(Role::class, 'id'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Vui lòng chọn người dùng cần phân quyền.',
            'user_id.exists' => 'Người dùng đã chọn không tồn tại hoặc đã bị xóa.',
            'role_id.required' => 'Vui lòng chọn role cần gán (hoặc gỡ).',
            'role_id.array' => 'Role không hợp lệ (phải bao gồm một danh sách role).',
            'role_id.*.required' => 'Vui lòng chọn role cần gán (hoặc gỡ).',
            'role_id.*.exists' => 'Role đã chọn không tồn tại hoặc đã bị xóa.',
        ]; 
    }
}
