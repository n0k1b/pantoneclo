<?php
namespace App\Traits;

trait CouponTrait {

    public function limitCouponOfDateCheck($model)
    {
        $current_date = date('Y-m-d');
        if (isset($model->end_date) && ($current_date > $model->end_date)) {
            $model->update(['value'=>0,'limit_qty'=>0]);
        }
    }
}
