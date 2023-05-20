<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ProductEmail;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SettingCashOnDelivery;
use App\Models\SettingFlatRate;
use App\Models\SettingFreeShipping;
use App\Models\SettingLocalPickup;
use App\Models\SettingPaypal;
use App\Models\SettingStrip;
use App\Models\Tax;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use App\Models\Wishlist;
use App\Traits\CouponTrait;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use CouponTrait;

    public function sendEmail(Request $request)
    {
        //Log::info($request);
        $email = 'mohammadshahin675@gmail.com';

        Mail::to($email)->send(new ProductEmail($request->message, $request->contact_no, $request->email));

    }
    public function productAddToCart(Request $request)
    {
        if ($request->ajax()) {
            //New
            $cart_content = Cart::content();
            $request_qty = $request->qty;
            $rowId = null;

            if ($cart_content) {
                foreach ($cart_content as $key => $value) {
                    if ($value->id == $request->product_id) {
                        $request_qty += $value->qty;
                        $rowId = $value->rowId;
                        break;
                    }
                }
            }

            $attribute_names = array();
            $attribute_names = explode(",", $request->attribute_names);
            $value_names = array();
            $value_names = explode(",", $request->value_names);

            $product = Product::with(['productTranslation', 'productTranslationEnglish', 'categories', 'productCategoryTranslation', 'tags', 'brand', 'brandTranslation', 'brandTranslationEnglish',
                'baseImage' => function ($query) {
                    $query->where('type', 'base')
                        ->first();
                },
                'additionalImage' => function ($query) {
                    $query->where('type', 'additional')
                        ->get();
                },
            ])
                ->find($request->product_id);

            if (($product->manage_stock == 1) && ($product->qty == 0 || $request_qty > $product->qty)) {
                return response()->json(['type' => 'quantity_limit', 'product_quantity' => $product->qty]);
            }

            $data = [];
            $data['id'] = uniqid();
            $data['name'] = $product->productTranslation->product_name ?? $product->productTranslationEnglish->product_name ?? null;
            $data['qty'] = $request_qty;
            $data['tax'] = 0;

            if (isset($request->flash_sale) && $request->flash_sale == 1) {
                $data['price'] = $request->flash_sale_price;
            } elseif ($product->special_price != null && $product->special_price > 0 && $product->special_price < $product->price) {
                $productPrice = $product->special_price;
            } else {
                $productPrice = $product->price;
            }

            $data['price'] = $productPrice;
            $data['weight'] = $product->weight ?? 0;
            $data['options']['image'] = $product->baseImage->image;
            $data['options']['product_id'] = $product->id;

            // if (!empty($attribute_name_arr) && !empty($request->value_ids)) {
            //     foreach($attribute_name_arr as $key => $value) {
            //         // $data['options'][$value]= $value_ids[$key];
            //         $data['options'][$value]= $value_names[$key];
            //     }
            // }

            if ($attribute_names[0] != null && $value_names[0] != null) {
                for ($i = 0; $i < count($attribute_names); $i++) {
                    $data['options'][$attribute_names[$i]] = $value_names[$i];
                    // --------- Only Attribute -------------
                    $data['options']['attributes']['name'][$i] = $attribute_names[$i];
                    $data['options']['attributes']['value'][$i] = $value_names[$i];
                }
            }

            // --------- Weight Base Price -------------
            if ($product->weight_base_calculation) {
                // Test
                for ($i = 0; $i < count($value_names); $i++) {
                    $originalString = $value_names[$i];
                    $wordToCheck = "gm";
                    if (strpos($originalString, $wordToCheck) !== false) {
                        $attributeWeightOfGram = (int) str_replace($wordToCheck, "", $originalString);
                        $productOriginalWeightOfGram = 1000;
                        $perProductPrice = ($productPrice * $attributeWeightOfGram) / $productOriginalWeightOfGram;
                        $data['price'] = $perProductPrice;
                        $data['weight'] = $attributeWeightOfGram / 1000;
                    }
                }
                // Test

                // $originalString = $value_names[0];
                // $wordToCheck = "gm";
                // if (strpos($originalString, $wordToCheck) !== false) {
                //     $attributeWeightOfGram = (int) str_replace($wordToCheck, "", $originalString);
                //     $productOriginalWeightOfGram = 1000;
                //     $perProductPrice       = ($productPrice * $attributeWeightOfGram) / $productOriginalWeightOfGram;
                //     $data['price']  = $perProductPrice;
                //     $data['weight'] = $attributeWeightOfGram / 1000;
                // }
            }

            $data['options']['product_slug'] = $request->product_slug;
            $data['options']['category_id'] = $request->category_id;
            $data['options']['brand_id'] = $product->brand_id ?? null;
            $data['options']['manage_stock'] = $product->manage_stock ?? null;
            $data['options']['stock_qty'] = $product->qty ?? null;
            $data['options']['in_stock'] = $product->in_stock ?? 0;

            if ($rowId) {
                Cart::update($rowId, $data);
            } else {
                Cart::add($data);
            }

            $cart_count = Cart::count();
            $cart_total = implode(explode(',', Cart::total()));
            $cart_content = Cart::content();

            if ($request->wishlist_id) {
                Wishlist::find($request->wishlist_id)->delete();
                $wishlist_id = $request->wishlist_id;
            } else {
                $wishlist_id = 0;
            }

            return response()->json(['type' => 'success', 'cart_content' => $cart_content, 'cart_count' => $cart_count, 'cart_total' => $cart_total, 'wishlist_id' => $wishlist_id]);
        }
    }

    public function cartViewDetails()
    {
        if (!Session::get('currentLocal')) {
            Session::put('currentLocal', 'en');
            $locale = 'en';
        } else {
            $locale = Session::get('currentLocal');
        }
        App::setLocale($locale);

        $setting_free_shipping = Cache::remember('setting_free_shipping', 300, function () {
            return SettingFreeShipping::latest()->first();
        });

        $setting_local_pickup = Cache::remember('setting_local_pickup', 300, function () {
            return SettingLocalPickup::latest()->first();
        });

        $setting_flat_rate = Cache::remember('setting_flat_rate', 300, function () {
            return SettingFlatRate::latest()->first();
        });

        $cart_content = Cart::content();
        $cart_subtotal = Cart::subtotal();
        $cart_total = Cart::total();

        return view('frontend.pages.cart_details', compact('cart_content', 'cart_subtotal', 'cart_total', 'setting_free_shipping', 'setting_local_pickup', 'setting_flat_rate'));
    }

    public function cartRomveById(Request $request)
    {
        if ($request->ajax()) {
            $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;
            Cart::remove($request->rowId);
            $cart_content = Cart::content();
            $cart_count = Cart::count();
            $cart_total = number_format((float) implode(explode(',', Cart::total())) * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '');
            return response()->json(['type' => 'success', 'cart_content' => $cart_content, 'cart_count' => $cart_count, 'cart_total' => $cart_total]);
        }
    }

    public function cartQuantityChange(Request $request)
    {
        if ($request->ajax()) {

            $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;

            Cart::update($request->rowId, ['qty' => $request->qty]);

            $specificCart = Cart::get($request->rowId);
            $cartSpecificSubtotal = number_format((float) $specificCart->subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '');

            $cartSpecificCount = $specificCart->qty;
            $cartCount = Cart::count();

            $cartTotal = implode(explode(',', Cart::total()));

            return response()->json(['type' => 'success', 'cartSpecificSubtotal' => $cartSpecificSubtotal, 'cartSpecificCount' => $cartSpecificCount, 'cartCount' => $cartCount, 'cartTotal' => $cartTotal]);
        }

        //Previous
        // if ($request->ajax()) {
        //     Cart::update($request->rowId, ['qty'  => $request->qty]);
        //     $cart_subtotal = Cart::get($request->rowId)->subtotal;
        //     $cart_count = Cart::count();
        //     $subtotal = Cart::subtotal();
        //     $cart_total = Cart::total();

        //     $total = Cart::total();
        //     $total_amount = implode(explode(',',$total)) + $request->shipping_charge - $request->coupon_value;
        //     $cart_total = number_format($total_amount, 2); //convert 10000 to 10,000

        //     return response()->json(['type'=>'success','cart_subtotal'=>$cart_subtotal, 'cart_count'=>$cart_count, 'cart_total'=>$cart_total,'subtotal'=>$subtotal,'total'=>$total]);
        // }
    }

    public function checkout()
    {
        if (Cart::count() <= 0) {
            return redirect(url('cart/empty'));
        }

        if (!Session::get('currentLocal')) {
            Session::put('currentLocal', 'en');
            $locale = 'en';
        } else {
            $locale = Session::get('currentLocal');
        }

        $coupons = Coupon::get();
        foreach ($coupons as $value) {
            if (isset($value->limit_qty)) {
                $this->limitCouponOfDateCheck($value);
            }
        }

        $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;

        $cart_content = Cart::content();

        $cart_subtotal = number_format((float) implode(explode(',', Cart::subtotal())) * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '');
        $cart_total = number_format((float) implode(explode(',', Cart::total())) * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '');

        $setting_free_shipping = Cache::remember('setting_free_shipping', 300, function () {
            return SettingFreeShipping::latest()->first();
        });
        $setting_local_pickup = Cache::remember('setting_local_pickup', 300, function () {
            return SettingLocalPickup::latest()->first();
        });
        $setting_flat_rate = Cache::remember('setting_flat_rate', 300, function () {
            return SettingFlatRate::latest()->first();
        });
        $countries = Cache::remember('countries', 300, function () {
            return Country::all();
        });
        $cash_on_delivery = Cache::remember('cash_on_delivery', 300, function () {
            return SettingCashOnDelivery::select('status')->latest()->first();
        });
        $stripe = Cache::remember('stripe', 300, function () {
            return SettingStrip::select('status')->latest()->first();
        });
        $paypal = Cache::remember('paypal', 300, function () {
            return SettingPaypal::select('status')->latest()->first();
        });

        $billing_address = null;
        $shipping_address = null;
        if (Auth::check()) {
            $billing_address = Cache::remember('billing_address', 300, function () {
                return UserBillingAddress::where('user_id', Auth::user()->id)->where('is_default', 1)->first();
            });
            $shipping_address = Cache::remember('shipping_address', 300, function () {
                return UserShippingAddress::where('user_id', Auth::user()->id)->where('is_default', 1)->first();
            });
        }

        // ------ Test -----------
        // return $cart_content;
        // $attributes[][] = [];
        // foreach($cart_content as $item){
        //     // return $item->options->attributes['name'];
        //     return count($item->options->attributes['name']);

        //     for($i=0; $i< count($item->options->attributes['name']); $i++){
        //         $attributes[$item->rowId][$i] = [
        //                 'name' => $item->options->attributes['name'][$i],
        //                 // 'value' => $item->options->attributes['value'][$i]
        //             ];
        //     }
        // }
        // return $attributes;

        // $data = $cart_content['66da2a5e593c37b25d5cea24b02a1205']->options->attributes;
        // $attributes = array();
        // for($i=0; $i< count($data['name']); $i++){
        //     $attributes[] = [
        //             'name' => $data['name'][$i],
        //             'value' => $data['value'][$i]
        //         ];
        // }

        // foreach($attributes as $item){
        //     return $item['value'];
        // }
        // return $attributes;

        // foreach($data as $key => $item){
        //     return json_decode(serialize($item));
        // }
        // $cart_content['0693784773e867fbd989140b179326fc']->options->attributes;

        // $cart_content['0693784773e867fbd989140b179326fc']->options->attributes;
        // $data = $cart_content['0693784773e867fbd989140b179326fc']->options->attributes;
        // return $cart_content['cf32fb01aee0a0479af6670382f09fea']->options;
        // Test End

        // foreach($cart_content as $item){
        //     $data = $item->options->attributes;
        // }
        // foreach($data as $key => $item){
        //     return $key;
        // }
        // ------ Test End -----------

        return view('frontend.pages.checkout_page.checkout', compact(
            'cart_content',
            'cart_subtotal',
            'cart_total',
            'setting_free_shipping',
            'setting_local_pickup',
            'setting_flat_rate',
            'countries',
            'cash_on_delivery',
            'stripe',
            'paypal',
            'billing_address',
            'shipping_address'
        ));
    }

    public function countryWiseTax(Request $request)
    {
        if ($request->ajax()) {

            //Coupon
            if ($request->coupon_code != null) {
                $coupon = Coupon::where('coupon_code', $request->coupon_code)->where('is_active', 1)->first();
                if ($coupon) {
                    $coupon_value = Coupon::where('coupon_code', $request->coupon_code)->where('is_active', 1)->first()->value;
                }
            } else {
                $coupon_value = 0;
            }

            //Shipping_cost
            if ($request->shipping_cost != null) {
                $shipping_cost = $request->shipping_cost;
            } else {
                $shipping_cost = 0;
            }

            //Tax
            $tax = Tax::where('country', $request->billing_country)->first();
            if ($tax) {
                $tax_rate = $tax->rate;
                $tax_id = $tax->id;
            } else {
                $tax_rate = 0;
                $tax_id = null;
            }

            $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;

            $cart_total = implode(explode(',', Cart::total()));
            $total_amount = (($cart_total + $shipping_cost + $tax_rate) - $coupon_value) * $CHANGE_CURRENCY_RATE;

            return response()->json(['total_amount' => number_format((float) $total_amount, env('FORMAT_NUMBER'), '.', ''),
                'coupon_value' => $coupon_value,
                'tax_rate' => $tax_rate,
                'tax_id' => $tax_id]);
        }
    }

    public function applyCoupon(Request $request)
    {
        if ($request->ajax()) {

            //Coupon
            // $coupon_value = 0;
            // if ($request->coupon_code!=NULL) {
            //     $coupon =  Coupon::where('coupon_code',$request->coupon_code)->where('is_active',1)->first();
            //     if ($coupon) {
            //         $coupon_value = Coupon::where('coupon_code',$request->coupon_code)->where('is_active',1)->first()->value;
            //     }
            // }

            //Coupon
            $coupon_value = 0;
            $expired = false;
            if ($request->coupon_code != null) {
                $coupon = Coupon::where('coupon_code', $request->coupon_code)
                    ->where('coupon_remaining', '>', 0)
                    ->first();
                if ($coupon && $coupon->is_expire) {
                    if (date('Y-m-d', strtotime($coupon->start_date)) <= date('Y-m-d') && date('Y-m-d', strtotime($coupon->end_date)) >= date('Y-m-d')) {
                        $coupon_value = $coupon->value;
                    } else {
                        $coupon_value = 0;
                        $expired = true;
                    }
                } elseif ($coupon && !$coupon->is_expire) {
                    $coupon_value = $coupon->value;
                }
            }

            //Shipping Cost
            if ($request->shipping_cost != null) {
                $shipping_cost = $request->shipping_cost;
            } else {
                $shipping_cost = 0;
            }

            //Tax
            $tax = Tax::where('id', $request->tax_id)->first();
            if ($tax) {
                $tax_rate = $tax->rate;
                $tax_id = $tax->id;
            } else {
                $tax_rate = 0;
                $tax_id = null;
            }

            $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;

            $cart_total = implode(explode(',', Cart::total()));
            $total_amount = (($cart_total + $shipping_cost + $tax_rate) - $coupon_value) * $CHANGE_CURRENCY_RATE;

            return response()->json(['type' => 'success', 'total_amount' => number_format((float) $total_amount, env('FORMAT_NUMBER'), '.', ''),
                'coupon_value' => $coupon_value,
                'tax_rate' => $tax_rate,
                'tax_id' => $tax_id,
                'expired' => $expired]);
        }
    }

    public function shippingCharge(Request $request)
    {
        if ($request->ajax()) {

            //Coupon
            $coupon_value = $request->coupon_value != null ? $request->coupon_value : 0;

            //Tax
            $tax = Tax::where('id', $request->tax_id)->first();
            if ($tax) {
                $tax_rate = $tax->rate;
                $tax_id = $tax->id;
            } else {
                $tax_rate = 0;
                $tax_id = null;
            }

            $CHANGE_CURRENCY_RATE = env('USER_CHANGE_CURRENCY_RATE') != null ? env('USER_CHANGE_CURRENCY_RATE') : 1.00;

            $cart_total = implode(explode(',', Cart::total()));
            $total_amount = (($cart_total + $request->shipping_cost + $tax_rate) - $coupon_value) * $CHANGE_CURRENCY_RATE;

            return response()->json(['type' => 'success',
                'total_amount' => number_format((float) $total_amount, env('FORMAT_NUMBER'), '.', ''),
                'coupon_value' => $coupon_value,
                'tax_rate' => $tax_rate,
                'tax_id' => $tax_id]);

        }
    }
}
