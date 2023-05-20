<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductTranslationContract;
use App\Models\ProductTranslation;
use App\Repositories\BaseRepository;

class ProductTranslationRepository extends BaseRepository implements ProductTranslationContract
{
    public function __construct(ProductTranslation $model){
        parent::__construct($model);
    }
}
