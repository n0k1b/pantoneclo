<?php

namespace App\Payment;

use App\Contracts\Payble\PaybleContract;
use App\Traits\PaymentTrait;

class CashOnDeliveryPayment implements PaybleContract
{
    use PaymentTrait;

    // public function pay($request, $order_id)
    // {
    //     //logic here
    //     $this->reduceProductQuantity($order_id);
    // }

    public function pay($request, $otherRequest)
    {
        $order_id = $this->orderStore($request);
        $this->reduceProductQuantity($order_id);
        return redirect('payment_success');
    }

    public function cancel(){
        $this->orderCancel();
        return redirect(route('cartpro.home'));
    }
}

?>
