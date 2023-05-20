<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\KeywordHit;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tax;
use App\Traits\TranslationTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
//Google Analytics
use Spatie\Analytics\AnalyticsFacade as Analytics;
use Spatie\Analytics\Period;

class ReportController extends Controller
{
    use TranslationTrait;

    public function reportCoupon(Request $request)
    {
        if ($request->ajax()) {
            $html='';
            if ($request->start_date && $request->end_date && $request->report_type) {
                $from        = date($request->start_date);
                $to          = date($request->end_date);
                $report_type = $request->report_type;

                $orders = Order::with('coupon.couponTranslations','orderDetails')
                            ->where('coupon_id','!=',null)
                            ->whereBetween('date', [$from, $to])
                            ->where('order_status', $report_type)
                            ->get()
                            ->map(function($order){
                                return [
                                    'id'          => $order->id,
                                    'date'        => date('d M, Y',strtotime($order->date)),
                                    'coupon_name' => $this->translations($order->coupon->couponTranslations)->coupon_name ?? null,
                                    'coupon_code' => $order->coupon->coupon_code,
                                    'total_orders'=> $order->orderDetails->count(),
                                    'status'      => ucwords(str_replace("_", ' ', $order->order_status)),
                                    'total_amount'=> $order->total,
                                ];
                            });
            }
            elseif ($request->start_date && $request->end_date) {
                $from = date($request->start_date);
                $to   = date($request->end_date);

                // $coupon_reports = Coupon::with(['couponTranslation','couponTranslationEnglish','orders'=>function ($query) use($from,$to){
                //     $query->whereBetween('date', [$from, $to])->get();
                // }])->get();

                $orders = Order::with('coupon.couponTranslations','orderDetails')
                        ->where('coupon_id','!=',null)
                        ->whereBetween('date', [$from, $to])
                        ->get()
                        ->map(function($order){
                            return [
                                'id'          => $order->id,
                                'date'        => date('d M, Y',strtotime($order->date)),
                                'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                                'coupon_name' => $this->translations($order->coupon->couponTranslations)->coupon_name ?? null,
                                'coupon_code' => $order->coupon->coupon_code,
                                'total_orders'=> $order->orderDetails->count(),
                                'status'      => ucwords(str_replace("_", ' ', $order->order_status)),
                                'total_amount'=> $order->total,
                            ];
                        });
            }
            elseif ($request->report_type){
                $report_type = $request->report_type;

                $orders = Order::with('coupon.couponTranslations','orderDetails')
                    ->where('coupon_id','!=',null)
                    ->where('order_status', $report_type)
                    ->get()
                    ->map(function($order){
                        return [
                            'id'          => $order->id,
                            'date'        => date('d M, Y',strtotime($order->date)),
                            'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                            'coupon_name' => $this->translations($order->coupon->couponTranslations)->coupon_name ?? null,
                            'coupon_code' => $order->coupon->coupon_code,
                            'total_orders'=> $order->orderDetails->count(),
                            'status'      => ucwords(str_replace("_", ' ', $order->order_status)),
                            'total_amount'=> $order->total,
                        ];
                    });
            }
            $coupon_reports = json_decode(json_encode($orders), FALSE);
            $html = $this->couponReportDataHtml($coupon_reports);
            return response()->json($html);
        }
        else {
            $orders = Order::with('coupon.couponTranslations','orderDetails')
                        ->where('coupon_id','!=',null)
                        ->get()
                        ->map(function($order){
                            return [
                                'id'          => $order->id,
                                'date'        => date('d M, Y',strtotime($order->date)),
                                'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                                'coupon_name' => $order->coupon->couponTranslations ? $this->translations($order->coupon->couponTranslations)->coupon_name : '',
                                'coupon_code' => $order->coupon->coupon_code,
                                'total_orders'=> $order->orderDetails->count(),
                                'status'      => ucwords(str_replace("_", ' ', $order->order_status)),
                                'total_amount'=> $order->total,
                            ];
                        });
            $coupon_reports = json_decode(json_encode($orders), FALSE);
            return view('admin.pages.report.index',compact('coupon_reports'));
        }
    }

