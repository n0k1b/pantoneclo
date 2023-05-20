<?php

namespace App\Repositories\Coupon;

use App\Contracts\Coupon\CouponTranslationContract;
use App\Models\CouponTranslation;
use App\Repositories\BaseRepository;
use App\Utilities\Action;

class CouponTranslationRepository extends BaseRepository implements CouponTranslationContract
{
    public function __construct(CouponTranslation $model){
        parent::__construct($model);
    }

    // public function destroy($coupon_id){
    //     CouponTranslation::where('coupon_id', $coupon_id)->delete();
    // }

    // public function bulkAction($type, $ids){
    //     return Action::setBulkAction($type, CouponTranslation::whereIn('coupon_id',$ids));
    // }
}
