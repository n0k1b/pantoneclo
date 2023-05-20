<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
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
            'product_name' => 'required|unique:product_translations,product_name,NULL,id,deleted_at,NULL',
            'description' => 'required',
            'price'       => 'required',
            'sku'         => 'required|unique:products',
            'base_image'  => 'image|required|max:10240|mimes:jpeg,png,jpg,gif,webp',
            'category_id' => 'required',
            'tax_id'      => 'required',
        ];
    }
}
