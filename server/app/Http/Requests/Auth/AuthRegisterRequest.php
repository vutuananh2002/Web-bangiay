<?php

namespace App\Http\Requests\Auth;

use App\Enums\CustomerGenderEnum;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [
                'bail',
                'required',
                'string',
                'min:6',
                'max:20',
                Rule::unique(User::class),
            ],
            'email' => [
                'bail',
                'required',
                'email',
                'regex:/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                Rule::unique(User::class),
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:6',
                'max:10',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*.,])[A-Za-z0-9!@#$%^&*.,]{6,10}$/',
                'confirmed'
            ],
            'avatar' => [
                'bail',
                'nullable',
                'file',
                'image',
            ],
            'first_name' => [
                'bail',
                'required',
                'min:2',
                'regex:/^([A-ZAÀÁẢÃẠĂẰẲẴẶÂẦẤẨẪẬĐEÈÉẺẼẸÊỀẾỂỄỆIÌÍỈĨỊOÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢUÙÚỦŨỤƯỪỨỬỮỰYỲÝỶỸỴ]|[a-zaàáảãạăằắẳẵặâầấẩẫậđeèéẻẽẹêềếểễệiìíỉĩịoòóỏõọôồốổỗộơờớởỡợuùúủũụưừứửữựyỳýỷỹỵ]{1,}\s?)+$/',
            ],
            'last_name' => [
                'bail',
                'required',
                'min:2',
                'regex:/^([A-ZAÀÁẢÃẠĂẰẲẴẶÂẦẤẨẪẬĐEÈÉẺẼẸÊỀẾỂỄỆIÌÍỈĨỊOÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢUÙÚỦŨỤƯỪỨỬỮỰYỲÝỶỸỴ]|[a-zaàáảãạăằắẳẵặâầấẩẫậđeèéẻẽẹêềếểễệiìíỉĩịoòóỏõọôồốổỗộơờớởỡợuùúủũụưừứửữựyỳýỷỹỵ]{1,}\s?)+$/',
            ],
            'gender' => [
                'bail',
                'required',
                Rule::in(CustomerGenderEnum::asArray()),
            ],
            'address' => [
                'bail',
                'required',
            ],
            'phone_number' => [
                'bail',
                'required',
                'regex:/^0([1-9]{9})$/',
                Rule::unique(Customer::class),
            ],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Vui lòng điền tên người dùng.',
            'username.string' => 'Tên người dùng không hợp lệ.',
            'username.min' => 'Tên người dùng tối thiểu 6 ký tự.',
            'username.max' => 'Tên người dùng tối đa 20 ký tự.',
            'username.unique' => 'Tên người dùng này đã tồn tại. Vui lòng chọn một tên khác.',
            'email.required' => 'Vui lòng điền email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.regex' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Địa chỉ email này đã tồn tại.',
            'password.required' => 'Vui lòng điền mật khẩu.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 6 ký tự.',
            'password.max' => 'Mật khẩu tối đa 10 ký tự.',
            'password.regex' => 'Mật khẩu phải chứa từ 6 - 10 ký tự bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'password.confirmed' => 'Vui lòng xác nhận mật khẩu.',
            'avatar.file' => 'Avatar phải là file.',
            'avatar.image' => 'Avatar phải là ảnh.',
            'first_name.required' => 'Vui lòng nhập họ của bạn.',
            'first_name.min' => 'Họ tối thiểu 2 ký tự.',
            'first_name.regex' => 'Họ đã nhập không hợp lệ.',
            'last_name.required' => 'Vui lòng nhập tên của bạn.',
            'last_name.min' => 'Tên tối thiểu 2 ký tự.',
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
