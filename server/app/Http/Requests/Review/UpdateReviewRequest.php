<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ProductRatingEnum;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('update_review', $this->review);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'bail',
                'required',
                Rule::exists(Product::class, 'id'),
            ],
            'customer_id' => [
                'bail',
                'required',
                Rule::exists(Customer::class, 'id'),
            ],
            'comment' => [
                'bail',
                'required',
            ],
            'star' => [
                'bail',
                'required',
                Rule::in(ProductRatingEnum::asArray()),
            ],
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'Nội dung bình luận không được để trống.',
            'star.required' => 'Vui lòng đánh giá sản phẩm.', 
        ];
    }
}