    protected function couponReportDataHtml($coupon_reports){
        $html = '';
        foreach ($coupon_reports as $item){
                $html .= '<tr><td>';
                $html .= $item->date;
                $html .= '</td><td>';
                $html .= $item->customer_name;
                $html .= '</td><td>';
                $html .= $item->coupon_name;
                $html .= '</td><td>';
                $html .= $item->coupon_code;
                $html .= '</td><td>';
                $html .= $item->total_orders;
                $html .= '</td><td>';
                $html .= $item->status;
                $html .= '</td><td>';

                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td></tr>';
        }
        return $html;
    }


    public function reportcustomerOrders(Request $request)
    {
        if ($request->ajax()) {
            if ($request->start_date && $request->end_date && $request->report_type) {
                $from = date($request->start_date);
                $to   = date($request->end_date);
                $report_type = $request->report_type;

                $data = Order::with('orderDetails')
                    ->whereBetween('date', [$from, $to])
                    ->where('order_status', $report_type)
                    ->get()
                    ->map(function($order){
                        return [
                            'id'            => $order->id,
                            'date'          => date('d M, Y',strtotime($order->date)),
                            'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                            'email'         => $order->billing_email,
                            'total_products'=> $order->orderDetails->count(),
                            'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
                            'total_amount'  => $order->total,
                        ];
                    });
            }
            else if ($request->start_date && $request->end_date) {
                $from = date($request->start_date);
                $to   = date($request->end_date);
                $data = Order::with('orderDetails')
                        ->whereBetween('date', [$from, $to])
                        ->get()
                        ->map(function($order){
                            return [
                                'id'            => $order->id,
                                'date'          => date('d M, Y',strtotime($order->date)),
                                'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                                'email'         => $order->billing_email,
                                'total_products'=> $order->orderDetails->count(),
                                'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
                                'total_amount'  => $order->total,
                            ];
                        });
            }
            elseif ($request->report_type){
                $report_type = $request->report_type;
                $data = Order::with('orderDetails')
                        ->where('order_status', $report_type)
                        ->get()
                        ->map(function($order){
                            return [
                                'id'            => $order->id,
                                'date'          => date('d M, Y',strtotime($order->date)),
                                'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                                'email'         => $order->billing_email,
                                'total_products'=> $order->orderDetails->count(),
                                'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
                                'total_amount'  => $order->total,
                            ];
                        });
            }
            elseif ($request->report_type){
                $report_type = $request->report_type;
                $data = Order::with('orderDetails')
                        ->where('order_status', $report_type)
                        ->get()
                        ->map(function($order){
                            return [
                                'id'            => $order->id,
                                'date'          => date('d M, Y',strtotime($order->date)),
                                'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                                'email'         => $order->billing_email,
                                'total_products'=> $order->orderDetails->count(),
                                'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
                                'total_amount'  => $order->total,
                            ];
                        });
            }
            // elseif ($request->customer_email){
            //     $data = Order::with('orderDetails')
            //             ->get()
            //             ->map(function($order){
            //                 return [
            //                     'id'            => $order->id,
            //                     'date'          => date('d M, Y',strtotime($order->date)),
            //                     'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
            //                     'email'         => $order->billing_email,
            //                     'total_products'=> $order->orderDetails->count(),
            //                     'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
            //                     'total_amount'  => $order->total,
            //                 ];
            //             });
            // }
            $customer_order_reports = json_decode(json_encode($data), FALSE);
            $html = $this->customerOrderReportHtml($customer_order_reports);
            return response()->json($html);
        }else {
            // $customer_order_reports = User::with('orders.orderDetails')
            //                         ->where('user_type',0)
            //                         ->where('is_active',1)
            //                         ->get();
            $data = Order::with('orderDetails')
                    ->get()
                    ->map(function($order){
                        return [
                            'id'            => $order->id,
                            'date'          => date('d M, Y',strtotime($order->date)),
                            'customer_name' => $order->billing_first_name.' '.$order->billing_last_name,
                            'email'         => $order->billing_email,
                            'total_products'=> $order->orderDetails->count(),
                            'status'        => ucwords(str_replace("_", ' ', $order->order_status)),
                            'total_amount'  => $order->total,
                        ];
                    });

    $customer_order_reports = json_decode(json_encode($data), FALSE);
            return view('admin.pages.report.customer_orders',compact('customer_order_reports'));
        }
    }

