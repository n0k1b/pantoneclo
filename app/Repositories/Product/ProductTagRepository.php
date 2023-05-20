<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductTagContract;
use App\Models\ProductTag;
use App\Repositories\BaseRepository;

class ProductTagRepository extends BaseRepository implements ProductTagContract
{
    public function __construct(ProductTag $model){
        parent::__construct($model);
    }
}
