<?php

namespace App\Http\Requests;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RemoveProductFromCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('remove_product_from_cart', $this->cart);
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
            'products' => [
                'bail',
                'required',
                'array',
            ],
            'products.*' => [
                'bail',
                'required',
                Rule::exists(Product::class, 'id'),
            ]
        ];
    }
}
