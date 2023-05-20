<?php

namespace App\Contracts\Product;

use App\Contracts\BaseContract;

interface ProductImageContract extends BaseContract
{
    public function getAllImageByProductId($product_id);
}
