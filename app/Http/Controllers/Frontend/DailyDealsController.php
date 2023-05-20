<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Traits\ProductPromoBadgeTextTrait;
use Illuminate\Support\Facades\Cache;

class DailyDealsController extends Controller
{
    use ProductPromoBadgeTextTrait;

    public function index()
    {
        $locale = Session::get('currentLocal');

        $products = Cache::remember('products_deals', 300, function () use ($locale){
            return DB::table('products')
                    ->join('product_translations', function ($join) use ($locale) {
                        $join->on('product_translations.product_id', '=', 'products.id')
                        ->where('product_translations.local', '=', $locale);
                    })
                    ->leftJoin('brand_translations', function ($join) use ($locale) {
                        $join->on('brand_translations.brand_id', '=', 'products.brand_id')
                        ->where('brand_translations.local', '=', $locale);
                    })
                    ->leftJoin('product_images', function ($join) {
                        $join->on('product_images.product_id', '=', 'products.id')
                        ->where('product_images.type', '=', 'base');
                    })
                    ->select('products.*','product_images.image','product_images.image_medium','product_images.type','product_translations.product_name','product_translations.short_description','brand_translations.brand_name')
                    ->where('is_active',1)
                    ->where('special_price','>','price')
                    ->orderBy('products.id','ASC')
                    ->where('products.deleted_at',null)
                    ->get();
         });

         $product_images = Cache::remember('product_images_deals', 300, function (){
             return ProductImage::select('product_id','image','type')->get();
         });

         $category_product = Cache::remember('category_product_deals', 300, function (){
             return CategoryProduct::get();
         });

        $category_ids = [];
        foreach ($products as $key => $item) {
            foreach ($category_product as $key => $value) {
                if ($item->id==$value->product_id) {
                    $category_ids[$item->id] = $category_product[$key];
                    break;
                }
            }
        }

        $product_attr_val = Cache::remember('product_attr_val_deals', 300, function (){
            return  Product::with('productAttributeValues','brandTranslation')
                    ->orderBy('id','DESC')
                    ->get();
        });

        $categories = Cache::remember('categories_deals', 300, function (){
            return Category::with(['catTranslation','parentCategory.catTranslation','categoryTranslationDefaultEnglish','child.catTranslation'])
                    ->where('is_active',1)
                    ->orderBy('is_active','DESC')
                    ->orderBy('id','ASC')
                    ->get();
        });


        $attribute_with_values = Cache::remember('attribute_with_values_deals', 300, function (){
            return ProductAttributeValue::with('attributeTranslation','attributeValueTranslations')->get()->keyBy('attribute_id');
        });

        // $attribute_values =  DB::table('attribute_category')
        //         ->join('attribute_translations', function ($join) use ($locale) {
        //             $join->on('attribute_translations.attribute_id', '=', 'attribute_category.attribute_id')
        //             ->where('attribute_translations.locale', '=', $locale);
        //         })
        //         ->join('attribute_value_translations', function ($join) use ($locale) {
        //             $join->on('attribute_value_translations.attribute_id', '=', 'attribute_category.attribute_id')
        //             ->where('attribute_value_translations.local', '=', $locale);
        //         })
        //         ->select('attribute_category.*','attribute_translations.attribute_name','attribute_value_translations.attribute_value_id','attribute_value_translations.value_name AS attribute_value_name')
        //         ->get();

        // return view('frontend.pages.shop_products',compact('products','product_images','category_ids','product_attr_val','categories','attribute_with_values' ,'attribute_values'));
        return view('frontend.pages.daily_deals_products',compact('products','product_images','category_ids','product_attr_val','categories','attribute_with_values'));
    }

    public function limitShopProductShow(Request $request)
    {

        $locale = Session::get('currentLocal');

        $products = DB::table('products')
                ->join('product_translations', function ($join) use ($locale) {
                    $join->on('product_translations.product_id', '=', 'products.id')
                    ->where('product_translations.local', '=', $locale);
                })
                ->leftJoin('brand_translations', function ($join) use ($locale) {
                    $join->on('brand_translations.brand_id', '=', 'products.brand_id')
                    ->where('brand_translations.local', '=', $locale);
                })
                ->join('product_images', function ($join) {
                    $join->on('product_images.product_id', '=', 'products.id')
                    ->where('product_images.type', '=', 'base');
                })
                ->select('products.*','product_images.image','product_images.image_medium','product_images.type','product_translations.product_name','product_translations.short_description','brand_translations.brand_name')
                ->where('is_active',1)
                ->orderBy('products.id','ASC')
                ->limit($request->limit_data)
                ->where('products.deleted_at',null)
                ->get();

        $category_product =  CategoryProduct::get();
        $category_ids = [];
        foreach ($products as $key => $item) {
            foreach ($category_product as $key => $value) {
                if ($item->id==$value->product_id) {
                    $category_ids[$item->id] = $category_product[$key];
                    break;
                }
            }
        }


        $html = $html = $this->productsShow($category_ids, $products);
        return response()->json($html);
    }

