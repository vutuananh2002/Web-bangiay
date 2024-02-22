<?php

namespace App\Http\Requests;

use App\Models\Cart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CheckoutOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('checkout', $this->cart);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => [
                'bail',
                'required',
                Rule::exists(Cart::class),
            ],
            'receiver_name' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^([A-ZAÀÁẢÃẠĂẰẲẴẶÂẦẤẨẪẬĐEÈÉẺẼẸÊỀẾỂỄỆIÌÍỈĨỊOÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢUÙÚỦŨỤƯỪỨỬỮỰYỲÝỶỸỴ]|[a-zaàáảãạăằắẳẵặâầấẩẫậđeèéẻẽẹêềếểễệiìíỉĩịoòóỏõọôồốổỗộơờớởỡợuùúủũụưừứửữựyỳýỷỹỵ]{1,}\s?)+$/',
            ],
            'receiver_phone_number' => [
                'bail',
                'required',
                'regex:/^0([1-9]{9})$/',
            ],
            'receiver_address' => [
                'bail',
                'required',
            ],
        ];
    }
}
