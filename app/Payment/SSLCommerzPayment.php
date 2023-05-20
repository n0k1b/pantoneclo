<?php

namespace App\Payment;

use App\Contracts\Payble\PaybleContract;
use App\Traits\PaymentTrait;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\Request;

class SSLCommerzPayment implements PaybleContract
{
    use PaymentTrait;

    public function pay($request, $otherRequest)
    {
        // return $request;
        $order_id = $this->orderStore($request);
        $this->reduceProductQuantity($order_id);
        $this->SSLCommerz($request, $order_id);
    }

    public function cancel(){

    }


    /*
    |------------------------------------------------------------
    |SSL Commerz Part
    |------------------------------------------------------------
    */
    protected function SSLCommerz($request, $order_id){
        $reference = $order_id + 1000;

        $post_data = array();
        $post_data['total_amount'] = implode(explode(',',$request->totalAmount)); # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        // # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->billing_first_name.' '.$request->billing_last_name;
        $post_data['cus_email'] = $request->billing_email ?? 'no-mail@gmail.com';
        $post_data['cus_add1'] = $request->billing_address_1 ?? 'Unknown';
        $post_data['cus_add2'] = $request->billing_address_2 ?? 'Unknown';
        $post_data['cus_city'] = $request->billing_city ?? 'Unknown';
        $post_data['cus_state'] = $request->billing_state ?? 'Unknown';
        $post_data['cus_postcode'] = $request->billing_zip_code ?? 'Unknown';
        $post_data['cus_country'] = $request->billing_country ?? 'Unknown';
        $post_data['cus_phone'] = $request->billing_phone ?? 'Unknown';
        $post_data['cus_fax'] = "" ?? 'Unknown';

        // # SHIPMENT INFORMATION
        $post_data['ship_name'] = $request->shipping_first_name.' '.$request->shipping_last_name;
        if ($post_data['ship_name']==NULL) {
            $post_data['ship_name'] = 'Unknown';
        }
        $post_data['ship_add1'] = $request->shipping_address_1  ?? 'Unknown';
        $post_data['ship_add2'] = $request->shipping_address_2  ?? 'Unknown';
        $post_data['ship_city'] = $request->shipping_city  ?? 'Unknown';
        $post_data['ship_state'] = $request->shipping_state ?? 'Unknown';
        $post_data['ship_postcode'] = $request->shipping_zip_code ?? 'Unknown';
        $post_data['ship_phone'] = $request->shipping_phone  ?? 'Unknown';
        $post_data['ship_country'] = $request->shipping_country  ?? 'Unknown';
        $post_data['shipping_method'] = $request->shipping_type ?? 'free';
        $post_data['product_name'] = "Unknown";
        $post_data['product_category'] = "Unknown";
        $post_data['product_profile'] = "Unknown";

        // # OPTIONAL PARAMETERS
        $post_data['value_a'] = $order_id;
        $post_data['value_b'] = "";
        $post_data['value_c'] = "";
        $post_data['value_d'] = "";

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        //If Something Wrong
        if (!is_array($payment_options)) {
            $this->undoTableDataAndRestoreProductQuantity($reference);
            $payment_options = array();
            return redirect('order_cancel')->withError("Something went wrong");
        }
    }


    public function success($request)
    {
        $tran_id = $request->input('tran_id');
        $order_id = $request->input('value_a');
        $reference = $order_id + 1000;
        return $this->updateOrderAfterPaymentComplete($reference, $tran_id);
    }

    public function failAndCancel($request)
    {
        $order_id = $request->input('value_a');
        $reference = $order_id + 1000;
        $this->undoTableDataAndRestoreProductQuantity($reference);
        return redirect('order_cancel')->withError("Something is wrong");
    }
}

?>
