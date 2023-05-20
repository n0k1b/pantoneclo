<?php
namespace App\Traits;

use App\Models\CurrencyRate;
use App\Models\Order;

trait UtilitiesTrait{


    public function getOrderByReference($reference_no){
        return Order::with('orderDetails.product.productTranslation','shippingDetails')->where('reference_no',$reference_no)->first();
    }

    protected function currencyRate()
    {
        $currency_rate = 0.00;
        $default_currency_code = env('DEFAULT_CURRENCY_CODE');
        if ($default_currency_code) {
            $currency_rate = CurrencyRate::where('currency_code',$default_currency_code)->first()->currency_rate;
        }
        return $currency_rate;
    }

    protected function getOrderArray($order)
    {
        $orderArray                  = $order->toArray();
        $orderArray['currency_rate'] = $this->currencyRate();
        $orderArray['total_qty']     = $order->orderDetails->sum('qty');
        $orderArray['subtotal']      = $order->orderDetails->sum('subtotal');
        return $orderArray;
    }
}
