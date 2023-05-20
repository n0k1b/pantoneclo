<?php

namespace App\Contracts\Payble;

interface PaybleContract
{
    // public function pay($request, $order_id);
    public function pay($request, $otherRequest);
    public function cancel();
}
