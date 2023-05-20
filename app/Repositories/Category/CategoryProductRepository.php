<?php

namespace App\Repositories\Category;

use App\Contracts\Category\CategoryProductContract;
use App\Models\CategoryProduct;
use App\Repositories\BaseRepository;

class CategoryProductRepository extends BaseRepository implements CategoryProductContract
{
    public function __construct(CategoryProduct $model){
        parent::__construct($model);
    }
}