    protected function customerOrderReportHtml($customer_order_reports){
        $html = '';
        foreach ($customer_order_reports as $item){
                $html .= '<tr><td>';
                $html .= $item->date; //;
                $html .= '</td><td>';
                $html .= $item->customer_name;
                $html .= '</td><td>';
                $html .= $item->email;
                $html .= '</td><td>';
                $html .= $item->total_products;
                $html .= '</td><td>';
                $html .= $item->status;
                $html .= '</td><td>';

                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td></tr>';
        }

        return $html;
    }

    public function productStockReport(Request $request)
    {
        if ($request->ajax()) {
            if ($request->quantity_above && $request->quantity_bellow && $request->stock_availity) {
                $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                        ->whereBetween('qty',[$request->quantity_above,$request->quantity_bellow])
                                        ->where('in_stock',$request->stock_availity)
                                        ->where('is_active',1)
                                        ->get();
            }
            else if ($request->quantity_above && $request->quantity_bellow) {
                $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                        ->whereBetween('qty',[$request->quantity_above,$request->quantity_bellow])
                                        ->where('is_active',1)
                                        ->get();
            }
            else if ($request->quantity_above) {
                $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                        ->where('qty','>',$request->quantity_above)
                                        ->where('is_active',1)
                                        ->get();
            }
            else if ($request->quantity_bellow) {
                $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                        ->where('qty','<',$request->quantity_bellow)
                                        ->where('is_active',1)
                                        ->get();
            }
            else if ($request->stock_availity) {
                $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                        ->where('in_stock',$request->stock_availity)
                                        ->where('is_active',1)
                                        ->get();
            }
            $html = $this->productStockReportHtml($product_stock_reports);
            return response()->json($html);
        }else {
            $product_stock_reports =  Product::with('productTranslation','productTranslationEnglish')
                                    ->where('is_active',1)
                                    ->get();

            return view('admin.pages.report.product_stock_report',compact('product_stock_reports'));
        }
    }

    protected function productStockReportHtml($product_stock_reports)
    {
        $html = '';
        foreach ($product_stock_reports as $item){
                $html .= '<tr><td>';
                $html .= $item->productTranslation->product_name;
                $html .= '</td><td>';
                $html .= $item->qty;
                $html .= '</td><td>';
                $html .= $item->in_stock==1  ? 'In Stock':'Out Stock';
                $html .= '</td></tr>';
        }

        return $html;
    }


    public function productViewReport(Request $request)
    {
        // $google_results[0]['url'] = '/cartpro/product/xiaomi-redmi-10/20';
        // $google_results[1]['url'] = '/cartpro/product/oppo-watch/3';
        // $google_results[2]['url'] = '/cartpro/product/richman-shirt/3';

        $google_results = Analytics::fetchMostVisitedPages(Period::days(7));
        $product_name_and_views =[];

        if ($request->ajax()) {
            $all_product_slugs = Product::where('is_active',1)->where('sku',$request->sku)->pluck('slug');
            $product_name_and_views = $this->productViewReportShow($google_results, $all_product_slugs);
            $html = '';
            foreach ($product_name_and_views as $item){
                    $html .= '<tr><td>';
                    $html .= ucfirst(str_replace("-"," ",$item['product_name']));
                    $html .= '</td><td>';
                    $html .= $item['pageViews'];
                    $html .= '</td></tr>';
            }
            return response()->json($html);
        }
        else {
            $all_product_slugs = Product::where('is_active',1)->pluck('slug');
            $product_name_and_views = $this->productViewReportShow($google_results, $all_product_slugs);
            return view('admin.pages.report.product_view_report',compact('product_name_and_views'));
        }
    }

    protected function productViewReportShow($google_results, $all_product_slugs){
        $product_name_and_views =[];

        if (!empty($google_results) && !empty($all_product_slugs)) {
            foreach ($google_results as $value) {
                $data_array = explode("/",$value['url']);
                if (count($data_array)>3) {
                    $product_name = $data_array[3]; //3 indicate product name
                    foreach ($all_product_slugs as $key => $item) {
                        if ($item==$product_name) {
                            $product_name_and_views[$key]['product_name'] = $product_name;
                            $product_name_and_views[$key]['pageViews'] = $value['pageViews']; //10;
                            break;
                        };
                    }
                }
            }
        }
        return $product_name_and_views;
    }

