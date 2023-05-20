<?php

namespace App\Http\Requests\Tax;

use Illuminate\Foundation\Http\FormRequest;

class TaxUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tax_name'   => 'required|unique:tax_translations,tax_name,'.$this->tax_translation_id.',id,deleted_at,NULL',
            'tax_class'  => 'required',
            'based_on'   => 'required',
            'country'    => 'required',
        ];
    }
}
