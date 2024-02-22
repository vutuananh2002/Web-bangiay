<?php

namespace App\Http\Requests\Manufacture;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLogoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('manage_manufacture_logo', $this->manufacture);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => [
                'bail',
                'required',
                'file',
                'image',
            ] 
        ];
    }

    public function messages()
    {
        return [
            'logo.required' => 'Vui lòng chọn ảnh logo nhà sản xuất.',
            'logo.file' => 'Ảnh logo phải là file.',
            'logo.image' => 'File không hợp lệ.',
        ];
    }
}
