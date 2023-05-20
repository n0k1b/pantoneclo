<?php

namespace App\Repositories\Review;

use App\Contracts\Review\ReviewContract;
use App\Models\Review;
use App\Utilities\Action;

class ReviewRepository extends Action implements ReviewContract
{
    public function getById($id){
        return Review::find($id);
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }
}
