<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductAttributeValueContract;
use App\Models\ProductAttributeValue;
use App\Repositories\BaseRepository;

class ProductAttributeValueRepository extends BaseRepository implements ProductAttributeValueContract
{
    public function __construct(ProductAttributeValue $model){
        parent::__construct($model);
    }
}
