<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'product_name'=> 'required|max:255|unique:product_translations,product_name,'.$this->product_translation_id.',id,deleted_at,NULL',
            'description' => 'required',
            'price'       => 'required',
            'base_image'  => 'image|max:10240|mimes:jpeg,png,jpg,gif,webp',
            'category_id' => 'required',
            'sku'         => 'required|unique:products,sku,'.$this->id,
            'brand_id' => 'nullable',
            'tax_id' => 'required',
        ];
    }
}