    public function shopProductsShowSortby(Request $request)
    {
        if(!Session::get('currentLocal')){
            Session::put('currentLocal', 'en');
            $locale = 'en';
        }else {
            $locale = Session::get('currentLocal');
        }


        if ($request->condition=='latest') {
            $products =  DB::table('products')
                            ->join('product_translations', function ($join) use ($locale) {
                                $join->on('product_translations.product_id', '=', 'products.id')
                                ->where('product_translations.local', '=', $locale);
                            })
                            ->leftJoin('brand_translations', function ($join) use ($locale) {
                                $join->on('brand_translations.brand_id', '=', 'products.brand_id')
                                ->where('brand_translations.local', '=', $locale);
                            })
                            ->join('product_images', function ($join) {
                                $join->on('product_images.product_id', '=', 'products.id')
                                ->where('product_images.type', '=', 'base');
                            })
                            ->select('products.*','product_images.image_medium','product_images.type','product_translations.product_name','product_translations.short_description','brand_translations.brand_name')
                            ->where('is_active',1)
                            ->orderBy('products.id','DESC')
                            ->where('products.deleted_at',null)
                            ->get();
        }elseif ($request->condition=='low_to_high') {
            $products =  DB::table('products')
                            ->join('product_translations', function ($join) use ($locale) {
                                $join->on('product_translations.product_id', '=', 'products.id')
                                ->where('product_translations.local', '=', $locale);
                            })
                            ->leftJoin('brand_translations', function ($join) use ($locale) {
                                $join->on('brand_translations.brand_id', '=', 'products.brand_id')
                                ->where('brand_translations.local', '=', $locale);
                            })
                            ->join('product_images', function ($join) {
                                $join->on('product_images.product_id', '=', 'products.id')
                                ->where('product_images.type', '=', 'base');
                            })
                            ->select('products.*','product_images.image_medium','product_images.type','product_translations.product_name','product_translations.short_description','brand_translations.brand_name')
                            ->where('is_active',1)
                            ->addSelect(DB::raw('IF(is_special=0, price, special_price ) AS current_price'))
                            ->orderBy('current_price','ASC')
                            ->where('products.deleted_at',null)
                            ->get();
        }elseif ($request->condition=='high_to_low') {
            $products =  DB::table('products')
                            ->join('product_translations', function ($join) use ($locale) {
                                $join->on('product_translations.product_id', '=', 'products.id')
                                ->where('product_translations.local', '=', $locale);
                            })
                            ->leftJoin('brand_translations', function ($join) use ($locale) {
                                $join->on('brand_translations.brand_id', '=', 'products.brand_id')
                                ->where('brand_translations.local', '=', $locale);
                            })
                            ->join('product_images', function ($join) {
                                $join->on('product_images.product_id', '=', 'products.id')
                                ->where('product_images.type', '=', 'base');
                            })
                            ->select('products.*','product_images.image_medium','product_images.type','product_translations.product_name','product_translations.short_description','brand_translations.brand_name')
                            ->where('is_active',1)
                            ->addSelect(DB::raw('IF(is_special=0, price, special_price ) AS current_price'))
                            ->orderBy('current_price','DESC')
                            ->where('products.deleted_at',null)
                            ->get();
        }

        $category_product =  CategoryProduct::get();
        $category_ids = [];
        foreach ($products as $key => $item) {
            foreach ($category_product as $key => $value) {
                if ($item->id==$value->product_id) {
                    $category_ids[$item->id] = $category_product[$key];
                    break;
                }
            }
        }

        $html = $this->productsShow($category_ids, $products);
        return response()->json($html);
    }


