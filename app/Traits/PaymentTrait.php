<?php
namespace App\Traits;

use App\Models\Coupon;
use App\Models\FlashSaleProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shipping;
use App\Models\Tax;
use App\Notifications\NewOrderNotification;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\MailTrait;

trait PaymentTrait{

    use ENVFilePutContent, MailTrait;

    protected function orderStore($request)
    {
        $order = new Order();
        $order->user_id = Auth::user()->id ?? null;
        $order->billing_first_name = $request->billing_first_name;
        $order->billing_last_name = $request->billing_last_name;
        $order->billing_email = $request->billing_email;
        $order->billing_phone = $request->billing_phone;
        $order->billing_country = $request->billing_country;
        $order->billing_address_1 = $request->billing_address_1;
        $order->billing_address_2 = $request->billing_address_2;
        $order->billing_city = $request->billing_city;
        $order->billing_state = $request->billing_state;
        $order->billing_zip_code = $request->billing_zip_code;
        $order->shipping_method = $request->shipping_type;
        $order->shipping_cost = $request->shipping_cost;
        $order->payment_method = $request->payment_type;
        $order->total = implode(explode(',',$request->totalAmount));
        $order->currency_base_total = $order->total * env('USER_CHANGE_CURRENCY_RATE');
        $order->currency_symbol = (env('USER_CHANGE_CURRENCY_SYMBOL')!=NULL ? env('USER_CHANGE_CURRENCY_SYMBOL') : env('DEFAULT_CURRENCY_SYMBOL'));
        $order->order_status = 'pending';
        $order->payment_status = 'pending';
        $order->payment_id = uniqid();
        $order->date = date('Y-m-d');

        if (isset($request->tax_id)) {
            $tax = Tax::find($request->tax_id);
            if ($tax) {
                $order->tax_id = $tax->id;
                $order->tax = $tax->rate;
            }else {
                $order->tax_id = null;
                $order->tax = null;
            }
        }
        // $order->tax_id = $request->tax_id!=NULL ? $request->tax_id : NULL;


        // Check Restore & Cancel
        if ($request->coupon_code && $request->coupon_checked==1) {
            $coupon = Coupon::where('coupon_code',$request->coupon_code)->where('is_active',1);
            if ($coupon->exists()) {
                $coupon = Coupon::where('coupon_code',$request->coupon_code)->first();
                $order->coupon_id = $coupon->id;
                $order->discount = $coupon->value ?? null;

                //Limit Coupon
                DB::table('coupons')
                ->where('coupon_code',$request->coupon_code)
                ->where('coupon_remaining','>',0)
                ->orWhere('coupon_remaining','!=',NULL)
                ->update(['coupon_remaining' => DB::raw('coupon_remaining - 1')]);
                if ($coupon && $coupon->is_limit && $coupon->coupon_remaining==0) {
                    $coupon->update(['value'=>0.00]);
                }
            }
        }

        $shipping = new Shipping();
        $shipping->shipping_first_name = $request->shipping_first_name;
        $shipping->shipping_last_name = $request->shipping_last_name;
        $shipping->shipping_email = $request->shipping_email;
        $shipping->shipping_phone = $request->shipping_phone;
        $shipping->shipping_country = $request->shipping_country;
        $shipping->shipping_address_1 = $request->shipping_address_1;
        $shipping->shipping_address_2 = $request->shipping_address_2;
        $shipping->shipping_city = $request->shipping_city;
        $shipping->shipping_state = $request->shipping_state;
        $shipping->shipping_zip_code = $request->shipping_zip_code;
        $order->save();

        //reference_no Added
        $reference_no = 1000 + $order->id;

        Order::whereId($order->id)->update(['reference_no'=> $reference_no]);

        if (isset($request->shipping_address_check) && $request->shipping_address_check==1) { //if selected shipping
            $shipping->order_id = $order->id;
            $shipping->save();
        }

        $content    = Cart::content();
        foreach ($content as $row)
        {
            $order_detail = new OrderDetail();
            $order_detail->order_id   = $order->id;
            $order_detail->product_id = $row->options->product_id;;
            $order_detail->category_id= $row->options->category_id;
            $order_detail->brand_id   = $row->options->brand_id;
            $order_detail->image      = $row->options->image;
            $order_detail->options    = $row->options;
            $order_detail->price      = $row->price;
            $order_detail->qty        = $row->qty;
            $order_detail->weight     = $row->weight;
            $order_detail->discount   = $request->coupon_value;
            $order_detail->tax        = $row->tax;
            $order_detail->subtotal   = $row->subtotal;
            $order_detail->save();
        }
        $order_id = $order->id;

        //Mail
        $this->sendMailWithOrderDetailsInvoice($reference_no);
        $order->notify(new NewOrderNotification($reference_no));
        return $order_id;
    }

    protected function reduceProductQuantity($order_id){
        $order_details = OrderDetail::where('order_id',$order_id)->get();
        foreach ($order_details as $row) {
            if (FlashSaleProduct::where('product_id',$row->product_id)->exists()) {
                DB::table('flash_sale_products')
                ->where('product_id',$row->product_id)
                ->update(['qty' => DB::raw('qty -'.$row->qty)]);
            }else {
                DB::table('products')
                ->where('id',$row->product_id)
                ->where('manage_stock',1)
                ->update(['qty' => DB::raw('qty -'.$row->qty)]);
            }
        }
        Cart::destroy();
    }

    protected function destroyOthers(){
        Cart::destroy();
        return redirect('payment_success');
    }

    protected function undoDBTableData($order_id){
        Order::find($order_id)->delete();
        OrderDetail::where('order_id',$order_id)->delete();
    }

    protected function undoTableDataAndRestoreProductQuantity($reference_no){
        $order_id = $this->restoreProductQuantityByReference($reference_no);
        $this->undoDBTableData($order_id);
    }

    protected function orderCancel(){
        Cart::destroy();
    }

    // Paystack || Razorpay || SSL Commerz
    protected function updateOrderAfterPaymentComplete($reference_no, $payment_id){
        Order::where('reference_no',$reference_no)
            ->update([
                'order_status'  =>'order_completed',
                'payment_status'=>'complete',
                'payment_id'    => $payment_id,
            ]);
        return redirect('payment_success');
    }

    // Paystack || SSL Commerz
    protected function restoreProductQuantityByReference($reference_no){
        $order = Order::where('reference_no',$reference_no)->first();
        return $this->restoreProductQuantity($order);
    }

    // Paystack
    protected function restoreProductQuantity($order){
        $order_details = OrderDetail::where('order_id',$order->id)->get();
        foreach ($order_details as $row) {
            if (FlashSaleProduct::where('product_id',$row->product_id)->exists()) {
                DB::table('flash_sale_products')
                ->where('product_id',$row->product_id)
                ->update(['qty' => DB::raw('qty +'.$row->qty)]);
            }else {
                DB::table('products')
                ->where('id',$row->product_id)
                ->where('manage_stock',1)
                ->update(['qty' => DB::raw('qty +'.$row->qty)]);
            }
        }
        Cart::destroy();
        return $order->id;
    }

    // Paystack
    protected function latestOrderCancel(){
        $order = Order::latest()->first();
        $this->restoreProductQuantity($order);
        $this->undoDBTableData($order->id);
    }
}

?>
