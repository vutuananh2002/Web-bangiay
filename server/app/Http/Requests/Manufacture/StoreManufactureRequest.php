<?php

namespace App\Http\Requests\Manufacture;

use App\Models\Manufacture;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreManufactureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('create_manufacture', $this->manufacture);
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
                'required',
                'string',
            ],
            'logo' => [
                'bail',
                'required',
                'image',
                'file',
            ],
            'address' => [
                'bail',
                'nullable',
                'string'
            ],
            'phone_number' => [
                'bail',
                'required',
                'string',
                'regex:/^0([1-9]{9})$/',
                Rule::unique(Manufacture::class),
            ],
            'email' => [
                'bail',
                'required',
                'email',
                'regex:/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                Rule::unique(Manufacture::class),
            ],
            'information' => [
                'bail',
                'nullable',
                'string'
            ],
            'slug' => [
                'bail',
                'required',
                'string',
                'regex:/^[\w\d]+(?:-[\w\d]+)*$/',
                Rule::unique(Manufacture::class),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng điền tên nhà sản xuất.',
            'name.string' => 'Tên nhà sản xuất không hợp lệ.',
            'logo.required' => 'Vui lòng chọn ảnh logo nhà sản xuất.',
            'logo.image' => 'File được chọn không hợp lệ.',
            'logo.file' => 'Ảnh logo phải là file.',
            'address.string' => 'Địa chỉ nhà sản xuất không hợp lệ.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại nhà sản xuât.',
            'phone_number.regex' => 'Số điện thoại đã nhập không hợp lệ.',
            'phone_number.unique' => 'Số điện thoại này đã tồn tại.',
            'phone_number.string' => 'Số điện thoại không hợp lệ.',
            'email.required' => 'Vui lòng điền email nhà sản xuất.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.regex' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã tồn tại.',
            'information.string' => 'Thông tin nhà sản xuất không hợp lệ.',
            'slug.required' => 'Vui lòng điền đường dẫn nhà sản xuất.',
            'slug.string' => 'Đường dẫn không hợp lệ.',
            'slug.regex' => 'Đường dẫn không hợp lệ. Ex: Đường dẫn hợp lệ: test-slug',
            'slug.unique' => 'Đường dẫn này đã tồn tại.',
        ];
    }
}
