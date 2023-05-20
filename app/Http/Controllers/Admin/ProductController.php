<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\AttributeValue;
use App\Models\AttributeValueTranslation;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductTranslation;
use App\Models\Tag;
use App\Models\Tax;
use App\Services\AttributeSetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Traits\ActiveInactiveTrait;
use App\Traits\SlugTrait;
use Image;
use Str;
use App\Traits\FormatNumberTrait;
use App\Traits\DeleteWithFileTrait;
use Illuminate\Support\Facades\App;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\TagService;
use App\Services\TaxService;
use App\Traits\imageHandleTrait;
use App\Traits\TranslationTrait;

class ProductController extends Controller
{
    use ActiveInactiveTrait, SlugTrait, FormatNumberTrait, DeleteWithFileTrait, imageHandleTrait, TranslationTrait;


    protected $productService;
    protected $brandService;
    protected $categoryService;
    protected $tagService;
    protected $attributeSetService;
    protected $taxService;
    public function __construct(ProductService $productService, BrandService $brandService, CategoryService $categoryService, TagService $tagService, AttributeSetService $attributeSetService ,TaxService $taxService){
        $this->productService  = $productService;
        $this->brandService    = $brandService;
        $this->categoryService = $categoryService;
        $this->tagService      = $tagService;
        $this->attributeSetService = $attributeSetService;
        $this->taxService      = $taxService;
    }


    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    |
    |
    */
    public function index()
    {
        if (!auth()->user()->can('product-view')){
            return abort('403', __('You are not authorized'));
        }
        return view('admin.pages.product.index');
    }

    public function dataTable(){
        return $this->productService->dataTable();
    }

    /*
    |--------------------------------------------------------------------------
    | Product Create
    |--------------------------------------------------------------------------
    |
    |
    */

    public function create()
    {
        $local         = Session::get('currentLocal');
        $brands        = json_decode(json_encode($this->brandService->getAllBrands()), FALSE);
        $categories    = $this->categoryService->getAllCategories();
        $tags          = $this->tagService->getAllTag();
        $attributeSets = $this->attributeSetService->getAllWithAttributesAndValues();
        $taxes         = $this->taxService->getAllTax();
        return view('admin.pages.product.create',compact('local','brands','categories','tags','attributeSets','taxes'));
    }

    /*
    |--------------------------------------------------------------------------
    | Product Store
    |--------------------------------------------------------------------------
    |
    */

