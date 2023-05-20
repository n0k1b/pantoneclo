<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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
            'slider_title' => 'required|unique:slider_translations,slider_title,'.$this->slider_Translation_id.',id,deleted_at,NULL',
            'type' => 'required',
            'slider_image' => 'image|max:10240|mimes:jpeg,png,jpg,gif,webp',
        ];
    }
}
