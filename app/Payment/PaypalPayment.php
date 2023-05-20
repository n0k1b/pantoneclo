<?php

namespace App\Payment;

use App\Contracts\Payble\PaybleContract;
use App\Traits\PaymentTrait;

class PaypalPayment implements PaybleContract
{
    use PaymentTrait;

    public function pay($request, $otherRequest)
    {
        $request->payment_status = "completed";
        $order_id = $this->orderStore($request);
        $this->reduceProductQuantity($order_id);
        return response()->json(['success' =>'done']);
    }

    public function cancel(){
        $this->orderCancel();
        return response()->json('success');
    }
}

?>
