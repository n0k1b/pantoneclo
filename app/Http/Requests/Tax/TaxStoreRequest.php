<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;

class TaxStoreRequest extends FormRequest
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
            'tax_name' => 'required|unique:tax_translations,tax_name,NULL,id,deleted_at,NULL',
            'tax_class'=> 'required',
            'based_on' => 'required',
            'country'  => 'required',
        ];
    }
}
