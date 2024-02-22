<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CustomerGenderEnum;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('update_customer', $this->customer);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => [
                'bail',
                'nullable',
                'min:2',
                'regex:/^([A-ZAÀÁẢÃẠĂẰẲẴẶÂẦẤẨẪẬĐEÈÉẺẼẸÊỀẾỂỄỆIÌÍỈĨỊOÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢUÙÚỦŨỤƯỪỨỬỮỰYỲÝỶỸỴ]|[a-zaàáảãạăằắẳẵặâầấẩẫậđeèéẻẽẹêềếểễệiìíỉĩịoòóỏõọôồốổỗộơờớởỡợuùúủũụưừứửữựyỳýỷỹỵ]{1,}\s?)+$/',
            ],
            'last_name' => [
                'bail',
                'nullable',
                'min:2',
                'regex:/^([A-ZAÀÁẢÃẠĂẰẲẴẶÂẦẤẨẪẬĐEÈÉẺẼẸÊỀẾỂỄỆIÌÍỈĨỊOÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢUÙÚỦŨỤƯỪỨỬỮỰYỲÝỶỸỴ]|[a-zaàáảãạăằắẳẵặâầấẩẫậđeèéẻẽẹêềếểễệiìíỉĩịoòóỏõọôồốổỗộơờớởỡợuùúủũụưừứửữựyỳýỷỹỵ]{1,}\s?)+$/',
            ],
            'gender' => [
                'bail',
                'nullable',
                Rule::in(CustomerGenderEnum::asArray()),
            ],
            'address' => [
                'bail',
                'nullable',
            ],
            'phone_number' => [
                'bail',
                'nullable',
                'regex:/^0([1-9]{9})$/',
                Rule::unique(Customer::class)->ignore($this->customer),
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Vui lòng nhập họ của bạn.',
            'first_name.min' => 'Họ tối thiểu :min ký tự.',
            'first_name.regex' => 'Họ đã nhập không hợp lệ.',
            'last_name.required' => 'Vui lòng nhập tên của bạn.',
            'last_name.min' => 'Tên tối thiểu :min ký tự.',
            'last_name.regex' => 'Tên đã điền không hợp lệ.',
            'gender.required' => 'Vui lòng chọn giới tính.',
            'gender.in' => 'Giới tính phải là Nam hoặc Nữ.',
            'address.required' => 'Vui lòng điền địa chỉ của bạn.',
            'phone_number.required' => 'Vui lòng điền số điện thoại.',
            'phone_number.regex' => 'Số điện thoại đã nhập không hợp lệ.',
            'phone_number.unique' => 'Số điện thoại này đã tồn tại.',  
        ];
    }
}
