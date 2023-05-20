<?php

namespace App\Contracts\Slider;

interface SliderContract
{
    public function getAllSlider();

    public function getAllActiveSlider();

    public function storeData($data);

    public function getById($id);

    public function updateDataById($slider_id, $data);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);

}