    public function salesReport(Request $request)
    {
        if ($request->ajax()) {
            if ($request->start_date && $request->end_date && $request->report_type) {
                $sales_report = Order::with('orderDetails','shippingDetails')
                                ->whereBetween('date',[$request->start_date, $request->end_date])
                                ->where('order_status',$request->report_type)
                                ->get();
            }
            else if ($request->start_date && $request->end_date) {
                $sales_report = Order::with('orderDetails','shippingDetails')
                                ->whereBetween('date',[$request->start_date, $request->end_date])
                                ->get();
            }
            else if ($request->report_type) {
                $sales_report = Order::with('orderDetails','shippingDetails')
                                ->where('order_status',$request->report_type)
                                ->get();
            }
            $html = $this->salesReportHtml($sales_report);
            return response()->json($html);
        }else {
            $sales_report = Order::with('orderDetails','shippingDetails')
                            ->get();
            return view('admin.pages.report.sales_report',compact('sales_report'));
        }

    }

    protected function salesReportHtml($product_stock_reports)
    {
        $html = '';
        foreach ($product_stock_reports as $item){
                $html .= '<tr><td>';
                $html .=  date('d M, Y',strtotime($item->date));
                $html .= '</td><td>';
                $html .= $item->id;
                $html .= '</td><td>';
                $html .= $item->orderDetails->count();
                $html .= '</td><td>';

                $sum_subtotal = 0;
                foreach ($item->orderDetails as $value){
                    $sum_subtotal += $value->price;
                }
                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$sum_subtotal, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$sum_subtotal, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td><td>';


                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$item->shipping_cost, env('FORMAT_NUMBER'), '.', '').' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL').' '.number_format((float)$item->shipping_cost, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td><td>';


                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '').' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL').' '.number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td><td>';

                $html .= $item->order_status;
                $html .= '</td></tr>';

        }

        return $html;
    }

    public function searchReport(){
        $keyword_hits = KeywordHit::select('keyword','hit')->get();
        return view('admin.pages.report.search_report',compact('keyword_hits'));
    }

    public function shippingReport(Request $request)
    {
        if ($request->ajax()) {
            if ($request->start_date && $request->end_date && $request->report_type) {
                $to = $request->start_date;
                $end= $request->end_date;
                $shipping_reports = Order::with('orderDetails')->whereBetween('date',[$to,$end])->where('shipping_method',$request->report_type)->get();
            }
            else if ($request->start_date && $request->end_date) {
                $to = $request->start_date;
                $end= $request->end_date;
                $shipping_reports = Order::with('orderDetails')->whereBetween('date',[$to,$end])->get();
            }
            else if ($request->report_type) {
                $shipping_reports = Order::with('orderDetails')->where('shipping_method',$request->report_type)->get();
            }
            $html = $this->shippingReportHtml($shipping_reports);
            return response()->json($html);

        }else {
            $shipping_reports = Order::with('orderDetails')->get();
            return view('admin.pages.report.shipping_report',compact('shipping_reports'));
        }
    }

    protected function shippingReportHtml($shipping_reports)
    {
        $html = '';
        if (!empty($shipping_reports)) {
            foreach ($shipping_reports as $item){
                $html .= '<tr><td>';
                $html .=  date('d M, Y',strtotime($item->date)); //;
                $html .= '</td><td>';
                $html .= ucwords(str_replace("_", ' ', $item->shipping_method));
                $html .= '</td><td>';
                $html .= $item->orderDetails->count();
                $html .= '</td><td>';
                $html .= ucwords(str_replace("_", ' ', $item->order_status));
                $html .= '</td><td>';

                if(env('CURRENCY_FORMAT')=='suffix'){
                    $html .= number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                }else{
                    $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '');
                }
                $html .= '</td></tr>';
            }
        }

        return $html;
    }

    public function taxReport(Request $request)
    {
        if ($request->ajax()) {

            if ($request->start_date && $request->end_date) {

                $to = $request->start_date;
                $end= $request->end_date;
                $type = 'date';

                $tax_reports = Tax::with(['taxTranslation','taxTranslationDefaultEnglish','orders'=>function ($query) use ($to,$end){
                                        $query->whereBetween('date',[$to,$end])
                                            ->get();
                                    }])
                                ->where('is_active',1)
                                ->get();
            }
            // else if($request->tax_name) {

            //     $tax_name = $request->tax_name;
            //     $tax_reports = Tax::with(['orders','taxNameEn'=>function($query) use ($tax_name){
            //                             $query->where('tax_name',$tax_name)
            //                                     ->where('locale','en')
            //                                     ->get();
            //                         }])
            //                         ->where('is_active',1)
            //                         ->get();

            //     return response()->json($tax_reports);
            //     $type = 'tax_name';
            // }
            $html = $this->taxReportHtml($tax_reports,$type);
            return response()->json($html);

        }else {
            $tax_reports = Tax::with('orders','taxTranslation','taxTranslationDefaultEnglish')
                            ->where('is_active',1)
                            ->get();
            return view('admin.pages.report.tax_report',compact('tax_reports'));
        }

    }

    public function taxReportHtml($tax_reports,$type){
        $html = '';
        if (!empty($tax_reports)) {
            foreach ($tax_reports as $item){
                if ($type  =='date' && $item->orders->isNotEmpty()){
                    $html .= '<tr><td>';
                    $html .=  date('d M, Y',strtotime($item->orders[0]->date)) .' - '.date('d M, Y',strtotime($item->orders[count($item->orders)-1]->date)); //;
                    $html .= '</td><td>';
                    $html .= $item->taxTranslation->tax_name;
                    $html .= '</td><td>';
                    $html .= $item->orders->count();
                    $html .= '</td><td>';

                    $total_amount = 0;
                    foreach ($item->orders as $item){
                        $total_amount += $item->total;
                    }

                    if(env('CURRENCY_FORMAT')=='suffix'){
                        $html .= number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                    }else{
                        $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '');
                    }
                    $html .= '</td></tr>';
                }
                // else if ($type =='tax_name' && $item->orders->isNotEmpty()){
                //     $html .= '<tr><td>';
                //     $html .=  date('d M, Y',strtotime($item->orders[0]->date)) .' - '.date('d M, Y',strtotime($item->orders[count($item->orders)-1]->date)); //;
                //     $html .= '</td><td>';
                //     $html .= $item->taxTranslation->tax_name;
                //     $html .= '</td><td>';
                //     $html .= $item->orders->count();
                //     $html .= '</td><td>';

                //     $total_amount = 0;
                //     foreach ($item->orders as $item){
                //         $total_amount += $item->total;
                //     }

                //     if(env('CURRENCY_FORMAT')=='suffix'){
                //         $html .= number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '') .' '.env('DEFAULT_CURRENCY_SYMBOL');
                //     }else{
                //         $html .= env('DEFAULT_CURRENCY_SYMBOL') .' '.number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '');
                //     }
                //     $html .= '</td></tr>';
                // }
            }
        }

        return $html;
    }


    public function productPurchaseReport()
    {
        return view('admin.reports.product_purchase_report');
    }

    public function reportsType($report_type, Request $request)
    {
        $report_type = 'coupon';

        return view('admin.pages.report.index',compact('report_type'));
    }


}


            // $current_url =  url()->current();
            // $data = explode("//",$current_url);
            // $text = explode("/",$data[1]);
            // $product_name =  $text[3];





// public function todayReport()
// {
//     $reports = Order::with('orderDetails.product.productTranslation','shippingDetails')->whereDate('created_at',Carbon::today())->get();

//     return view('admin.pages.report.today_report',compact('reports'));
// }

// public function thisWeekReport()
// {
//     $reports = Order::with('orderDetails.product.productTranslation','shippingDetails')->whereMonth('created_at',Carbon::now()->month)->get();
//     return view('admin.pages.report.this_month',compact('reports'));
// }


// public function thisYearReport()
// {
//     $reports = Order::with('orderDetails.product.productTranslation','shippingDetails')->whereYear('created_at',Carbon::now()->year)->get();
//     return view('admin.pages.report.this_year',compact('reports'));
// }

// public function filterReport(Request $request)
// {
//     if ($request->start_date && $request->end_date) {
//         $start_date = $request->start_date;
//         $end_date = $request->end_date;

//         $reports = Order::with('orderDetails.product.productTranslation','shippingDetails')->whereBetween('date',[$start_date,$end_date])->get();
//         return view('admin.pages.report.filter_report',compact('reports'));
//     }else {
//         return view('admin.pages.report.filter_report');
//     }
// }
