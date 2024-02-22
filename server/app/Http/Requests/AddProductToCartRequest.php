<?php

namespace App\Http\Requests;

use App\Models\Cart;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AddProductToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('add_product_to_cart', $this->cart);
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
            'product_id' => [
                'bail',
                'required',
                Rule::exists(Product::class, 'id'),
            ],
            'quantity' => [
                'bail',
                'required',
                'numeric',
                'min:1',
            ],
            'color_id' => [
                'bail',
                'required',
                Rule::exists(Color::class, 'id'),
            ],
            'size_id' => [
                'bail',
                'required',
                Rule::exists(Size::class, 'id'),
            ],
            'type_id' => [
                'bail',
                'required',
                Rule::exists(Type::class, 'id'),
            ]
        ];
    }
}
