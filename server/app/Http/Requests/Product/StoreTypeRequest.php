<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductTypeEnum;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('create_type', $this->type);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_code' => [
                'bail',
                'required',
                'numeric',
                Rule::in(ProductTypeEnum::asArray()),
            ],
            'slug' => [
                'bail',
                'required',
                'string',
                'regex:/^[\w\d]+(?:-[\w\d]+)*$/',
                Rule::unique(Type::class),
            ],
            'sizes' => [
                'bail',
                'nullable',
                'array',
            ],
            'sizes.*' => [
                'bail',
                'required',
                Rule::exists(Size::class, 'id'),
            ]
        ];
    }

    public function messages()
    {
        return [
            'type_code.required' => 'Vui lòng chọn loại sản phẩm.',
            'type_code.in' => 'Loại sản phẩm phải nằm trong danh sách (Nam, Nữ, Trẻ em).',
            'slug.required' => 'Vui lòng điền đường dẫn của loại sản phẩm.',
            'slug.string' => 'Đường dẫn không hợp lệ.',
            'slug.regex' => 'Đường dẫn không hợp lệ. Ex: Đường dẫn hợp lệ: test-slug',
            'slug.unique' => 'Đường dẫn này đã tồn tại.',
            'sizes.array' => 'Size của loại sản phẩm không hợp lệ (phải bao gồm một danh sách các size).',
            'sizes.*.required' => 'Vui lòng chọn size của loại sản phẩm.',
            'sizes.*.exists' => 'Size này không tồn tại.',
        ];
    }
}
