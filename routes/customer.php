<?php

use Illuminate\Support\Facades\Auth AS DefaultAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Frontend;
use App\Http\Controllers\Frontend\BrandProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryProductController;
use App\Http\Controllers\Frontend\DailyDealsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController AS FrontendPageController;
use App\Http\Controllers\Frontend\UserAccountController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ShopProductController;
use App\Http\Controllers\Frontend\SslCommerzPaymentController;
use App\Http\Controllers\Frontend\TagProductController;
use App\Http\Controllers\Frontend\UserBillingAddressController;
use App\Http\Controllers\Frontend\UserShippingAddressController;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



DefaultAuth::routes();

Route::group(['middleware' => ['XSS','set_locale','maintenance_mode']], function ()
{
    /*
    |--------------------------------------------------------------------------
    | User Section
    |--------------------------------------------------------------------------
    */

    Route::get('/login',[Auth\LoginController::class,'showCustomerLoginForm'])->name('customer_login_form');
    Route::post('/customer/login', [Auth\LoginController::class,'customerLogin'])->name('customer.login');
    Route::post('/customer/register', [Frontend\RegisterController::class,'customerRegister'])->name('customer.register');
    Route::get('/password/reset', [Auth\ForgotPasswordController::class,'showLinkRequestForm'])->name('customer.password.request');
    Route::post('/password/email', [Auth\ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email'); //Here

    Route::group(['namespace'=>'Frontend'], function (){

        Route::get('/',[HomeController::class,'index'])->name('cartpro.home');

        Route::get('/change-for/{text}',[HomeController::class,'changeForDemoOrClient']);

        //FAQ
        Route::get('/faq',[HomeController::class,'faq'])->name('cartpro.faq');
        Route::get('/search-faq',[HomeController::class,'searchFAQ'])->name('cartpro.search-faq');

        //Contact
        Route::get('/contact',[HomeController::class,'contact'])->name('cartpro.contact');
        Route::post('/contact-message',[HomeController::class,'contactMessage'])->name('cartpro.contact.message');
        //About Us
        Route::get('/about-us',[HomeController::class,'aboutUs'])->name('cartpro.about_us');

        //Order Tracking
        Route::get('/order-tracking',[HomeController::class,'orderTracking'])->name('cartpro.order_tracking');
        Route::post('/order-tracking-find',[HomeController::class,'orderTrackingFind'])->name('cartpro.order_tracking_find');
        Route::get('/order-tracking-find-details/{reference_no}',[HomeController::class,'orderTrackingFindDetails'])->name('cartpro.order_tracking_find_details');
        Route::get('/default_lanuage_change/{id}',[HomeController::class,'defaultLanguageChange'])->name('cartpro.default_language_change');
        Route::get('/currency-change/{currency_code}',[HomeController::class,'currencyChange'])->name('cartpro.currency_change');

        // Route::get('/keyword_hit',[HomeController::class,'test'])->name('cartpro.keyword_hit');

        Route::post('/search-product',[HomeController::class,'searchProduct'])->name('cartpro.search_product');
        //Set Cookie
        Route::get('/set_cookie',[HomeController::class,'setCookie'])->name('cartpro.set_cookie');

        //Brand and Brand Wise Products
        Route::get('/brands',[BrandProductController::class,'brands'])->name('cartpro.brands');
        Route::get('/brand/{brand_slug}',[BrandProductController::class,'brandWiseProducts'])->name('cartpro.brand.products');

        //Shop and Shop Wise Products
        Route::get('/shop',[ShopProductController::class,'index'])->name('cartpro.shop');
        Route::get('limit_shop_products_show',[ShopProductController::class,'limitShopProductShow'])->name('cartpro.limit_shop_product_show');
        Route::get('/shop_products_show_sortby',[ShopProductController::class,'shopProductsShowSortby'])->name('cartpro.shop_products_show_sortby');
        Route::get('/shop/sidebar_filter',[ShopProductController::class,'shopProductsSidebarFilter'])->name('cartpro.shop_sidebar_filter');

         //Daily Deals Products
         Route::get('/daily-deals',[DailyDealsController::class,'index'])->name('cartpro.daily_deals');


        Route::get('product/{product_slug}/{category_id}',[HomeController::class,'product_details'])->name('cartpro.product_details');
        Route::get('data_ajax_search',[HomeController::class,'dataAjaxSearch'])->name('cartpro.data_ajax_search');

        //Category Wise Products
        Route::get('/all-categories',[CategoryProductController::class,'allCategogry'])->name('cartpro.all_categorgies');
        Route::get('/category/{slug}',[CategoryProductController::class,'categoryWiseProducts'])->name('cartpro.category_wise_products');
        Route::get('limit_category_products_show',[CategoryProductController::class,'limitCategoryProductShow'])->name('cartpro.limit_category_product_show');
        Route::get('/category_sortby_condition',[CategoryProductController::class,'categoryWiseConditionProducts'])->name('cartpro.category_wise_products_condition');
        Route::get('/category_price_range/',[CategoryProductController::class,'categoryWisePriceRangeProducts'])->name('cartpro.category.price_range');
        Route::get('/category_filter_by_attribute_value/',[CategoryProductController::class,'categoryProductsFilterByAttributeValue'])->name('cartpro.category.filter_by_attribute_value');
        Route::get('/sidebar_filter/',[CategoryProductController::class,'categoryWiseSidebarFilter'])->name('cartpro.category.sidebar_filter');


        Route::get('/tag/{slug}',[TagProductController::class,'tagWiseProducts'])->name('tag_wise_products');


        //Cart
        Route::prefix('/cart')->group(function () {
            Route::post('/add', [CartController::class,'productAddToCart'])->name('product.add_to_cart');
            Route::get('/view-details', [CartController::class,'cartViewDetails'])->name('cart.view_details');
            Route::get('/remove', [CartController::class,'cartRomveById'])->name('cart.remove');
            Route::get('/quantity_change', [CartController::class,'cartQuantityChange'])->name('cart.quantity_change');
            Route::get('/shipping_charge', [CartController::class,'shippingCharge'])->name('cart.shipping_charge');
            // Route::post('/checkout', [CartController::class,'checkout'])->name('cart.checkout');
            Route::get('/checkout', [CartController::class,'checkout'])->name('cart.checkout');
            Route::get('/apply_coupon', [CartController::class,'applyCoupon'])->name('cart.apply_coupon');
            Route::get('/country_wise_tax', [CartController::class,'countryWiseTax'])->name('cart.country_wise_tax');
            Route::get('/empty',function(){
                return view('frontend.pages.cart_empty_page');
            });
        });


        Route::post('newslatter/store',[HomeController::class,'newslatterStore'])->name('cartpro.newslatter_store');
        //payment -Paypal
        Route::get('order-store',[Frontend\OrderController::class,'orderStore'])->name('order.store');
        //payment -Stripe
        Route::post('/stripe/payment',[Frontend\OrderController::class,'handlePost'])->name('stripe.payment');
        //Cash On Delivery
        Route::post('/order/cash_on_delivery',[Frontend\OrderController::class,'cashOnDeliveryStore'])->name('order.cash_on_delivery');

        //Success Pages
        Route::get('/payment_success',function(){
            return view('frontend.pages.payment_success');
        });
        //Cancel Pages
        Route::get('/order_cancel',function(){
            return view('frontend.pages.order_cancel');
        });


        //Wishlist
        Route::prefix('/wishlist')->group(function () {
            Route::get('/',[WishlistController::class,'index'])->name('wishlist.index');
            Route::get('/add',[WishlistController::class,'addToWishlist'])->name('wishlist.add');
            Route::get('/remove',[WishlistController::class,'removeToWishlist'])->name('wishlist.remove');
        });

        Route::get('page/{page_slug}',[FrontendPageController::class,'pageShow'])->name('page.Show');

        //User Section
        Route::group(['prefix' => '/user_account','middleware'=>'customer_check'], function () {
            Route::get('/dashboard',[UserAccountController::class,'userAccount'])->name('user_account')->middleware('customer_check');
            Route::post('/update',[UserAccountController::class,'userProfileUpdate'])->name('user_profile_update');
            Route::post('/logout',[UserAccountController::class,'userLogout'])->name('user_logout');
            Route::get('/order/history',[UserAccountController::class,'orderHistory'])->name('user.order.history');
            Route::get('/order/history/details/{reference_no}',[UserAccountController::class,'orderHistoryDetails'])->name('user.order.history.details');

            //Billing Address
            Route::prefix('billing_addrees')->group(function () {
                Route::get('/',[UserBillingAddressController::class,'index'])->name('billing_addrees.index');
                Route::post('/store',[UserBillingAddressController::class,'store'])->name('billing_addrees.store');
                Route::post('/update/{id}',[UserBillingAddressController::class,'update'])->name('billing_addrees.update');
                Route::get('/delete/{id}',[UserBillingAddressController::class,'destroy'])->name('billing_addrees.delete');
            });

            //Shipping Address
            Route::prefix('shipping_addrees')->group(function () {
                Route::get('/',[UserShippingAddressController::class,'index'])->name('shipping_addrees.index');
                Route::post('/store',[UserShippingAddressController::class,'store'])->name('shipping_addrees.store');
                Route::post('/update/{id}',[UserShippingAddressController::class,'update'])->name('shipping_addrees.update');
                Route::get('/delete/{id}',[UserShippingAddressController::class,'destroy'])->name('shipping_addrees.delete');
            });
        });
        Route::post('/review/store',[HomeController::class,'reviewStore'])->name('review.store');



        /*
        |------------------------------------------------------------
        |Payment
        |------------------------------------------------------------
        */
        //Payment For All
        Route::post('/payment/process',[Frontend\PaymentController::class,'paymentProcees'])->name('payment.process');
        Route::post('/payment/{payment_method}/pay',[Frontend\PaymentController::class,'paymentPayPage'])->name('payment.pay.page');
        Route::post('/payment/{payment_method}/pay/confirm',[Frontend\PaymentController::class,'paymentPayConfirm'])->name('payment.pay.confirm');
        Route::post('/payment/{payment_method}/pay/cancel',[Frontend\PaymentController::class,'paymentPayCancel'])->name('payment.pay.cancel');
        //Paystack
        Route::get('/payment/paystack/pay/callback', [Frontend\PaymentController::class,'handleGatewayCallback'])->name('payment.pay.callback');



        // SSLCOMMERZ
        Route::post('/success', [Frontend\PaymentController::class, 'sslCommerzSuccess']);
        Route::post('/fail', [Frontend\PaymentController::class, 'sslCommerzFail']);
        Route::post('/cancel', [Frontend\PaymentController::class, 'sslCommerzCancel']);
        Route::post('/ipn', [Frontend\PaymentController::class, 'ipn']);


        Route::post('/payment/process/paypal-success',[Frontend\PaymentController::class,'paymentProceesPaypalSucccess'])->name('payment.process.paypal.done');


        // SSLCOMMERZ Start Default
        Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
        Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

        Route::post('/payment/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
        Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
        //SSLCOMMERZ END

        //Paypal
        Route::post('/paypal/create',[Frontend\PaymentController::class,'paypal-create']);





        //Google Login
        Route::get('/login/google', [Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('/login/google/callback', [Auth\LoginController::class, 'handleGoogleCallback']);

        //Facebook Login
        Route::get('/login/facebook', [Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
        Route::get('/login/facebook/callback', [Auth\LoginController::class, 'handleFacebookCallback']);

        //Github Login
        Route::get('/login/github', [Auth\LoginController::class, 'redirectToGithub'])->name('login.github');
        Route::get('/login/github/callback', [Auth\LoginController::class, 'handleGithubCallback']);

        //Socolite Tutorial
        //https://www.youtube.com/watch?v=jIckLu1cKew
    });
});
