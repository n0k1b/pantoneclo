<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Mail\OrderMail;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CurrencyRate;
use App\Models\FaqType;
use App\Models\FlashSale;
use App\Models\KeywordHit;
use App\Models\Language;
use App\Models\Newsletter AS DBNewslatter;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Review;
use App\Models\Setting;
use App\Models\SettingAboutUs;
use App\Models\SettingStore;
use App\Models\Slider;
use App\Services\BrandService;
use App\Services\SliderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Newsletter;
use App\Traits\CurrencyConrvertion;
use App\Traits\ENVFilePutContent;
use App\Traits\AutoDataUpdateTrait;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Frontend\FrontBaseController;
use App\Models\SettingTranslation;
use App\Models\StorefrontImage;
use App\Traits\TranslationTrait;

class HomeController extends FrontBaseController
{
    use CurrencyConrvertion,ENVFilePutContent, AutoDataUpdateTrait, TranslationTrait;

    private $sliderService;
    private $brandService;
    public function __construct(SliderService $sliderService, BrandService $brandService)
    {
        $this->sliderService = $sliderService;
        $this->brandService  = $brandService;
        // parent::__construct();
    }


    public function index()
    {
        if (!Session::has('currency_code')){
            Session::put('currency_code', env('DEFAULT_CURRENCY_CODE'));
            $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_SYMBOL',env('DEFAULT_CURRENCY_SYMBOL'));
            $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_RATE', env('DEFAULT_CURRENCY_RATE'));
        }

        $categories = Cache::remember('categories', 300, function () {
            return Category::with(['catTranslation','parentCategory.catTranslation','categoryTranslationDefaultEnglish','child.catTranslation'])
                    ->where('is_active',1)
                    ->orderBy('is_active','DESC')
                    ->orderBy('id','ASC')
                    ->get();
        });


        //We change the Logic of Flash Sale Products Later
        if(!Session::get('currentLocal')){
            Session::put('currentLocal', 'en');
            $locale = 'en';
        }else {
            $locale = Session::get('currentLocal');
        }

        //Storefront Theme Color
        $storefront_theme_color = "#0071df";

        //Store Front Slider Format
        $store_front_slider_format = 'full_width';

        //Product_Tab_One
        $product_tabs_one_titles = [];
        $product_tab_one_section_1 = [];
        $product_tab_one_section_2 = [];
        $product_tab_one_section_3 = [];
        $product_tab_one_section_4 = [];

        //Flash Sale And Vertical
        $storefront_flash_sale_title = null;
        $active_campaign_flash_id = null;
        $flash_sales = [];
        $storefront_vertical_product_1_title = null;
        $storefront_vertical_product_2_title = null;
        $storefront_vertical_product_3_title = null;
        $vertical_product_1 = [];
        $vertical_product_2 = [];
        $vertical_product_3 = [];


        //Settings
        $settings = Cache::remember('settings', 300, function () {
            return Setting::with(['storeFrontImage','settingTranslation','settingTranslationDefaultEnglish'])->get();;
        });

        //CategoryProducts
        $category_products = Cache::remember('category_products', 300, function () {
            return CategoryProduct::with('product','productTranslation','productTranslationDefaultEnglish','productBaseImage','additionalImage','category','categoryTranslation','categoryTranslationDefaultEnglish',
                    'productAttributeValues.attributeTranslation','productAttributeValues.attributeTranslationEnglish',
                    'productAttributeValues.attrValueTranslation','productAttributeValues.attrValueTranslationEnglish')
                    ->get();
        });

        //Slider
        $sliders = Cache::remember('sliders', 300, function () {
            return $this->sliderService->getAllSlider();
        });


        //Slider Banner
        $slider_banners = $this->getSliderBanner($settings);

        foreach ($settings as $key => $setting)
        {
            if ($setting->key=='store_front_slider_format' && $setting->plain_value!=NULL) {
                $store_front_slider_format = Cache::remember('store_front_slider_format', 300, function () use($setting) {
                    return $setting->plain_value;
                });
            }


            //----- Category-Product Start -----
            elseif ($setting->key=='storefront_product_tabs_1_section_tab_1_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $product_tab_one_section_1[] = $category_products[$key2];
                        }
                    }
                }
                $product_tabs_one_titles[] = $settings[($key-2)]->key;
            }

            elseif ($setting->key=='storefront_product_tabs_1_section_tab_2_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $product_tab_one_section_2[] =$category_products[$key2];
                        }
                    }
                }
                $product_tabs_one_titles[] = $settings[($key-2)]->key;
            }

            elseif ($setting->key=='storefront_product_tabs_1_section_tab_3_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $product_tab_one_section_3[] =$category_products[$key2];
                        }
                    }
                }
                $product_tabs_one_titles[] = $settings[($key-2)]->key;
            }

            elseif ($setting->key=='storefront_product_tabs_1_section_tab_4_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $product_tab_one_section_4[] =$category_products[$key2];
                        }
                    }
                }
                $product_tabs_one_titles[] = $settings[($key-2)]->key;
            }
            //Flash sale and vertical product
            elseif ($setting->key=='storefront_flash_sale_title') {
                $storefront_flash_sale_title = $setting->settingTranslation->value ?? $setting->settingTranslationDefaultEnglish->value ?? null;
            }
            elseif ($setting->key=='storefront_flash_sale_active_campaign_flash_id') {
                $active_campaign_flash_id = $setting->plain_value;
            }

            elseif ($setting->key=='storefront_vertical_product_1_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $vertical_product_1[] =$category_products[$key2];
                        }
                    }
                }
                $storefront_vertical_product_1_title = !$settings[($key-2)]->settingTranslation ? null :  ($settings[($key-2)]->settingTranslation->value ?? $settings[($key-2)]->settingTranslationDefaultEnglish->value);
            }
            elseif ($setting->key=='storefront_vertical_product_2_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $vertical_product_2[] =$category_products[$key2];
                        }
                    }
                }
                // $storefront_vertical_product_2_title = $settings[($key-2)]->settingTranslation->value ?? $settings[($key-2)]->settingTranslationDefaultEnglish->value;
                $storefront_vertical_product_2_title = !$settings[($key-2)]->settingTranslation ? null :  ($settings[($key-2)]->settingTranslation->value ?? $settings[($key-2)]->settingTranslationDefaultEnglish->value);

            }
            elseif ($setting->key=='storefront_vertical_product_3_category_id' && $setting->plain_value!=NULL) {
                if ($settings[$key-1]->plain_value=='category_products') {
                    foreach ($category_products as $key2 => $value) {
                        if ($value->category_id==$setting->plain_value) {
                            $vertical_product_3[] =$category_products[$key2];
                        }
                    }
                }
                // $storefront_vertical_product_3_title = $settings[($key-2)]->settingTranslation->value ?? $settings[($key-2)]->settingTranslationDefaultEnglish->value;
                $storefront_vertical_product_3_title = !$settings[($key-2)]->settingTranslation ? null :  ($settings[($key-2)]->settingTranslation->value ?? $settings[($key-2)]->settingTranslationDefaultEnglish->value);
            }
            //Top Brands
            elseif ($setting->key=='storefront_top_brands_section_enabled' && $setting->plain_value!=NULL) {
                $storefront_top_brands_section_enabled = $setting->plain_value ?? null;
            }
            elseif ($setting->key=='storefront_top_brands' && $setting->plain_value!=NULL) {
                $storefront_top_brands = $setting->plain_value;
            }
        }


        //Change this later.
        if ($active_campaign_flash_id) {
            $flash_sales = Cache::remember('flash_sales', 300, function () use ($active_campaign_flash_id) {
                return FlashSale::with(['flashSaleTranslation','flashSaleProducts.product.productTranslation','flashSaleProducts.product.baseImage',
                    'flashSaleProducts.product.additionalImage','flashSaleProducts.product.categoryProduct.categoryTranslation',
                    'flashSaleProducts.product.productAttributeValues'])->where('id',$active_campaign_flash_id)->where('is_active',1)->first();
            });
        }

        $brand_ids = json_decode($storefront_top_brands);
        $brands =  $this->brandService->getBrandsWhereInIds($brand_ids);


        $order_details = Cache::remember('order_details', 300, function () {
            return  OrderDetail::with('product.categoryProduct.category.catTranslation','product.productTranslation','product.baseImage','product.additionalImage','product.productAttributeValues.attributeTranslation','product.productAttributeValues.attrValueTranslation')
                    ->select('product_id')
                    ->groupBy('product_id')
                    ->selectRaw('SUM(qty) AS qty_of_sold')
                    ->orderBy('qty_of_sold','DESC')
                    ->skip(0)
                    ->take(10)
                    ->get();
        });



        //We will convert it in ExpiryReminder later
        $this->autoDataUpdate();

        return view('frontend.pages.home',compact('locale','settings','sliders','slider_banners',
                                                'brands','storefront_theme_color','store_front_slider_format','product_tab_one_section_1','product_tab_one_section_2',
                                                'product_tab_one_section_3','product_tab_one_section_4','product_tabs_one_titles',
                                                'storefront_flash_sale_title','flash_sales','storefront_vertical_product_1_title',
                                                'storefront_vertical_product_2_title','storefront_vertical_product_3_title','categories',
                                                'vertical_product_1','vertical_product_2','vertical_product_3','order_details','storefront_top_brands_section_enabled'));
    }


    public function product_details($product_slug, $category_id)
    {
        $product = Product::with(['productTranslation','productTranslationEnglish','categories','productCategoryTranslation','tags','brand','brandTranslation','brandTranslationEnglish',
                'baseImage'=> function ($query){
                    $query->where('type','base')
                        ->first();
                },
                'additionalImage'=> function ($query){
                    $query->where('type','additional')
                        ->get();
                },'productAttributeValues.attributeTranslation','productAttributeValues.attributeTranslationEnglish',
                'productAttributeValues.attrValueTranslation','productAttributeValues.attrValueTranslationEnglish',
                ])
                ->where('slug',$product_slug)
                ->first();


        $attribute = [];
        foreach ($product->productAttributeValues as $value) {
            $attribute[$value->attribute_id]= $value->attributeTranslation->attribute_name ?? $value->attributeTranslationEnglish->attribute_name ?? null;
        }

        $category = Category::with('catTranslation','categoryTranslationDefaultEnglish')->find($category_id);

        $cart = Cart::content()->where('id',$product->id)->where('options.category_id',$category_id ?? null)->first();
        if ($cart) {
            $product_cart_qty = $cart->qty;
        }else {
            $product_cart_qty = null;
        }

        //Review Part
        if (Auth::check()) {
            $user_and_product_exists = DB::table('orders')
                        ->join('order_details','order_details.order_id','orders.id')
                        ->where('orders.user_id',Auth::user()->id)
                        ->where('order_details.product_id',$product->id)
                        ->exists();
        }else {
            $user_and_product_exists = null;
        }

        $reviews = DB::table('reviews')
                    ->join('users','users.id','reviews.user_id')
                    ->where('product_id',$product->id)
                    ->where('status','approved')
                    ->select('users.id AS userId','users.first_name','users.last_name','users.image','reviews.comment','reviews.rating','reviews.status','reviews.created_at')
                    ->where('reviews.deleted_at',null)
                    ->get();

        if (empty($reviews)) {
            $reviews =[];
        }


        //Related Products
        $category_products =  CategoryProduct::with('product','productTranslation','productTranslationDefaultEnglish','productBaseImage','additionalImage','category','categoryTranslation','categoryTranslationDefaultEnglish',
                        'productAttributeValues.attributeTranslation','productAttributeValues.attributeTranslationEnglish',
                        'productAttributeValues.attrValueTranslation','productAttributeValues.attrValueTranslationEnglish')
                    ->where('category_id', $category_id)
                    ->get();


        return view('frontend.pages.product_details',compact('product','category','product_cart_qty','attribute','user_and_product_exists','reviews','category_products'));
    }

    public function dataAjaxSearch(Request $request)
    {
        if ($request->ajax()) {

            $base_url = url('/');

            $locale = Session::get('currentLocal');
            $products = ProductTranslation::with(['product:id,slug,price','product.baseImage'=> function($query){
                                return $query->where('type','base');
                            },
                            'product.categoryProduct'])
                            ->where('product_name','LIKE', '%'.$request->search_txt.'%')
                            ->where('local',$locale)
                            ->select('product_id','product_name','local')
                            ->get();
            $html = '';
            foreach ($products as $key => $item) {
                if ($item->product->baseImage!=null) {
                    $image_url = url("public".$item->product->baseImage->image_small);
                    $html .= '<li><a class="d-flex" href="'.$base_url.'/product/'.$item->product->slug.'/'.$item->product->categoryProduct[0]->category_id.'"><img src="'.$image_url.'" style="height:50px;width:50px"/><div><h6>'.$item->product_name.'</h6><span class="price">'.$item->product->price.'</span></div></a></li>';
                }else {
                    $html .= '<li><a class="d-flex" href="'.$base_url.'/product/'.$item->product->slug.'/'.$item->product->categoryProduct[0]->category_id.'">'.$item->product_name.'<br>'.$item->product->price.'</a></li>';
                }
            }
            return response()->json($html);
        }
    }

    public function newslatterStore(Request $request)
    {
        if ($request->ajax()) {

            if ($request->disable_newslatter==1) {
                Session::put('disable_newslatter',1);
            }

            $newslatter  = new DBNewslatter();
            $newslatter->email = $request->email;
            $newslatter->save();

            // Newsletter::delete($request->email);

            if ( ! Newsletter::isSubscribed($request->email) )
            {
                Newsletter::subscribePending($request->email);
                return response()->json(['type'=>'success','message'=>'Successfully Subscribed']);
            }
            return response()->json(['type'=>'error']);
        }
    }

    protected function getSliderBanner($settings)
    {
        $slider_banners = [];
        $empty_image = null;//'images/empty.jpg';

        for ($i=0; $i < 3; $i++) {
            foreach ($settings as $item){
                if ($item->key=='storefront_slider_banner_'.($i+1).'_image') {
                    if ($item->storeFrontImage) {
                        $slider_banners[$i]['image'] = $item->storeFrontImage->image;
                    }else {
                        $slider_banners[$i]['image'] = $empty_image;
                    }
                }
                elseif ($item->key=='storefront_slider_banner_'.($i+1).'_title') {
                    $slider_banners[$i]['title'] = $item->settingTranslations[0]->value ?? null;
                }
                elseif ($item->key=='storefront_slider_banner_'.($i+1).'_call_to_action_url') {
                    $slider_banners[$i]['action_url'] = $item->plain_value;
                }
                elseif ($item->key=='storefront_slider_banner_'.($i+1).'_open_in_new_window') {
                    $slider_banners[$i]['new_window'] = $item->plain_value;
                }
            }
        }

        return $slider_banners;
    }

    public function reviewStore(Request $request)
    {
        $review = new Review();
        $review->user_id = Auth::user()->id;
        $review->product_id = $request->product_id;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        $review->status = 'pending';
        $review->save();


        $product_review = Review::where('product_id',$request->product_id)
                        ->where('status','approved')
                        ->select(DB::raw('count(*) as product_count, sum(rating) as product_rating'))
                        ->first();

        $product_avg_rating = 0;
        if ($product_review->product_count>0) {
            $product_avg_rating = $product_review->product_rating / $product_review->product_count;
            $product_avg_rating = number_format((float)$product_avg_rating, 2, '.', '');
        }

        $product = Product::find($request->product_id);
        $product->avg_rating = $product_avg_rating;
        $product->update();

        return redirect()->back();
    }

    public function orderTracking()
    {
        return view('frontend.pages.order_tracking.index');
    }

    public function orderTrackingFind(Request $request)
    {
        $order = Order::where(['reference_no'=>$request->reference_no,
                        'billing_email'=>$request->email])
                        ->first();

        return view('frontend.pages.order_tracking.order_page',compact('order'));
    }

    public function orderTrackingFindDetails($reference_no)
    {
        $locale = Session::get('currentLocal');
        $order = Order::where('reference_no',$reference_no)->first();
        $order_details = DB::table('order_details')
                    ->join('orders','orders.id','order_details.order_id')
                    ->join('products','products.id','order_details.product_id')
                    ->join('product_translations',function ($join) use($locale) {
                        $join->on('product_translations.product_id', '=', 'products.id')
                        ->where('product_translations.local', '=', $locale);
                    })
                    ->where('user_id',Auth::user()->id)
                    ->select('product_translations.product_name','order_details.image','order_details.price','order_details.qty','order_details.options','order_details.subtotal')
                    ->where('order_details.order_id',$order->id)
                    ->where('order_details.deleted_at',null)
                    ->get();


        return view('frontend.pages.order_tracking.order_details',compact('order','order_details'));
    }

    public function defaultLanguageChange($id)
    {
        $language = Language::find($id);

        Session::put('currentLocal', $language->local);

        App::setLocale($language->local);
        return redirect()->back();
    }

    public function searchProduct(Request $request)
    {
        if (!$request->search) {
            return redirect()->back();
        }

        //KyWord Hit
        $dataCheck = KeywordHit::where('keyword',$request->search);
        if ($dataCheck->exists()) {
            $get_data = $dataCheck->first();
            $increment = $get_data->hit+1;
            $get_data->update(['hit'=>$increment]);
        }else {
            $keyword_hit = new KeywordHit();
            $keyword_hit->keyword = $request->search;
            $keyword_hit->hit = 1;
            $keyword_hit->save();
        }

        $locale = Session::get('currentLocal');
        $products = ProductTranslation::with(['product','product.baseImage'=> function($query){
                    return $query->where('type','base');
                },
                'product.categoryProduct','product.additionalImage'])
                ->where('product_name','LIKE', '%'.$request->search.'%')
                ->where('local',$locale)
                ->select('product_id','product_name','local')
                ->get();

        if($products->count()>0){
            return view('frontend.pages.search_products',compact('products'));
        }else {
            return view('frontend.includes.product_not_found');
        }
    }

    public function currencyChange($currency_code)
    {
        // return Session::get('currency_symbol');
        // return Session::get('currency_rate');

        // Session::put('currency_code', $currency_code);
        // $currency_symbol = $this->CurrencySymbol();
        // $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_SYMBOL',$currency_symbol);
        // $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_RATE',$this->ChangeCurrencyRate());
        // return redirect()->back();

        session()->put('currency_code', $currency_code);
        session()->put('currency_symbol',$this->CurrencySymbol($currency_code));
        session()->put('currency_rate',$this->ChangeCurrencyRate($currency_code));

        // Session::put('currency_code', $currency_code);
        // $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_SYMBOL', $this->CurrencySymbol($currency_code));
        // $this->dataWriteInENVFile('USER_CHANGE_CURRENCY_RATE', $this->ChangeCurrencyRate($currency_code));
        return redirect()->back();
    }

    public function setCookie(Request $request)
    {
        //Top Banner
        if ($request->cookie_type=='top_banner') {
            Cookie::queue('top_banner', 'top_banner', 60 * 8760); //key, value, minute
            return response()->json('disable');
        }

        //Newlatter
        if ($request->newslatter=='disable') {
            Cookie::queue('newslatter', $request->newslatter, 60 * 8760); //key, value, minute
            return response()->json('disable');
        }elseif($request->newslatter=='enable') {
            Cookie::queue(Cookie::forget('newslatter'));
        }
    }

    public function faq()
    {
        $faq_types = FaqType::with(['faqTypeTranslation','faqs'=> function($q){
                        $q->where('is_active',1);
                    } ,'faqs.faqTranslation','faqs.faqTranslationEnglish'])
                    ->where('is_active',1)
                    ->get();
        return view('frontend.pages.faq',compact('faq_types'));
    }

    public function searchFAQ(Request $request)
    {
        $locale = Session::get('currentLocal');

        $faqSearchBaseOnFaqType =   DB::table('faq_translations')
                                    ->select('faq_translations.title','faq_translations.description','faq_translations.locale','faq_type_translations.type_name as type_name')
                                    ->join('faqs','faqs.id','faq_translations.faq_id')
                                    ->join('faq_type_translations','faq_type_translations.faq_type_id','faqs.faq_type_id')
                                    ->where('faq_type_translations.locale',$locale)
                                    ->where('faq_translations.locale',$locale)->where('faq_type_translations.type_name','LIKE', '%'.$request->search_faq.'%')
                                    ->get()
                                    ->groupBy('type_name');

        $faqSearchBaseOnFaqTitle =  DB::table('faq_translations')
                                    ->select('faq_translations.title','faq_translations.description','faq_translations.locale','faq_type_translations.type_name as type_name')
                                    ->join('faqs','faqs.id','faq_translations.faq_id')
                                    ->join('faq_type_translations','faq_type_translations.faq_type_id','faqs.faq_type_id')
                                    ->where('faq_type_translations.locale',$locale)
                                    ->where('faq_translations.locale',$locale)->where('faq_translations.title','LIKE', '%'.$request->search_faq.'%')
                                    ->get()
                                    ->groupBy('type_name');

        $faqSearchBaseOnFaqDescription = DB::table('faq_translations')
                                        ->select('faq_translations.title','faq_translations.description','faq_translations.locale','faq_type_translations.type_name as type_name')
                                        ->join('faqs','faqs.id','faq_translations.faq_id')
                                        ->join('faq_type_translations','faq_type_translations.faq_type_id','faqs.faq_type_id')
                                        ->where('faq_type_translations.locale',$locale)
                                        ->where('faq_translations.locale',$locale)->where('faq_translations.description','LIKE', '%'.$request->search_faq.'%')
                                        ->get()
                                        ->groupBy('type_name');


        if(count($faqSearchBaseOnFaqType)>0){
            $search_product = $faqSearchBaseOnFaqType;
        }
        else if(count($faqSearchBaseOnFaqTitle)>0){
            $search_product = $faqSearchBaseOnFaqTitle;
        }
        else{
            $search_product = $faqSearchBaseOnFaqDescription;
        }
        return view('frontend.pages.faq',compact('search_product'));
    }

    public function contact()
    {
        $schedules = [];
        $data = null;
        $setting_store = SettingStore::latest()->first();
        if (!empty($setting_store)) {
            // return $setting_store->store_email;
            $data = json_decode($setting_store->schedule);
        }
        foreach ($data as $key => $value) {
            $schedules[] = $value;
        }

        return view('frontend.pages.contact',compact('schedules'));
    }

    public function contactMessage()
    {
        $setting_store = SettingStore::latest()->first();
        $store_email = null;
        if (!empty($setting_store)) {
            $store_email = $setting_store->store_email;
        }

        $data = [];
        $data['name']    = request('name');
        $data['email']   = request('email');
        $data['subject'] = request('subject');
        $data['message'] = request('message');
        Mail::to($store_email)->send(new ContactMail($data));

        session()->flash('message',"Message sent successfully");
        session()->flash('type','success');
        return redirect()->back();
    }

    public function aboutUs()
    {
        $setting_about_us = SettingAboutUs::with('aboutUsTranslation','aboutUsTranslationEnglish')->latest()->first();
        return view('frontend.pages.about_us',compact('setting_about_us'));
    }



    // public function changeForDemoOrClient($text)
    // {
    //     // $this->dataWriteInENVFile('APP_DEBUG',(bool)false);
    //     return 1;


    //     if (isset($text) && $text==='CLIENT') {
    //         $installFileOldName = base_path('/'.'install0');
    //         $installFileNewName = base_path('/'.'install');

    //         /* Rename File Name */
    //         if(!rename($installFileOldName, $installFileNewName)) {
    //             return "File can't be renamed!";
    //         }
    //         $this->dataWriteInENVFile('PRODUCT_MODE',$text);
    //         $this->dataWriteInENVFile('USER_VERIFIED',1);
    //     }
    //     else if (isset($text) && $text==='DEMO'){
    //         $this->dataWriteInENVFile('PRODUCT_MODE',$text);
    //         $this->dataWriteInENVFile('USER_VERIFIED',null);
    //     }
    //     Artisan::call('optimize:clear');
    //     return redirect()->back();

    //     // $this->dataWriteInENVFile('PRODUCT_MODE','DEMO');
    //     // $this->dataWriteInENVFile('USER_VERIFIED',1);
    //     // $this->dataWriteInENVFile('APP_DEBUG',1);

    //     // Artisan::call('optimize:clear');
    //     // return redirect()->back();
    // }
}
