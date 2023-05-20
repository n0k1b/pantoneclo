<?php

namespace App\Payment;

use App\Contracts\Payble\PaybleContract;
use App\Traits\PaymentTrait;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\Session;

class RazorpayPayment implements PaybleContract
{
    use PaymentTrait;

    public function pay($request, $otherRequest)
    {
        if(count($otherRequest) && !empty($otherRequest['razorpay_payment_id'])) {
            try {
                $order_id = $this->orderStore($request);
                $reference = $order_id + 1000;
                $payment_id = $otherRequest['razorpay_payment_id'];
                $this->reduceProductQuantity($order_id);
                Session::forget('error');
            } catch (Exception $e) {
                Session::put('error',$e->getMessage());
                return redirect(route("payment.pay.page",'razorpay'), 307);
            }
        }
        return $this->updateOrderAfterPaymentComplete($reference, $payment_id);
    }

    public function cancel(){
        $this->orderCancel();
        return response()->json('success');
    }
}

?>
