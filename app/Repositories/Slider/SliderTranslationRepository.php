<?php

namespace App\Repositories\Slider;

use App\Contracts\Slider\SliderTranslationContract;
use App\Models\SliderTranslation;
use App\Traits\ActiveInactiveTrait;

class SliderTranslationRepository implements SliderTranslationContract
{
    use ActiveInactiveTrait;

    public function storeData($data){
        return SliderTranslation::create($data);
    }

    public function getByIdAndLocale($slider_id, $locale){
        return SliderTranslation::where('slider_id',$slider_id)->where('locale', $locale)->first();
    }

    public function updateOrInsertSliderTranslation($data)
    {
        SliderTranslation::updateOrCreate(
            ['slider_id'  => $data['slider_id'], 'locale' => $data['locale']],
            ['slider_title'=> $data['slider_title'], 'slider_subtitle'=> $data['slider_subtitle']]
        );
    }

    public function destroy($slider_id){
        SliderTranslation::where('slider_id', $slider_id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, SliderTranslation::whereIn('slider_id',$ids));
    }
}
