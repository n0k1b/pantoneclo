<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeSetController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CurrencyRateController;
use App\Http\Controllers\Admin\DeveloperSectionController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\FaqTypeController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LocaleFileController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingLocationController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StoreFrontController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\API\ClientAutoUpdateController;
use App\Http\Controllers\Frontend\BrandProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryProductController;
use App\Http\Controllers\Frontend\DailyDealsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\ShopProductController;
use App\Http\Controllers\Frontend\SslCommerzPaymentController;
use App\Http\Controllers\Frontend\TagProductController;
use App\Http\Controllers\Frontend\UserAccountController;
use App\Http\Controllers\Frontend\UserBillingAddressController;
use App\Http\Controllers\Frontend\UserShippingAddressController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Middleware\DemoCheck;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth as DefaultAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

DefaultAuth::routes();

Route::get('/cart-data', function () {
    return Cart::content();
    // return Cart::weight();
    $cart_content = Cart::content();
    return $cart_content['c2857b6429ca61a9430c069b6f87f54e']->options->attributes['name'][0];
});


Route::group(['middleware' => ['XSS', 'set_locale', 'maintenance_mode']], function () {
    /*
    |--------------------------------------------------------------------------
    | User Section
    |--------------------------------------------------------------------------
     */

    Route::get('/login', [Auth\LoginController::class, 'showCustomerLoginForm'])->name('customer_login_form');
    Route::post('/customer/login', [Auth\LoginController::class, 'customerLogin'])->name('customer.login');
    Route::post('/customer/register', [Frontend\RegisterController::class, 'customerRegister'])->name('customer.register');
    Route::get('/password/reset', [Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('customer.password.request');
    Route::post('/password/email', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email'); //Here


    Route::group(['namespace' => 'Frontend'], function () {


        Route::get('/', [HomeController::class, 'index'])->name('cartpro.home');

        Route::get('/change-for/{text}', [HomeController::class, 'changeForDemoOrClient']);

        //FAQ
        Route::get('/faq', [HomeController::class, 'faq'])->name('cartpro.faq');
        Route::get('/search-faq', [HomeController::class, 'searchFAQ'])->name('cartpro.search-faq');

        //Contact
        Route::get('/contact', [HomeController::class, 'contact'])->name('cartpro.contact');
        Route::post('/contact-message', [HomeController::class, 'contactMessage'])->name('cartpro.contact.message');
        //About Us
        Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('cartpro.about_us');

        //Order Tracking
        Route::get('/order-tracking', [HomeController::class, 'orderTracking'])->name('cartpro.order_tracking');
        Route::post('/order-tracking-find', [HomeController::class, 'orderTrackingFind'])->name('cartpro.order_tracking_find');
        Route::get('/order-tracking-find-details/{reference_no}', [HomeController::class, 'orderTrackingFindDetails'])->name('cartpro.order_tracking_find_details');
        Route::get('/default_lanuage_change/{id}', [HomeController::class, 'defaultLanguageChange'])->name('cartpro.default_language_change');
        Route::get('/currency-change/{currency_code}', [HomeController::class, 'currencyChange'])->name('cartpro.currency_change');

        // Route::get('/keyword_hit',[HomeController::class,'test'])->name('cartpro.keyword_hit');

        Route::post('/search-product', [HomeController::class, 'searchProduct'])->name('cartpro.search_product');
        //Set Cookie
        Route::get('/set_cookie', [HomeController::class, 'setCookie'])->name('cartpro.set_cookie');

        //Brand and Brand Wise Products
        Route::get('/brands', [BrandProductController::class, 'brands'])->name('cartpro.brands');
        Route::get('/brand/{brand_slug}', [BrandProductController::class, 'brandWiseProducts'])->name('cartpro.brand.products');

        //Shop and Shop Wise Products
        Route::get('/shop', [ShopProductController::class, 'index'])->name('cartpro.shop');
        Route::get('limit_shop_products_show', [ShopProductController::class, 'limitShopProductShow'])->name('cartpro.limit_shop_product_show');
        Route::get('/shop_products_show_sortby', [ShopProductController::class, 'shopProductsShowSortby'])->name('cartpro.shop_products_show_sortby');
        Route::get('/shop/sidebar_filter', [ShopProductController::class, 'shopProductsSidebarFilter'])->name('cartpro.shop_sidebar_filter');

        //Daily Deals Products
        Route::get('/daily-deals', [DailyDealsController::class, 'index'])->name('cartpro.daily_deals');

        Route::get('product/{product_slug}/{category_id}', [HomeController::class, 'product_details'])->name('cartpro.product_details');


        Route::get('data_ajax_search', [HomeController::class, 'dataAjaxSearch'])->name('cartpro.data_ajax_search');

        //Category Wise Products
        Route::get('/all-categories', [CategoryProductController::class, 'allCategogry'])->name('cartpro.all_categorgies');
        Route::get('/category/{slug}', [CategoryProductController::class, 'categoryWiseProducts'])->name('cartpro.category_wise_products');
        Route::get('limit_category_products_show', [CategoryProductController::class, 'limitCategoryProductShow'])->name('cartpro.limit_category_product_show');
        Route::get('/category_sortby_condition', [CategoryProductController::class, 'categoryWiseConditionProducts'])->name('cartpro.category_wise_products_condition');
        Route::get('/category_price_range/', [CategoryProductController::class, 'categoryWisePriceRangeProducts'])->name('cartpro.category.price_range');
        Route::get('/category_filter_by_attribute_value/', [CategoryProductController::class, 'categoryProductsFilterByAttributeValue'])->name('cartpro.category.filter_by_attribute_value');
        Route::get('/sidebar_filter/', [CategoryProductController::class, 'categoryWiseSidebarFilter'])->name('cartpro.category.sidebar_filter');

        Route::get('/tag/{slug}', [TagProductController::class, 'tagWiseProducts'])->name('tag_wise_products');

        //Cart
        Route::prefix('/cart')->group(function () {
            Route::post('/add', [CartController::class, 'productAddToCart'])->name('product.add_to_cart');
            Route::get('/view-details', [CartController::class, 'cartViewDetails'])->name('cart.view_details');
            Route::get('/remove', [CartController::class, 'cartRomveById'])->name('cart.remove');
            Route::get('/quantity_change', [CartController::class, 'cartQuantityChange'])->name('cart.quantity_change');
            Route::get('/shipping_charge', [CartController::class, 'shippingCharge'])->name('cart.shipping_charge');
            // Route::post('/checkout', [CartController::class,'checkout'])->name('cart.checkout');
            Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
            Route::get('/apply_coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');
            Route::get('/country_wise_tax', [CartController::class, 'countryWiseTax'])->name('cart.country_wise_tax');
            Route::get('/empty', function () {
                return view('frontend.pages.cart_empty_page');
            });
        });

        Route::post('newslatter/store', [HomeController::class, 'newslatterStore'])->name('cartpro.newslatter_store');
        //payment -Paypal
        Route::get('order-store', [Frontend\OrderController::class, 'orderStore'])->name('order.store');
        //payment -Stripe
        Route::post('/stripe/payment', [Frontend\OrderController::class, 'handlePost'])->name('stripe.payment');
        //Cash On Delivery
        Route::post('/order/cash_on_delivery', [Frontend\OrderController::class, 'cashOnDeliveryStore'])->name('order.cash_on_delivery');

        //Success Pages
        Route::get('/payment_success', function () {
            return view('frontend.pages.payment_success');
        });
        //Cancel Pages
        Route::get('/order_cancel', function () {
            return view('frontend.pages.order_cancel');
        });

        //Wishlist
        Route::prefix('/wishlist')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::get('/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
            Route::get('/remove', [WishlistController::class, 'removeToWishlist'])->name('wishlist.remove');
        });

        Route::get('page/{page_slug}', [FrontendPageController::class, 'pageShow'])->name('page.Show');

        //User Section
        Route::group(['prefix' => '/user_account', 'middleware' => 'customer_check'], function () {
            Route::get('/dashboard', [UserAccountController::class, 'userAccount'])->name('user_account')->middleware('customer_check');
            Route::post('/update', [UserAccountController::class, 'userProfileUpdate'])->name('user_profile_update');
            Route::post('/logout', [UserAccountController::class, 'userLogout'])->name('user_logout');
            Route::get('/order/history', [UserAccountController::class, 'orderHistory'])->name('user.order.history');
            Route::get('/order/history/details/{reference_no}', [UserAccountController::class, 'orderHistoryDetails'])->name('user.order.history.details');

            //Billing Address
            Route::prefix('billing_addrees')->group(function () {
                Route::get('/', [UserBillingAddressController::class, 'index'])->name('billing_addrees.index');
                Route::post('/store', [UserBillingAddressController::class, 'store'])->name('billing_addrees.store');
                Route::post('/update/{id}', [UserBillingAddressController::class, 'update'])->name('billing_addrees.update');
                Route::get('/delete/{id}', [UserBillingAddressController::class, 'destroy'])->name('billing_addrees.delete');
            });

            //Shipping Address
            Route::prefix('shipping_addrees')->group(function () {
                Route::get('/', [UserShippingAddressController::class, 'index'])->name('shipping_addrees.index');
                Route::post('/store', [UserShippingAddressController::class, 'store'])->name('shipping_addrees.store');
                Route::post('/update/{id}', [UserShippingAddressController::class, 'update'])->name('shipping_addrees.update');
                Route::get('/delete/{id}', [UserShippingAddressController::class, 'destroy'])->name('shipping_addrees.delete');
            });
        });
        Route::post('/review/store', [HomeController::class, 'reviewStore'])->name('review.store');

        /*
        |------------------------------------------------------------
        |Payment
        |------------------------------------------------------------
         */
        //Payment For All
        Route::post('/payment/process', [Frontend\PaymentController::class, 'paymentProcees'])->name('payment.process');
        Route::post('/payment/{payment_method}/pay', [Frontend\PaymentController::class, 'paymentPayPage'])->name('payment.pay.page');
        Route::post('/payment/{payment_method}/pay/confirm', [Frontend\PaymentController::class, 'paymentPayConfirm'])->name('payment.pay.confirm');
        Route::post('/payment/{payment_method}/pay/cancel', [Frontend\PaymentController::class, 'paymentPayCancel'])->name('payment.pay.cancel');
        //Paystack
        Route::get('/payment/paystack/pay/callback', [Frontend\PaymentController::class, 'handleGatewayCallback'])->name('payment.pay.callback');

        // SSLCOMMERZ
        Route::post('/success', [Frontend\PaymentController::class, 'sslCommerzSuccess']);
        Route::post('/fail', [Frontend\PaymentController::class, 'sslCommerzFail']);
        Route::post('/cancel', [Frontend\PaymentController::class, 'sslCommerzCancel']);
        Route::post('/ipn', [Frontend\PaymentController::class, 'ipn']);

        Route::post('/payment/process/paypal-success', [Frontend\PaymentController::class, 'paymentProceesPaypalSucccess'])->name('payment.process.paypal.done');

        // SSLCOMMERZ Start Default
        Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
        Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

        Route::post('/payment/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
        Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);
        //SSLCOMMERZ END

        //Paypal
        Route::post('/paypal/create', [Frontend\PaymentController::class, 'paypal-create']);

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

    /*
    |--------------------------------------------------------------------------
    | Admin Section
    |--------------------------------------------------------------------------
     */

    Route::get('/admin', [Auth\LoginController::class, 'showAdminLoginForm'])->name('admin');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login');

    Route::group(['prefix' => 'admin', 'middleware' => 'admin_check'], function () {
        Route::group(['namespace' => 'Admin'], function () {

            Route::get('/new-release', [ClientAutoUpdateController::class, 'index'])->name('new-release'); // New Release
            Route::get('/bugs', [ClientAutoUpdateController::class, 'bugUpdatePage'])->name('bug-update-page'); // Bugs

            //Admin Dashboard
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('dashboard/chart', [AdminController::class, 'chart'])->name('admin.dashboard.chart');
            Route::get('/google_analytics', [AdminController::class, 'googleAnalytics'])->name('admin.googleAnalytics');
            Route::get('/logout', [AdminController::class, 'Logout'])->name('admin.logout');

            // Notification Related
            Route::group(['prefix' => 'notification'], function () {
                Route::get('/markAsRead', [NotificationController::class, 'markAsReadNotification'])->name('markAsRead');
                Route::get('/all/notifications', [NotificationController::class, 'allNotifications'])->name('seeAllNotification');
                Route::get('clearAll', [NotificationController::class, 'clearAll'])->name('clearAll');
            });

            //Admin Profile
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', [ProfileController::class, 'index'])->name('admin.profile');
                Route::post('/update', [ProfileController::class, 'profileUpdate'])->name('admin.profile_update');

            });

            //--Category--
            Route::group(['prefix' => '/categories'], function () {
                Route::get('/', [CategoryController::class, 'index'])->name('admin.category');
                Route::get('/datatable', [CategoryController::class, 'dataTable'])->name('admin.category.datatable');
                Route::post('/store', [CategoryController::class, 'store'])->name('admin.category.store');
                Route::get('/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
                Route::post('updateCategory', [CategoryController::class, 'categoryUpdate'])->name('category_list.update'); //Remove Later
                Route::post('update', [CategoryController::class, 'update'])->name('admin.category.update');
                Route::get('/active', [CategoryController::class, 'active'])->name('admin.category.active');
                Route::get('/inactive', [CategoryController::class, 'inactive'])->name('admin.category.inactive');
                Route::get('/bulk_action', [CategoryController::class, 'bulkAction'])->name('admin.category.bulk_action');
                Route::get('/delete', [CategoryController::class, 'delete'])->name('admin.category.delete');
                Route::get('/bulk_delete', [CategoryController::class, 'bulkDelete'])->name('admin.category.bulk_delete');
            });

            //brands
            Route::group(['prefix' => '/brands'], function () {
                Route::get('/', [BrandController::class, 'index'])->name('admin.brand');
                Route::post('/store', [BrandController::class, 'store'])->name('admin.brand.store')->middleware(['demo_check', 'checkAjax']);
                Route::get('/brand/{id}', [BrandController::class, 'brandEdit'])->name('admin.brand.edit');
                Route::post('/update/{id}', [BrandController::class, 'brandUpdate'])->name('brand.update');
                Route::get('/active', [BrandController::class, 'active'])->name('admin.brand.active');
                Route::get('/inactive', [BrandController::class, 'inactive'])->name('admin.brand.inactive');
                Route::get('/delete', [BrandController::class, 'delete'])->name('admin.brand.delete')->middleware(['demo_check', 'checkAjax']);
                Route::get('/bulk_action', [BrandController::class, 'bulkAction'])->name('admin.brand.bulk_action');
            });

            //Attribute Set
            Route::group(['prefix' => 'attribute-sets'], function () {
                Route::get('/', [AttributeSetController::class, 'index'])->name('admin.attribute_set.index');
                Route::get('/datatable', [AttributeSetController::class, 'datatable'])->name('admin.attribute_set.datatable');
                Route::post('/store', [AttributeSetController::class, 'store'])->name('admin.attribute_set.store');
                Route::get('/edit', [AttributeSetController::class, 'edit'])->name('admin.attribute_set.edit');
                Route::post('/update', [AttributeSetController::class, 'update'])->name('admin.attribute_set.update');
                Route::get('/active', [AttributeSetController::class, 'active'])->name('admin.attribute_set.active');
                Route::get('/inactive', [AttributeSetController::class, 'inactive'])->name('admin.attribute_set.inactive');
                Route::get('/bulk_action', [AttributeSetController::class, 'bulkAction'])->name('admin.attribute_set.bulk_action');
                Route::get('/destroy', [AttributeSetController::class, 'destroy'])->name('admin.attribute_set.destroy');
            });

            //Attributes
            Route::group(['prefix' => 'attributes'], function () {
                Route::get('/', [AttributeController::class, 'index'])->name('admin.attribute.index');
                Route::get('/datatable', [AttributeController::class, 'datatable'])->name('admin.attribute.datatable');
                Route::get('/create', [AttributeController::class, 'create'])->name('admin.attribute.create');
                Route::post('/store', [AttributeController::class, 'store'])->name('admin.attribute.store');
                Route::get('/edit/{id}', [AttributeController::class, 'edit'])->name('admin.attribute.edit');
                Route::post('/update/{id}', [AttributeController::class, 'update'])->name('admin.attribute.update');
                Route::get('/active', [AttributeController::class, 'active'])->name('admin.attribute.active');
                Route::get('/inactive', [AttributeController::class, 'inactive'])->name('admin.attribute.inactive');
                Route::get('/destroy', [AttributeController::class, 'destroy'])->name('admin.attribute.destroy');
                Route::get('/bulk_action', [AttributeController::class, 'bulkAction'])->name('admin.attribute.bulk_action');
                //Attribute's Values
                Route::get('/get_attribute_values', [AttributeController::class, 'getAttributeValues'])->name('admin.attribute.get_attribute_values');
            });

            //Tags
            Route::group(['prefix' => 'tags'], function () {
                Route::get('/', [TagController::class, 'index'])->name('admin.tag.index');
                Route::get('/datatable', [TagController::class, 'dataTable'])->name('admin.tag.datatable');
                Route::post('/store', [TagController::class, 'store'])->name('admin.tag.store');
                Route::get('/edit', [TagController::class, 'edit'])->name('admin.tag.edit');
                Route::post('/update', [TagController::class, 'update'])->name('admin.tag.update');
                Route::get('/active', [TagController::class, 'active'])->name('admin.tag.active');
                Route::get('/inactive', [TagController::class, 'inactive'])->name('admin.tag.inactive');
                Route::get('/destroy', [TagController::class, 'destroy'])->name('admin.tag.destroy');
                Route::get('/bulk_action', [TagController::class, 'bulkAction'])->name('admin.tag.bulk_action');
            });

            //Products
            Route::group(['prefix' => 'products', 'middleware' => 'demo_check'], function () {
                Route::get('/', [ProductController::class, 'index'])->name('admin.products.index')->withoutMiddleware([DemoCheck::class]);
                Route::get('/dataTable', [ProductController::class, 'dataTable'])->name('admin.products.dataTable')->middleware('checkAjax');
                Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create')->withoutMiddleware([DemoCheck::class]);
                Route::post('/store', [ProductController::class, 'store'])->name('admin.products.store');
                Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('admin.products.edit')->withoutMiddleware([DemoCheck::class]);
                Route::get('/edit/{id}/attribute-inventory', [ProductController::class, 'attributeWiseInventory'])->name('admin.products.attribute_inventory')->withoutMiddleware([DemoCheck::class]);
                Route::post('/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
                Route::get('/active', [ProductController::class, 'active'])->name('admin.products.active');
                Route::get('/inactive', [ProductController::class, 'inactive'])->name('admin.products.inactive');
                Route::get('/delete', [ProductController::class, 'delete'])->name('admin.products.delete');
                Route::get('/bulk_action', [ProductController::class, 'bulkAction'])->name('admin.products.bulk_action');
            });

            //Review
            Route::prefix('review')->group(function () {
                Route::get('/', [ReviewController::class, 'index'])->name('admin.review.index');
                Route::get('/edit', [ReviewController::class, 'edit'])->name('admin.review.edit');
                Route::post('/update', [ReviewController::class, 'update'])->name('admin.review.update');
                Route::get('/delete', [ReviewController::class, 'delete'])->name('admin.review.delete')->middleware('demo_check');
            });

            //Sales
            Route::group(['prefix' => 'order'], function () {
                Route::get('/', [OrderController::class, 'index'])->name('admin.order.index');
                Route::get('/details/{reference_no}', [OrderController::class, 'orderDetails'])->name('admin.order.details');
                Route::get('/status', [OrderController::class, 'orderStatus'])->name('admin.order.status'); //Will be removed later
                Route::get('/{order_id}/{status}', [OrderController::class, 'orderStatusChange'])->name('admin.order.status_change');
                Route::post('/date', [OrderController::class, 'orderDate'])->name('admin.order.order_date');
                Route::post('/delivery-time', [OrderController::class, 'orderDeliveryTime'])->name('admin.order.delivery_time');
                Route::get('/download/invoice/{reference_no}', [OrderController::class, 'downloadInvoice'])->name('admin.order.download-invoice');
                Route::get('/delete', [OrderController::class, 'delete'])->name('admin.order.delete')->middleware('demo_check');
            });
            Route::get('/transaction', [OrderController::class, 'transactionIndex'])->name('admin.transaction.index')->middleware('demo_check');

            //Flash Sale
            Route::group(['prefix' => 'flash-sales'], function () {
                Route::get('/', [FlashSaleController::class, 'index'])->name('admin.flash_sale.index');
                Route::get('/create', [FlashSaleController::class, 'create'])->name('admin.flash_sale.create');
                Route::post('/store', [FlashSaleController::class, 'store'])->name('admin.flash_sale.store');
                Route::get('/edit/{id}', [FlashSaleController::class, 'edit'])->name('admin.flash_sale.edit');
                Route::post('/update/{id}', [FlashSaleController::class, 'update'])->name('admin.flash_sale.update');
                Route::get('/active', [FlashSaleController::class, 'active'])->name('admin.flash_sale.active');
                Route::get('/inactive', [FlashSaleController::class, 'inactive'])->name('admin.flash_sale.inactive');
                Route::get('/delete', [FlashSaleController::class, 'delete'])->name('admin.flash_sale.delete');
                Route::get('/bulk_action', [FlashSaleController::class, 'bulkAction'])->name('admin.flash_sale.bulk_action');
            });

            //Coupons
            Route::group(['prefix' => 'coupons'], function () {
                Route::get('/', [CouponController::class, 'index'])->name('admin.coupon.index');
                Route::get('/create', [CouponController::class, 'create'])->name('admin.coupon.create');
                Route::post('/store', [CouponController::class, 'store'])->name('admin.coupon.store');
                Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('admin.coupon.edit');
                Route::post('/update', [CouponController::class, 'update'])->name('admin.coupon.update');
                Route::get('/active', [CouponController::class, 'active'])->name('admin.coupon.active');
                Route::get('/inactive', [CouponController::class, 'inactive'])->name('admin.coupon.inactive');
                Route::get('/delete', [CouponController::class, 'delete'])->name('admin.coupon.delete');
                Route::get('/bulk_action', [CouponController::class, 'bulkAction'])->name('admin.coupon.bulk_action');
            });

            Route::group(['prefix' => 'online-store'], function () {

                //Pages
                Route::group(['prefix' => 'pages'], function () {
                    Route::get('/', [PageController::class, 'index'])->name('admin.page.index');
                    Route::get('/datatable', [PageController::class, 'dataTable'])->name('admin.page.datatable')->middleware('checkAjax');
                    Route::post('/store', [PageController::class, 'store'])->name('admin.page.store')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/edit', [PageController::class, 'edit'])->name('admin.page.edit');
                    Route::post('/update', [PageController::class, 'update'])->name('admin.page.update')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/active', [PageController::class, 'active'])->name('admin.page.active')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/inactive', [PageController::class, 'inactive'])->name('admin.page.inactive')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/destroy', [PageController::class, 'destroy'])->name('admin.page.destroy')->middleware(['demo_check', 'checkAjax']);
                    Route::get('bulk_action', [PageController::class, 'bulkAction'])->name('admin.page.bulk_action')->middleware(['demo_check', 'checkAjax']);
                });

                //--Menus--
                Route::group(['prefix' => 'menu'], function () {
                    Route::get('/', [MenuController::class, 'index'])->name('admin.menu');
                    Route::post('/store', [MenuController::class, 'store'])->name('admin.menu.store');
                    Route::get('/edit-test', [MenuController::class, 'edit'])->name('admin.menu.edit');
                    Route::post('/update-test', [MenuController::class, 'update'])->name('admin.menu.update');
                    Route::get('/active-test', [MenuController::class, 'active'])->name('admin.menu.active');
                    Route::get('/inactive-test', [MenuController::class, 'inactive'])->name('admin.menu.inactive');
                    Route::get('/delete', [MenuController::class, 'delete'])->name('admin.menu.delete');
                    Route::get('test/bulk_action', [MenuController::class, 'bulkAction'])->name('admin.menu.bulk_action');
                    //--Menus Items--
                    Route::get('/{menuId}/items', [MenuItemController::class, 'index'])->name('admin.menu.menu_item');
                    Route::get('/items/data-fetch-by-type', [MenuItemController::class, 'dataFetchByType'])->name('admin.menu.menu_item.data-fetch-by-type');
                    Route::post('/items/store', [MenuItemController::class, 'store'])->name('admin.menu.menu_item.store');
                    Route::get('/edit', [MenuItemController::class, 'edit'])->name('admin.menu.menu_item.edit');
                    Route::post('/update', [MenuItemController::class, 'update'])->name('admin.menu.menu_item.update');
                    Route::get('/active', [MenuItemController::class, 'active'])->name('admin.menu.menu_item.active');
                    Route::get('/inactive', [MenuItemController::class, 'inactive'])->name('admin.menu.menu_item.inactive');
                    Route::get('/items/delete/{id}', [MenuItemController::class, 'delete'])->name('admin.menu.menu_item.delete'); //Not Deleted
                    Route::get('/bulk_action', [MenuItemController::class, 'bulkAction'])->name('admin.menu.menu_item.bulk_action');
                });

                //Store Front
                Route::group(['prefix' => 'storefront'], function () {
                    Route::get('/', [StoreFrontController::class, 'index'])->name('admin.storefront');
                    Route::post('/general/store', [StoreFrontController::class, 'generalStore'])->name('admin.storefront.general.store');
                    Route::post('/menu/store', [StoreFrontController::class, 'menuStore'])->name('admin.storefront.menu.store');
                    Route::post('/social_link/store', [StoreFrontController::class, 'socialLinkStore'])->name('admin.storefront.social_link.store');
                    Route::post('/feature/store', [StoreFrontController::class, 'featureStore'])->name('admin.storefront.feature.store');
                    Route::post('/logo/store', [StoreFrontController::class, 'logoStore'])->name('admin.storefront.logo.store');
                    Route::post('/topBanner/store', [StoreFrontController::class, 'topBannerStore'])->name('admin.storefront.topBanner.store');
                    Route::post('/footer/store', [StoreFrontController::class, 'footerStore'])->name('admin.storefront.footer.store');
                    Route::post('/newletter/store', [StoreFrontController::class, 'newletterStore'])->name('admin.storefront.newletter.store');
                    Route::post('/product_page/store', [StoreFrontController::class, 'productPageStore'])->name('admin.storefront.product_page.store');
                    Route::post('/slider_banners/store', [StoreFrontController::class, 'sliderBannersStore'])->name('admin.storefront.slider_banners.store');
                    Route::post('/one_column_banner/store', [StoreFrontController::class, 'oneColumnBannerStore'])->name('admin.storefront.one_column_banner.store');
                    Route::post('/two_column_banners/store', [StoreFrontController::class, 'twoColumnBannersStore'])->name('admin.storefront.two_column_banners.store');
                    Route::post('/three_column_banners/store', [StoreFrontController::class, 'threeColumnBannersStore'])->name('admin.storefront.three_column_banners.store');
                    Route::post('/three_column_full_width_banners/store', [StoreFrontController::class, 'threeColumnFllWidthBannersStore'])->name('admin.storefront.three_column_full_width_banners.store');
                    Route::post('/top_brands/store', [StoreFrontController::class, 'topBrandsStore'])->name('admin.storefront.top_brands.store');
                    Route::post('/top_categories/store', [StoreFrontController::class, 'topCategoriesStore'])->name('admin.storefront.top_categories.store');
                    Route::post('/flash_sale_and_vertical_products/store', [StoreFrontController::class, 'flashSaleAndVerticalProductsStore'])->name('admin.storefront.flash_sale_and_vertical_products.store');
                    Route::post('/product_tab_one/store', [StoreFrontController::class, 'productTabsOneStore'])->name('admin.storefront.product_tab_one.store');
                    Route::post('/product_tab_two/store', [StoreFrontController::class, 'productTabsTwoStore'])->name('admin.storefront.product_tab_two.store');
                });

                //--Slider--
                Route::group(['prefix' => 'sliders'], function () {
                    Route::get('/', [SliderController::class, 'index'])->name('admin.slider');
                    Route::get('/datatable', [SliderController::class, 'dataTable'])->name('admin.slider.datatable');
                    Route::get('/data-fetch-by-type', [SliderController::class, 'dataFetchByType'])->name('admin.slider.data-fetch-by-type');
                    Route::post('/store', [SliderController::class, 'store'])->name('admin.slider.store')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/edit', [SliderController::class, 'edit'])->name('admin.slider.edit');
                    Route::post('/update', [SliderController::class, 'update'])->name('admin.slider.update')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/active', [SliderController::class, 'active'])->name('admin.slider.active')->middleware('demo_check');
                    Route::get('/inactive', [SliderController::class, 'inactive'])->name('admin.slider.inactive')->middleware('demo_check');
                    Route::get('/destroy', [SliderController::class, 'destroy'])->name('admin.slider.destroy')->middleware('demo_check');
                    Route::get('test/bulk_action', [SliderController::class, 'bulkAction'])->name('admin.slider.bulk_action')->middleware('demo_check');
                });
            });

            Route::group(['prefix' => 'localization'], function () {
                //Taxes
                Route::group(['prefix' => 'taxes'], function () {
                    Route::get('/', [TaxController::class, 'index'])->name('admin.tax.index');
                    Route::get('/datatable', [TaxController::class, 'datatable'])->name('admin.tax.datatable')->middleware(['checkAjax']);
                    Route::post('/store', [TaxController::class, 'store'])->name('admin.tax.store')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/edit', [TaxController::class, 'edit'])->name('admin.tax.edit')->middleware(['checkAjax']);
                    Route::post('/update', [TaxController::class, 'update'])->name('admin.tax.update')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/active', [TaxController::class, 'active'])->name('admin.tax.active')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/inactive', [TaxController::class, 'inactive'])->name('admin.tax.inactive')->middleware(['demo_check', 'checkAjax']);
                    Route::get('/delete', [TaxController::class, 'delete'])->name('admin.tax.delete');
                    Route::get('/bulk_action', [TaxController::class, 'bulkAction'])->name('admin.tax.bulk_action')->middleware(['demo_check', 'checkAjax']);
                });
                //Currency Rates
                Route::group(['prefix' => 'currency_rates'], function () {
                    Route::get('/', [CurrencyRateController::class, 'index'])->name('admin.currency_rate.index');
                    Route::get('/edit', [CurrencyRateController::class, 'edit'])->name('admin.currency_rate.edit');
                    Route::post('/update', [CurrencyRateController::class, 'update'])->name('admin.currency_rate.update');
                });
            });

            //User
            Route::get('/user', [UserController::class, 'index'])->name('admin.user');
            Route::post('/insertUser', [UserController::class, 'store'])->name('user.store');
            Route::post('/updateUser', [UserController::class, 'update'])->name('user_list.update');
            Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user_edit');
            Route::get('/user/active', [UserController::class, 'active'])->name('admin.user.active');
            Route::get('/user/inactive', [UserController::class, 'inactive'])->name('admin.user.inactive');
            Route::get('/user/delete', [UserController::class, 'delete'])->name('admin.user.delete');
            Route::get('/user/bulk_action', [UserController::class, 'bulkAction'])->name('admin.user.bulk_action');

            //Roles
            Route::group(['prefix' => 'roles'], function () {
                Route::get('/', [RoleController::class, 'index'])->name('admin.role.index');
                Route::post('/store', [RoleController::class, 'store'])->name('admin.role.store');
                Route::get('/edit', [RoleController::class, 'edit'])->name('admin.role.edit');
                Route::post('/update', [RoleController::class, 'update'])->name('admin.role.update');
                Route::get('/active', [RoleController::class, 'active'])->name('admin.role.active');
                Route::get('/inactive', [RoleController::class, 'inactive'])->name('admin.role.inactive');
                Route::get('/delete', [RoleController::class, 'delete'])->name('admin.role.delete');
                Route::get('/bulk_action', [RoleController::class, 'bulkAction'])->name('admin.role.bulk_action');

                //--Permission--
                Route::get('/role-permission/{id}', [PermissionController::class, 'rolePermission'])->name('admin.rolePermission');
                Route::get('roles/permission_details/{id}', [PermissionController::class, 'permissionDetails'])->name('permissionDetails');
                Route::post('roles/permission', [PermissionController::class, 'set_permission'])->name('set_permission');
            });

            //Color
            Route::prefix('color')->group(function () {
                Route::get('/', [ColorController::class, 'index'])->name('admin.color.index');
                Route::post('/store', [ColorController::class, 'store'])->name('admin.color.store');
                Route::get('/edit', [ColorController::class, 'edit'])->name('admin.color.edit');
                Route::post('/update', [ColorController::class, 'update'])->name('admin.color.update');
                Route::get('/delete', [ColorController::class, 'delete'])->name('admin.color.delete');
            });

            //---- temporaray ---
            //Report
            Route::group(['prefix' => 'reports'], function () {
                Route::get('coupon', [ReportController::class, 'reportCoupon'])->name('admin.reports.coupon');
                Route::get('customer_orders', [ReportController::class, 'reportcustomerOrders'])->name('admin.reports.customer_orders');
                Route::get('product_stock_report', [ReportController::class, 'productStockReport'])->name('admin.reports.product_stock_report');
                Route::get('product_view_report', [ReportController::class, 'productViewReport'])->name('admin.reports.product_view_report');
                Route::get('sales_report', [ReportController::class, 'salesReport'])->name('admin.reports.sales_report');
                Route::get('search_report', [ReportController::class, 'searchReport'])->name('admin.reports.search_report');
                Route::get('shipping_report', [ReportController::class, 'shippingReport'])->name('admin.reports.shipping_report');
                Route::get('tax_report', [ReportController::class, 'taxReport'])->name('admin.reports.tax_report');
                Route::get('product_purchase_report', [ReportController::class, 'productPurchaseReport'])->name('admin.reports.product_purchase_report');
            });

            Route::get('report/today', [ReportController::class, 'todayReport'])->name('report.today');
            Route::get('report/this_week', [ReportController::class, 'thisWeekReport'])->name('report.this_week');
            Route::get('report/this_month', [ReportController::class, 'thisYearReport'])->name('report.this_year');
            Route::get('report/filter_report', [ReportController::class, 'filterReport'])->name('report.filter_report');
            //---- temporaray ---

            Route::group(['prefix' => 'setting'], function () {

                //Country
                Route::group(['prefix' => 'countries'], function () {
                    Route::get('/', [CountryController::class, 'index'])->name('admin.country.index');
                    Route::get('/datatable', [CountryController::class, 'dataTable'])->name('admin.country.datatable');
                    Route::post('/store', [CountryController::class, 'store'])->name('admin.country.store');
                    Route::get('/edit', [CountryController::class, 'edit'])->name('admin.country.edit');
                    Route::post('/update', [CountryController::class, 'update'])->name('admin.country.update');
                    Route::get('/destroy', [CountryController::class, 'destroy'])->name('admin.country.destroy');
                    Route::get('/bulk_action_delete', [CountryController::class, 'bulkActionDelete'])->name('admin.country.bulk_action_delete');
                });

                //Currency
                Route::group(['prefix' => 'currencies'], function () {
                    Route::get('/', [CurrencyController::class, 'index'])->name('admin.currency.index');
                    Route::get('/datatable', [CurrencyController::class, 'dataTable'])->name('admin.currency.datatable');
                    Route::post('/store', [CurrencyController::class, 'store'])->name('admin.currency.store');
                    Route::get('/edit', [CurrencyController::class, 'edit'])->name('admin.currency.edit');
                    Route::post('/update', [CurrencyController::class, 'update'])->name('admin.currency.update');
                    Route::get('/destroy', [CurrencyController::class, 'destroy'])->name('admin.currency.destroy');
                    Route::get('/bulk_action_delete', [CurrencyController::class, 'bulkActionDelete'])->name('admin.currency.bulk_action_delete');
                });

                //Other
                Route::group(['prefix' => 'others'], function () {
                    Route::get('/', [SettingController::class, 'index'])->name('admin.setting.index');
                    Route::post('/general/store', [SettingController::class, 'generalStoreOrUpdate'])->name('admin.setting.general.store_or_update');
                    Route::post('/home-page-seo/store', [SettingController::class, 'homePageSeoStoreOrUpdate'])->name('admin.setting.home_page_seo.store_or_update');
                    Route::post('/store_store', [SettingController::class, 'storeStoreOrUpdate'])->name('admin.setting.store.store_or_update');
                    Route::post('/currency/store', [SettingController::class, 'currencyStoreOrUpdate'])->name('admin.setting.currency.store_or_update');
                    Route::post('/sms/store', [SettingController::class, 'smsStoreOrUpdate'])->name('admin.setting.sms.store_or_update');
                    Route::post('/mail/store', [SettingController::class, 'mailStoreOrUpdate'])->name('admin.setting.mail.store_or_update');
                    Route::post('/newsletter/store', [SettingController::class, 'newsletterStoreOrUpdate'])->name('admin.setting.newsletter.store_or_update');
                    Route::post('/custom_css_js/store', [SettingController::class, 'customCssJsStoreOrUpdate'])->name('admin.setting.custom_css_js.store_or_update');
                    Route::post('/facebook/store', [SettingController::class, 'facebookStoreOrUpdate'])->name('admin.setting.facebook.store_or_update');
                    Route::post('/google/store', [SettingController::class, 'googleStoreOrUpdate'])->name('admin.setting.google.store_or_update');
                    Route::post('/github/store', [SettingController::class, 'githubStoreOrUpdate'])->name('admin.setting.github.store_or_update');
                    Route::post('/free_shipping/store', [SettingController::class, 'freeShippingStoreOrUpdate'])->name('admin.setting.free_shipping.store_or_update');
                    Route::post('/local_pickup/store', [SettingController::class, 'localPickupStoreOrUpdate'])->name('admin.setting.local_pickup.store_or_update');
                    Route::post('/flat_rate/store', [SettingController::class, 'flatRateStoreOrUpdate'])->name('admin.setting.flat_rate.store_or_update');
                    Route::post('/paypal/store', [SettingController::class, 'paypalStoreOrUpdate'])->name('admin.setting.paypal.store_or_update');
                    Route::post('/strip/store', [SettingController::class, 'stripStoreOrUpdate'])->name('admin.setting.strip.store_or_update');
                    Route::post('/paytm/store', [SettingController::class, 'paytmStoreOrUpdate'])->name('admin.setting.paytm.store_or_update');
                    Route::post('/sslcommerz/store', [SettingController::class, 'sslcommerzStoreOrUpdate'])->name('admin.setting.sslcommerz.store_or_update');
                    Route::post('/cash_on_delivery/store', [SettingController::class, 'cashonDeliveryStoreOrUpdate'])->name('admin.setting.cash_on_delivery.store_or_update');
                    Route::post('/bank_transfer/store', [SettingController::class, 'bankTransferStoreOrUpdate'])->name('admin.setting.bank_transfer.store_or_update');
                    Route::post('/check_money_order/store', [SettingController::class, 'cehckMoneyOrderStoreOrUpdate'])->name('admin.setting.check_money_order.store_or_update');
                    Route::post('/razorpay/store', [SettingController::class, 'razorpayStoreOrUpdate'])->name('admin.setting.razorpay.store_or_update');
                    Route::post('/paystack/store', [SettingController::class, 'paystackStoreOrUpdate'])->name('admin.setting.paystack.store_or_update');
                    Route::post('/about_us', [SettingController::class, 'aboutUsStoreOrUpdate'])->name('admin.setting.about_us.store_or_update');
                    Route::get('/empty_database', [SettingController::class, 'emptyDatabase'])->name('empty_database');
                    Route::post('/system-backup', [SettingController::class, 'systemBackup'])->name('system.backup')->middleware(['demo_check']);

                });

                Route::group(['prefix' => 'language'], function () {
                    Route::get('/', [LanguageController::class, 'index'])->name('admin.setting.language');
                    Route::post('/store', [LanguageController::class, 'store'])->name('admin.setting.language.store');
                    Route::post('/update', [LanguageController::class, 'update'])->name('admin.setting.language.update');
                    Route::get('/delete/{id}', [LanguageController::class, 'delete'])->name('admin.setting.language.delete');
                    Route::get('/defaultChange/{id}', [LanguageController::class, 'defaultChange'])->name('admin.setting.language.defaultChange');
                });

                Route::group(['prefix' => 'shipping'], function () {
                    Route::group(['prefix' => 'location'], function () {
                        Route::get('/', [ShippingLocationController::class, 'index'])->name('admin.shipping.location.index');
                    });
                });

            });

            Route::get('languages', [LocaleFileController::class, 'update'])->name('languages.translations.update');

            //FAQ
            Route::prefix('faq')->group(function () {
                Route::prefix('type')->group(function () {
                    Route::get('/index', [FaqTypeController::class, 'index'])->name('admin.faq_type.index');
                    Route::post('/store', [FaqTypeController::class, 'store'])->name('admin.faq_type.store');
                    Route::get('/edit', [FaqTypeController::class, 'edit'])->name('admin.faq_type.edit');
                    Route::post('/update', [FaqTypeController::class, 'update'])->name('admin.faq_type.update');
                    Route::get('/active', [FaqTypeController::class, 'active'])->name('admin.faq_type.active');
                    Route::get('/inactive', [FaqTypeController::class, 'inactive'])->name('admin.faq_type.inactive');
                    Route::get('/delete', [FaqTypeController::class, 'delete'])->name('admin.faq_type.delete');
                    Route::get('/bulk_action', [FaqTypeController::class, 'bulkAction'])->name('admin.faq_type.bulk_action');
                });

                Route::get('/index', [FAQController::class, 'index'])->name('admin.faq.index');
                Route::post('/store', [FAQController::class, 'store'])->name('admin.faq.store');
                Route::get('/edit', [FAQController::class, 'edit'])->name('admin.faq.edit');
                Route::post('/update', [FAQController::class, 'update'])->name('admin.faq.update');
                Route::get('/active', [FAQController::class, 'active'])->name('admin.faq.active');
                Route::get('/inactive', [FAQController::class, 'inactive'])->name('admin.faq.inactive');
                Route::get('/delete', [FAQController::class, 'delete'])->name('admin.faq.delete');
                Route::get('/bulk_action', [FAQController::class, 'bulkAction'])->name('admin.faq.bulk_action');
            });

            Route::group(['prefix' => 'developer-section'], function () {
                Route::get('index', [DeveloperSectionController::class, 'index'])->name('admin.developer.section.index');
                Route::post('auto-update-submit', [DeveloperSectionController::class, 'autoUpdateSubmit'])->name('admin.developer.section.auto-update-submit');
            });

        });
    });
});
