<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize('update_banner', $this->banner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'bail',
                'required',
                'string',
            ],
            'image' => [
                'bail',
                'required',
                'file',
                'image'
            ],
            'description' => [
                'bail',
                'required',
                'string'
            ]
        ];
    }
}
