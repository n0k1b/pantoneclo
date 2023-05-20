<?php

namespace App\Contracts\Brand;

interface BrandContract
{
    public function getAllBrands();

    public function storeBrand($data);

    public function getById($id);

    public function updateBrandById($id, $data);

    public function active($id);

    public function inactive($id);

    public function destroy($id);

    public function bulkAction($type, $ids);

    //Front - HomeController
    public function getBrandsWhereInIds($ids);

}
