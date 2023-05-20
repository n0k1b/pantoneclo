<?php
namespace App\Services;

use App\Contracts\Coupon\CouponContract;
use App\Contracts\Coupon\CouponTranslationContract;
use App\Utilities\Message;

class CouponService extends Message
{
    private $couponContract, $couponTranslationContract;
    public function __construct(CouponContract $couponContract, CouponTranslationContract $couponTranslationContract){
        $this->couponContract = $couponContract;
        $this->couponTranslationContract = $couponTranslationContract;
    }

    public function activeById($id){
        if (!auth()->user()->can('coupon-action')){
            return Message::getPermissionMessage();
        }
        $this->couponContract->active($id);
        return Message::activeSuccessMessage();
    }

    public function inactiveById($id){
        if (!auth()->user()->can('coupon-action')){
            return Message::getPermissionMessage();
        }
        $this->couponContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('coupon-action')){
            return Message::getPermissionMessage();
        }
        $this->couponContract->destroy('id',$id);
        $this->couponTranslationContract->destroy('coupon_id',$id);
        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if (!auth()->user()->can('coupon-action')){
            return Message::getPermissionMessage();
        }

        if ($type=='delete') {
            $this->couponContract->bulkAction($type, 'id', $ids);
            $this->couponTranslationContract->bulkAction($type, 'coupon_id', $ids);
            return Message::deleteSuccessMessage();
        }else{
            $this->couponContract->bulkAction($type, 'id', $ids);
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }
}
