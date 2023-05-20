<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductImageContract;
use App\Models\ProductImage;
use App\Repositories\BaseRepository;

class ProductImageRepository extends BaseRepository implements ProductImageContract
{
    public function __construct(ProductImage $model){
        parent::__construct($model);
    }


    public function getAllImageByProductId($product_id){
        return ProductImage::where('product_id',$product_id)->get();
    }


}
