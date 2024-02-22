<?php

namespace App\Http\Requests\Product;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateColorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('update_color', $this->color);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'color_code' => [
                'bail',
                'required',
                'regex:/^#([\w\d]{6})$/',
                Rule::unique(Color::class)->ignore($this->color),
            ],
        ];
    }

    public function messages()
    {
        return [
            'color_code.required' => 'Vui lòng chọn màu sản phẩm.',
            'color_code.regex' => 'Màu được chọn không hợp lệ.',
            'color_code.unique' => 'Màu này đã tồn tại. Vui lòng chọn một màu khác.',
        ];
    }
}
