<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProductImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('manage_product_image', $this->product);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'images' => [
                'bail',
                'required',
                'array',
            ],
            'images.*' => [
                'bail',
                'required',
                'file',
                'image',
            ],
        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'Vui lòng chọn ảnh sản phẩm.',
            'images.array' => 'Ảnh sản phẩm không hợp lệ (phải bao gồm một danh sách ảnh).',
            'images.*.required' => 'Vui lòng chọn ảnh sản phẩm.',
            'images.*.file' => 'Ảnh sản phẩm phải là file.',
            'images.*.image' => 'File đã chọn không hợp lệ.',
        ];
    }
}
