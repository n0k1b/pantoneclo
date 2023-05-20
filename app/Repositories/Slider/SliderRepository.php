<?php

namespace App\Repositories\Slider;

use App\Contracts\Slider\SliderContract;
use App\Models\Slider;
use App\Traits\ActiveInactiveTrait;
use App\Traits\TranslationTrait;

class SliderRepository implements SliderContract
{
    use TranslationTrait, ActiveInactiveTrait;

    public function getAllSlider()
    {
        $data = Slider::with('sliderTranslations')
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($slider){
                    return [
                        'id'             =>$slider->id,
                        'slider_slug'    =>$slider->slider_slug,
                        'type'           =>$slider->type,
                        'category_id'    =>$slider->category_id,
                        'url'            =>$slider->url,
                        'slider_image'   =>$slider->slider_image,
                        'slider_image_full_width'=>$slider->slider_image_full_width,
                        'slider_image_secondary'=>$slider->slider_image_secondary,
                        'target'         =>$slider->target,
                        'text_alignment' =>$slider->text_alignment,
                        'text_color'     =>$slider->text_color,
                        'is_active'      =>$slider->is_active,
                        'slider_title'   => $this->translations($slider->sliderTranslations)->slider_title ?? null,
                        'slider_subtitle'=> $this->translations($slider->sliderTranslations)->slider_subtitle ?? null,
                        'locale'         => $this->translations($slider->sliderTranslations)->locale ?? null,
                    ];
                });

        return json_decode(json_encode($data), FALSE);
    }

    public function getAllActiveSlider()
    {
        $data = Slider::with('sliderTranslations')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($slider){
                    return [
                        'id'             =>$slider->id,
                        'slider_slug'    =>$slider->slider_slug,
                        'type'           =>$slider->type,
                        'category_id'    =>$slider->category_id,
                        'url'            =>$slider->url,
                        'slider_image'   =>$slider->slider_image,
                        'slider_image_full_width'=>$slider->slider_image_full_width,
                        'slider_image_secondary'=>$slider->slider_image_secondary,
                        'target'         =>$slider->target,
                        'text_alignment' =>$slider->text_alignment,
                        'text_color'     =>$slider->text_color,
                        'is_active'      =>$slider->is_active,
                        'slider_title'   => $this->translations($slider->sliderTranslations)->slider_title ?? null,
                        'slider_subtitle'=> $this->translations($slider->sliderTranslations)->slider_subtitle ?? null,
                        'locale'         => $this->translations($slider->sliderTranslations)->locale ?? null,
                    ];
                });

        return json_decode(json_encode($data), FALSE);
    }

    public function storeData($data){
        return Slider::create($data);
    }

    public function getById($id){
        return Slider::find($id);
    }

    public function updateDataById($id, $data){
        return $this->getById($id)->update($data);
    }

    public function active($id){
        return $this->activeData($this->getById($id));
    }

    public function inactive($id){
        return $this->inactiveData($this->getById($id));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, Slider::whereIn('id',$ids));
    }
}