    public function store(ProductStoreRequest $request)
    {
        if ($request->new_from > $request->new_to) {
            $this->setErrorMessage(__('The From date should not be greater then the To date.'));
            return redirect()->back();
        }

        $attribute_arr = [];
        if (isset($request->attribute_id[0])) {
            foreach($request->attribute_id as $item){
                if (!in_array($item, $attribute_arr)){
                    array_push($attribute_arr, $item);
                }else{
                    $this->setErrorMessage(__('Cannot add duplicted attribute. Please select one.'));
                    return redirect()->back();
                }
            }
        }

        if($request->weight_base_calculation && $request->weight==null){
            $this->setErrorMessage('Weight can not be empty. Please input the weight');
            return redirect()->back();
        }
        else if($request->weight_base_calculation && ($request->weight > 1 || $request->weight < 1)){
            $this->setErrorMessage('For weight base calculation you have to set weight = 1');
            return redirect()->back();
        }

        $local = Session::get('currentLocal');

        if (auth()->user()->can('product-store'))
        {
            $product                = new Product();
            if ($request->brand_id) {
                $product->brand_id  = $request->brand_id;
            }
            $product->tax_id        = $request->tax_id;
            $product->slug          = $this->slug(htmlspecialchars_decode($request->product_name));
            $product->price         = $request->price;

            $product->weight        = $request->weight_base_calculation ? $request->weight : null;
            $product->weight_base_calculation  = $request->weight_base_calculation ?? 0;

            $product->special_price = number_format((float)$request->special_price, env('FORMAT_NUMBER'), '.', '');

            if ($request->special_price && $request->price > $request->special_price){
                $product->is_special = 1;
            }else {
                $product->is_special = 0;
            }
            $product->special_price_type = $request->special_price_type;
            $product->special_price_start= $request->special_price_start==true ? date("Y-m-d",strtotime($request->special_price_start)): null;
            $product->special_price_end  = $request->special_price_end==true ? date("Y-m-d",strtotime($request->special_price_end)): null;
            $product->selling_price = number_format((float)$request->special_price, env('FORMAT_NUMBER'), '.', '');
            $product->sku           = $request->sku;
            $product->manage_stock  = $request->manage_stock==0 ? 0:1;
            $product->qty           = $request->qty;
            $product->in_stock      = $request->in_stock==0 ? 0:1;
            $product->new_from      = $request->new_from==true ? date("Y-m-d",strtotime($request->new_from)): null;
            $product->new_to        = $request->new_to==true ? date("Y-m-d",strtotime($request->new_to)): null;
            $product->avg_rating    = 0;
            $product->is_active     = $request->is_active==0 ? 0 : 1;
            $product->save();

            //----------------- Product Translation --------------

            $productTranslation = new ProductTranslation();
            $productTranslation->product_id  = $product->id;
            $productTranslation->local        = Session::get('currentLocal');
            $productTranslation->product_name = htmlspecialchars_decode($request->product_name);
            $productTranslation->description  = htmlspecialchars_decode($request->description);;
            $productTranslation->short_description = $request->short_description;
            $productTranslation->meta_title    = $request->meta_title;
            $productTranslation->meta_description  = $request->meta_description;
            $productTranslation->save();

            //----------------- Base Image --------------
            if (!empty($request->base_image)){
                $image_name = $this->imageStoreProduct($request->base_image);

                $productImage = [];
                $productImage['product_id'] = $product->id;
                $productImage['image']        = '/images/products/large/'.$image_name;
                $productImage['image_medium'] = '/images/products/medium/'.$image_name;
                $productImage['image_small']  = '/images/products/small/'.$image_name;
                $productImage['type']       = 'base';
                ProductImage::insert($productImage);
            }

            //----------------- Multiple Image ---------------
            if (!empty($request->additional_images)) {
                $additionalImagesArray = $request->additional_images;
                foreach($additionalImagesArray as $key => $image){
                    $image_name = $this->imageStoreProduct($image);
                    $data = [];
                    $data['product_id'] = $product->id;
                    $data['image']        = '/images/products/large/'.$image_name;
                    $data['image_medium'] = '/images/products/medium/'.$image_name;
                    $data['image_small']  = '/images/products/small/'.$image_name;
                    $data['type']  = 'additional';

                    ProductImage::insert($data);
                }
            }

            //----------------- Category-Product --------------
            if (!empty($request->category_id)) {
                $categoryArrayIds = $request->category_id;
                $product->categories()->sync($categoryArrayIds);
            }


            //-----------------Product-Tag--------------
            if (!empty($request->tag_id)) {
                $tagArrayIds = $request->tag_id;
                $product->tags()->sync($tagArrayIds);
            }


            //-----------------Product-Attribute--------------

            if (!empty($request->attribute_id) && $request->attribute_id[0]) {
                $attributeValueTranslation = AttributeValueTranslation::whereIn('attribute_value_id',$request->attribute_value_id)->get();
                foreach($attributeValueTranslation as $item){
                    ProductAttributeValue::insert([
                            'product_id'=> $product->id,
                            'attribute_id'=> $item->attribute_id,
                            'attribute_value_id'=> $item->attribute_value_id
                    ]);
                }
            }

            session()->flash('type','success');
            session()->flash('message','Data Saved Successfully.');

            return redirect()->back();
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Product Edit
    |--------------------------------------------------------------------------
    |
    */

    public function edit($id)
    {
        $local = Session::get('currentLocal');

        $product = Product::with(['productTranslation','productTranslationEnglish','categories','tags','brand','brandTranslation','brandTranslationEnglish',
                    'baseImage'=> function ($query){
                        $query->where('type','base')
                            ->first();
                    },
                    'additionalImage'=> function ($query){
                        $query->where('type','additional')
                            ->get();
                    }
                    ])
                    ->where('id',$id)
                    ->first();

        $productAttributeValues = ProductAttributeValue::with('attributeValueTranslations')
                                    ->where('product_id',$id)
                                    ->get()
                                    ->groupBy('attribute_id');

        // return $productAttributerValues[1][0]->attributeValueTranslations[0]->value_name;
        // return $productAttributeValues;


        $brands = Brand::with(['brandTranslation','brandTranslationEnglish'])
            ->where('is_active',1)
            ->get();

        $categories =  $this->categoryService->getAllCategories();


        $tags = Tag::with(['tagTranslation'=> function ($query) use ($local){
            $query->where('local',$local)
            ->orWhere('local','en')
            ->orderBy('id','DESC');
        }])->get();

        $attributes = Attribute::with('attributeTranslation','attributeTranslationEnglish','attributeValues')
                        ->where('is_active',1)
                        ->get();

        $attribute_values = AttributeValue::with('attrValueTranslation','attrValueTranslationEnglish')->get();

        $attributeSets = AttributeSet::with('attributeSetTranslation','attributeSetTranslationEnglish','attributes.attributeTranslation',
                        'attributes.attributeTranslationEnglish','attributes.attributeValues.attributeValueTranslation')
                        ->where('is_active',1)
                        ->orderBy('is_active','DESC')
                        ->orderBy('id','DESC')
                        ->get();


        $taxes = Tax::with('taxTranslation','taxTranslationDefaultEnglish')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','ASC')
                ->get();

        $format_number = $this->totalFormatNumber();

        return view('admin.pages.product.edit',compact('local','brands','categories','tags','attributes','product','format_number','taxes','attribute_values','attributeSets','productAttributeValues'));
    }

    public function attributeWiseInventory($product_id)
    {

        return view('admin.pages.product.edit.attribute_wise_inventory');

        $productAttributerValues = ProductAttributeValue::with('attributeValueTranslations')
                            ->where('product_id',$product_id)
                            ->get()
                            ->groupBy('attribute_id');

        $data = [];
        $countOfTotalVariant=1;
        foreach($productAttributerValues as $keyAttribute => $items)
        {
            foreach($items as $key => $value)
            {
                $data[$keyAttribute][$key]['value_id']   = $value->attribute_value_id;
                $data[$keyAttribute][$key]['value_name'] = $this->translations($value->attributeValueTranslations)->value_name;
            }

            $countOfTotalVariant *= count($items->toArray());

        }

        // return $countOfTotalVariant;


        // $attribute_ids = [];
        // foreach($product->productAttributeValues as $item){
        //     if (!in_array($item->attribute_id, $attribute_ids)) {
        //         array_push($attribute_ids, $item->attribute_id);
        //     }
        // }

        // return  ProductAttributeValue::whereIn('attribute_id',$attribute_ids)->get();
        // return $product->productAttributeValues;


        // $data = [];
        // foreach($product->productAttributeValues as $item){
        //     array_push($data, $item);
        // }

        // return view('admin.pages.product.edit.attribute_wise_inventory');
    }


    /*
    |--------------------------------------------------------------------------
    | Product Update
    |--------------------------------------------------------------------------
    |
    |
    */

    public function update(ProductUpdateRequest $request, $id)
    {
        if ($request->new_from > $request->new_to) {
            $this->setErrorMessage(__('The From date should not be greater then the To date'));
            return redirect()->back();
        }

        $attribute_arr = [];
        if (isset($request->attribute_id[0])) {
            foreach($request->attribute_id as $item){
                if (!in_array($item, $attribute_arr)){
                    array_push($attribute_arr, $item);
                }else{
                    $this->setErrorMessage(__('Cannot add duplicted attribute. Please select one.'));
                    return redirect()->back();
                }
            }
        }

        if($request->weight_base_calculation && $request->weight==null){
            $this->setErrorMessage('Weight can not be empty. Please input the weight');
            return redirect()->back();
        }
        else if($request->weight_base_calculation && ($request->weight > 1 || $request->weight < 1)){
            $this->setErrorMessage('For weight base calculation you have to set weight = 1');
            return redirect()->back();
        }

        // ProductAttributeValue::




        if (auth()->user()->can('product-edit'))
        {
            $local = Session::get('currentLocal');

            $product                = Product::find($id);
            if ($request->brand_id) {
                $product->brand_id      = $request->brand_id;
            }
            if (session('currentLocal')=='en') {
                $product->slug          = $this->slug(htmlspecialchars_decode($request->product_name));
            }
            $product->tax_id        = $request->tax_id;
            $product->price         = $request->price; //1st option
            $product->weight        = $request->weight_base_calculation ? $request->weight : null;
            $product->weight_base_calculation  = $request->weight_base_calculation ?? 0;
            $product->special_price = number_format((float)$request->special_price, env('FORMAT_NUMBER'), '.', ''); //2nd option

            if ($request->special_price && $request->price > $request->special_price){
                $product->is_special = 1;
            }else {
                $product->is_special = 0;
            }
            $product->special_price_type = $request->special_price_type;
            $product->special_price_start= $request->special_price_start==true ? date("Y-m-d",strtotime($request->special_price_start)) : null;
            $product->special_price_end  = $request->special_price_end==true ? date("Y-m-d",strtotime($request->special_price_end)) : null;

            $product->selling_price = number_format((float)$request->special_price, env('FORMAT_NUMBER'), '.', '');
            $product->sku           = $request->sku;
            $product->manage_stock  = $request->manage_stock;
            $product->qty           = $request->qty;
            $product->in_stock      = $request->in_stock;
            $product->new_from      = $request->new_from==true ? date("Y-m-d",strtotime($request->new_from)) : null;
            $product->new_to        = $request->new_to==true ? date("Y-m-d",strtotime($request->new_to)) : null;

            if ($request->is_active==1) {
                $product->is_active = 1;
            }else {
                $product->is_active = 0;
            }
            $product->update();

            //---Product Update---
            DB::table('product_translations')
            ->updateOrInsert(
                [
                    'product_id'  => $id,
                    'local'       => $local,
                ],
                [
                    'product_name'      => htmlspecialchars_decode($request->product_name),
                    'description'       => htmlspecialchars_decode($request->description),
                    'short_description' => $request->short_description,
                    'meta_title'      => htmlspecialchars_decode($request->meta_title),
                    'meta_description'=> htmlspecialchars_decode($request->meta_description),
                ]
            );


             //-- Base Image ----------
             if (!empty($request->base_image)){
                $productImage = ProductImage::where('product_id',$id)->where('type','base')->first();
                if ($productImage) {
                    if (File::exists(public_path().$productImage->image)) {
                        File::delete(public_path().$productImage->image);
                        File::delete(public_path().$productImage->image_medium);
                        File::delete(public_path().$productImage->image_small);
                    }
                    $image_name = $this->imageStoreProduct($request->base_image);
                    $productImage->image  = '/images/products/large/'.$image_name;
                    $productImage->image_medium  = '/images/products/medium/'.$image_name;
                    $productImage->image_small  = '/images/products/small/'.$image_name;
                    $productImage->update();
                }else {
                    //check this line later
                    $image_name = $this->imageStoreProduct($request->base_image);

                    $productImage = new ProductImage();
                    $productImage->product_id = $id;
                    $productImage->image  = '/images/products/large/'.$image_name;
                    $productImage->image_medium  = '/images/products/medium/'.$image_name;
                    $productImage->image_small  = '/images/products/small/'.$image_name;
                    $productImage->type  = 'base';
                    $productImage->save();
                }
            }


            //----------------- Multiple Image ---------------
            if (!empty($request->additional_images)) {
                $data = ProductImage::where('product_id',$id)->where('type','additional')->get();
                foreach ($data as $key => $value) {
                    if (File::exists(public_path().$value->image)) {
                        File::delete(public_path().$value->image);
                        File::delete(public_path().$value->image_medium);
                        File::delete(public_path().$value->image_small);
                        $data[$key]->delete();
                    }
                }
                $additionalImagesArray = $request->additional_images;
                foreach($additionalImagesArray as $key => $image){
                    $image_name = $this->imageStoreProduct($image);
                    $productImage = new ProductImage();
                    $productImage->product_id = $id;
                    $productImage->image = '/images/products/large/'.$image_name;
                    $productImage->image_medium = '/images/products/medium/'.$image_name;
                    $productImage->image_small = '/images/products/small/'.$image_name;
                    $productImage->type  = 'additional';
                    $productImage->save();
                }
            }


            //----------------- Category-Product --------------
            if (!empty($request->category_id)) {
                $categoryArrayIds = $request->category_id;
                $product->categories()->sync($categoryArrayIds);
            }

            //-----------------Product-Tag--------------
            if (!empty($request->tag_id)) {
                $tagArrayIds = $request->tag_id;
                $product->tags()->sync($tagArrayIds);
            }

            //-----------------Product-Attribute-------------

            if (!empty($request->attribute_id) && $request->attribute_id[0]) {
                ProductAttributeValue::where('product_id',$product->id)->delete();
                $attributeValueTranslation = AttributeValueTranslation::whereIn('attribute_value_id',$request->attribute_value_id)->get();
                foreach($attributeValueTranslation as $item){
                    $checkProductAttributeValueExists = ProductAttributeValue::where('product_id',$product->id)
                                            ->where('attribute_id',$item->attribute_id)
                                            ->where('attribute_value_id',$item->attribute_value_id)
                                            ->exists();
                                            
                    if (!$checkProductAttributeValueExists) {
                        ProductAttributeValue::insert([
                                'product_id'=> $product->id,
                                'attribute_id'=> $item->attribute_id,
                                'attribute_value_id'=> $item->attribute_value_id
                        ]);
                    }
                }
            }else{
                ProductAttributeValue::where('product_id',$product->id)->delete();
            }
            $this->setSuccessMessage(__('Data Updated Successfully'));
            return redirect()->back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Product Action
    |--------------------------------------------------------------------------
    |
    |
    */

    public function active(Request $request){
        return $this->productService->activeById($request->id);
    }

    public function inactive(Request $request){
        return $this->productService->inactiveById($request->id);
    }

    public function delete(Request $request){
        return $this->productService->destroy($request->id);
    }

    public function bulkAction(Request $request){
        return $this->productService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
    }


    protected function imageStoreProduct($image)
    {
        $directory_large  ='/images/products/large/';
        $directory_medium  ='/images/products/medium/';
        $directory_small  ='/images/products/small/';
        $image_name        = Str::random(10). '.' .$image->getClientOriginalExtension();

        Image::make($image)->fit(720,660)->save(public_path($directory_large.$image_name));
        Image::make($image)->fit(400,400)->save(public_path($directory_medium.$image_name));
        Image::make($image)->fit(100,100)->save(public_path($directory_small.$image_name));

        return $image_name;
    }



    /*
    |--------------------------------------------------------------------------
    | Product Delete
    |--------------------------------------------------------------------------
    |
    |
    */

    // public function delete($id)
    // {
    //     // return Product::withTrashed()->find(37)->restore();
    //     // $onlySoftDeleted = Model::onlyTrashed()->get();

    //     $product = Product::find($id);
    //     $this->deleteWithFile($product);

    //     $product_images = ProductImage::where('product_id',$id);
    //     $this->deleteMultipleDataWithImages($product_images);

    //     session()->flash('type','success');
    //     session()->flash('message','Data Deleted Successfully.');

    //     return redirect()->back();
    // }
}




        // =============== Test (Edit) ==================

        //From DB
        // $attributes =  [];
        // array_push($attributes,'Color','Size','Others'); //$attributes[0]='color';  $attributes[1]='size';
        // foreach($attributes as $item){
        //     $data[$item] = [];
        //     if($item=='Color'){
        //         array_push($data[$item],'Black','White','Red');
        //     }
        //     else if($item=='Size'){
        //         array_push($data[$item],'L','M','S');
        //     }
        //     else if($item=='Others'){
        //         array_push($data[$item],'X','Y');
        //     }
        // }


        //Main Working
        // $row = [];
        // foreach($attributes as $key => $item){

        //         if (count($attributes)==1) {
        //             $row[] = $item;
        //         }

        //         if (count($attributes)==2) {
        //             foreach($data[$item] as $value){
        //                 $row[] = $item.' --- '.$value;
        //             }
        //         }

        //         // if (count($attributes)==3) {
        //         //     foreach($data[$item] as $value2){
        //         //         return $key;
        //         //         // foreach($data[$item] as $value3){
        //         //             // $row[] = $item.' --- '.$value2.' --- '.$value3;
        //         //             $row[] = $item.' --- '.$value2;
        //         //         // }
        //         //     }
        //         // }

        // }

        //done for 2 attribute
        // $row = [];
        // foreach($data as $key => $item){
        //      foreach($data[$key] as $value){
        //          $row[] = $key.' --- '.$value;
        //      }
        // }


        // BFS
        // first_variant_values = $('#'+variantIds[0]).val().split(_getDelimiter(delimiter[variantIds[0] ])); //attribute Value
        //         combinations = first_variant_values;
        //         step = 1;
        //         while(step < variantIds.length) {
        //             var newCombinations = [];
        //             for (var i = 0; i < combinations.length; i++) {
        //                 new_variant_values = $('#'+variantIds[step]).val().split(_getDelimiter(delimiter[variantIds[step] ])); //Next Row attribute Value
        //                 for (var j = 0; j < new_variant_values.length; j++) {
        //                     newCombinations.push(combinations[i]+'/'+new_variant_values[j]);
        //                 }
        //             }
        //             combinations = newCombinations;
        //             step++;
        //         }



        // =============== Test ==================