    protected function productsShow($category_ids,$products)
    {
        $CHANGE_CURRENCY_SYMBOL = env('USER_CHANGE_CURRENCY_SYMBOL');
        if ($CHANGE_CURRENCY_SYMBOL==NULL) {
            $CHANGE_CURRENCY_SYMBOL = NULL;
        }
        $CURRENCY_SYMBOL = $CHANGE_CURRENCY_SYMBOL!=NULL ? $CHANGE_CURRENCY_SYMBOL : env('DEFAULT_CURRENCY_SYMBOL');

        $html = '';

        foreach ($products as $item) {
            $imageurl = url("public/".$item->image_medium);

            $html .= '<form class="addToCart">
                        <input type="hidden" name="product_id" value="'.$item->id.'">
                        <input type="hidden" name="product_slug" value="'.$item->slug.'">
                        <input type="hidden" name="category_id" value="'.$category_ids[$item->id]->category_id.'">
                        <input type="hidden" name="qty" value="1">
                        <input type="hidden" name="_token" value="'.csrf_token().'">';


            $html .=    '<div class="product-grid-item">
                            <div class="single-product-wrapper">
                                <div class="single-product-item">';
                                if ($item->type=='base'){
                        $html .= '<img src="'.$imageurl.'" alt="...">';
                                }else{
                        $html .= '<img src="'.url('public/images/empty.jpg').'" alt="...">';
                                }

                        // Product Promo Badge Text
                        $html .= $this->productPromoBadgeText($item->manage_stock, $item->qty, $item->in_stock, date('Y-m-d'), $item->new_to);

                        $html .= '<div class="product-overlay">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#id_'.$item->id.'"> <span class="ti-zoom-in" data-bs-toggle="tooltip" data-bs-placement="top" title="quick view"></span></a>';
                                    if(Auth::check()){
                                        $html .=  '<a><span class="ti-heart add_to_wishlist" data-product_id="'.$item->id.'" data-product_slug="'.$item->slug.'" data-category_id="'.$category_ids[$item->id]->category_id.'" data-qty="1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to wishlist"></span></a>';
                                    }else{
                                        $html .=  '<a><span class="ti-heart forbidden_wishlist" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to wishlist"></span></a>';
                                    }

                            $html .='</div>
                            </div>
                            <div class="product-details">
                                <a class="product-name" href="">
                                    '.$item->product_name.'
                                </a>
                                <div class="product-short-description">
                                        '.$item->short_description.'
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="rating-summary">
                                            <div class="rating-result" title="60%">
                                                <ul class="product-rating">';
                                                    for ($i=1; $i <=5 ; $i++){
                                                        if ($i<= round($item->avg_rating)){
                                                            $html .= '<li><i class="las la-star"></i></li>';
                                                        }else {
                                                            $html .= '<li><i class="lar la-star"></i></li>';
                                                        }
                                                    }
                                                $html .= '</ul>
                                            </div>
                                        </div>
                                        <div class="product-price">';
                                            if ($item->special_price!=NULL && $item->special_price>0 && $item->special_price < $item->price){
                                                $html .= '<span class="promo-price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->special_price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                                $html .= '<span class="old-price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                            }else{
                                                $html .= '<span class="price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                            }
                                    $html .='</div>
                                    </div>';
                                    if (($item->manage_stock==1 && $item->qty==0) || ($item->in_stock==0)){
                                        $html .=  '<button class="button style2 sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Stock Out"><i class="las la-cart-plus"></i></button>';
                                    }else {
                                        $html .=  '<button class="button style2 sm" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"><i class="las la-cart-plus"></i></button>';
                                    }
                                $html .= '</div>
                            </div>
                            <div class="product-options">
                                <div class="product-price mt-2">';
                                if ($item->special_price!=NULL && $item->special_price>0 && $item->special_price < $item->price){
                                        $html .= '<span class="promo-price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->special_price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                        $html .= '<span class="old-price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                }else {
                                    $html .= '<span class="price">'.$CURRENCY_SYMBOL.' '.number_format((float)$item->price * env('USER_CHANGE_CURRENCY_RATE'), env('FORMAT_NUMBER'), '.', '').'</span>';
                                }

                                $html .= '</div>';
                                if (($item->manage_stock==1 && $item->qty==0) || ($item->in_stock==0)){
                                    $html .=  '<span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip" data-bs-placement="top" title="Disabled tooltip"><button class="btn button style2 sm" disabled><i class="las la-cart-plus"></i></button></span>';
                                }else {
                                    $html .=  '<button class="button style2 sm" type="submit" data-bs-toggle="tooltip" data-bs-placement="top"><i class="las la-cart-plus"></i></button>';
                                }
                                $html .='<div class="d-flex justify-content-between">';
                                    if(Auth::check()){
                                        $html .=  '<a><span class="ti-heart add_to_wishlist"  data-product_id="'.$item->id.'" data-product_slug="'.$item->slug.'" data-category_id="'.$category_ids[$item->id]->category_id.'" data-qty="1" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to wishlist"></span></a>';
                                    }else{
                                        $html .=  '<a><span class="ti-heart forbidden_wishlist" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to wishlist"></span></a>';
                                    }
                                $html .= '</div>
                            </div>
                            </div>
                        </div>
                    </form>';
        }
        return $html;
    }
}
