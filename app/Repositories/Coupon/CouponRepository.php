<?php

namespace App\Repositories\Coupon;

use App\Contracts\Coupon\CouponContract;
use App\Models\Coupon;
use App\Repositories\BaseRepository;
use App\Utilities\Action;

class CouponRepository extends BaseRepository implements CouponContract
{
    public function __construct(Coupon $model){
        parent::__construct($model);
    }

    // public function getById($id){
    //     return Coupon::find($id);
    // }

    // public function active($id){
    //     Action::setActive($this->getById($id));
    // }

    // public function inactive($id){
    //     Action::setInactive($this->getById($id));
    // }

    // public function destroy($id){
    //     $this->getById($id)->delete();
    // }

    // public function bulkAction($type, $ids){
    //     Action::setBulkAction($type, Coupon::whereIn('id',$ids));
    // }
}
