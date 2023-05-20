<?php

namespace App\Payment;

use App\Contracts\Payble\PaybleContract;
use App\Traits\PaymentTrait;
use Exception;
use Stripe;

class StripePayment implements PaybleContract
{
    use PaymentTrait;

    // public function pay($request, $order_id)
    // {
    //     //logic here
    //     try {
    //         $this->stripe($request);
    //     } catch (Exception $e) {
    //         $this->undoDBTableData($order_id);
    //         return redirect()->back()->withErrors(['errors' => [$e->getMessage()]]);
    //     }
    //     $this->reduceProductQuantity($order_id);
    //     return $this->destroyOthers();
    // }

    public function pay($request, $otherRequest)
    {
        try {
            $this->stripe($request->totalAmount, $otherRequest['stripeToken']);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        $order_id = $this->orderStore($request);
        $this->reduceProductQuantity($order_id);
        return response()->json(['success' =>'done']);
    }

    public function cancel(){
        $this->orderCancel();
        return response()->json('success');

    }

    protected function stripe($totalAmount, $stripeToken){
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => (int)implode(explode(',',$totalAmount)),
                "currency" => env('STRIPE_CURRENCY'),
                "source" => $stripeToken,
                "description" => "Stripe Payment Successfull."
        ]);
    }
}

?>
