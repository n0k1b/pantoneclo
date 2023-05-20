<?php

namespace App\Contracts\Slider;

interface SliderTranslationContract
{
    public function storeData($data);

    public function getByIdAndLocale($slider_id, $locale);

    public function updateOrInsertSliderTranslation($data);

    public function destroy($slider_id);

    public function bulkAction($type, $ids);
}
