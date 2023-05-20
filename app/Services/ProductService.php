<?php
namespace App\Services;

use App\Contracts\Category\CategoryProductContract;
use App\Contracts\Product\ProductAttributeValueContract;
use App\Contracts\Product\ProductContract;
use App\Contracts\Product\ProductImageContract;
use App\Contracts\Product\ProductTagContract;
use App\Contracts\Product\ProductTranslationContract;
use App\Traits\WordCheckTrait;
use App\Utilities\Message;
use Illuminate\Support\Facades\File;
use Str;

class ProductService extends Message
{
    use WordCheckTrait;

    private $productContract;
    private $productTranslationContract;
    private $productImageContract;
    private $productAttributeValueContract;
    private $categoryProductContract;
    private $productTagContract;

    public function __construct(ProductContract $productContract,
                                ProductTranslationContract $productTranslationContract,
                                ProductAttributeValueContract $productAttributeValueContract,
                                ProductImageContract $productImageContract,
                                CategoryProductContract $categoryProductContract,
                                ProductTagContract $productTagContract){
        $this->productContract            = $productContract;
        $this->productTranslationContract = $productTranslationContract;
        $this->productImageContract       = $productImageContract;
        $this->productAttributeValueContract= $productAttributeValueContract;
        $this->categoryProductContract    = $categoryProductContract;
        $this->productTagContract         = $productTagContract;
    }

    public function getAllProducts(){
        if ($this->wordCheckInURL('products')) {
            return $this->productContract->getAll();
        }else{
            return $this->productContract->getAllActiveData();
        }
    }


    public function dataTable()
    {
        $products = $this->getAllProducts();

        return datatables()->of($products)
        ->setRowId(function ($row){
            return $row->id;
        })
        ->addColumn('image', function ($row)
        {
            if (($row->baseImage==null) || ($row->baseImage->type!='base')) {
                $url = 'https://dummyimage.com/50x50/000/fff';
            }elseif ($row->baseImage->type=='base') {
                if (!File::exists(public_path($row->baseImage->image_small))) {
                    $url = 'https://dummyimage.com/50x50/000/fff';
                }else {
                    $url = url("public/".$row->baseImage->image_small);
                }
            }
            return '<img src="'. $url .'" height="50px" width="50px"/>';
        })
        ->addColumn('product_name', function ($row)
        {
            return Str::limit($row->product_name, 50, $end='...') ?? Str::limit($row->product_name, 50, $end='...');
        })
        ->addColumn('price', function ($row)
        {
            if ($row->special_price > 0) {
                if(env('CURRENCY_FORMAT')=='prefix'){
                    return  '<span>'.env('DEFAULT_CURRENCY_SYMBOL').number_format((float)$row->special_price, env('FORMAT_NUMBER'), '.', '').'</span></br><span class="text-danger"><del>'.env('DEFAULT_CURRENCY_SYMBOL').number_format((float)$row->price, env('FORMAT_NUMBER'), '.', '').'</del></span>';
                }else{
                    return  '<span>'.number_format((float)$row->special_price, env('FORMAT_NUMBER'), '.', '').env('DEFAULT_CURRENCY_SYMBOL').'</span></br><span class="text-danger"><del>'.number_format((float)$row->price, env('FORMAT_NUMBER'), '.', '').env('DEFAULT_CURRENCY_SYMBOL').'</del></span>';
                }
            }else {
                if(env('CURRENCY_FORMAT')=='prefix'){
                    return env('DEFAULT_CURRENCY_SYMBOL').number_format((float)$row->price, env('FORMAT_NUMBER'), '.', '');
                }else{
                    return number_format((float)$row->price, env('FORMAT_NUMBER'), '.', '').env('DEFAULT_CURRENCY_SYMBOL');
                }
            }
        })
        ->addColumn('action', function ($row)
        {
            $actionBtn = "";
            if (auth()->user()->can('product-edit'))
            {
                $actionBtn .= '<a href="'.route('admin.products.edit', $row->id) .'" class="edit btn btn-primary btn-sm" title="Edit"><i class="dripicons-pencil"></i></a>
                &nbsp; ';
            }
            if (auth()->user()->can('product-action'))
            {
                if ($row->is_active==1) {
                    $actionBtn .= '<button type="button" title="Inactive" class="inactive btn btn-warning btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-down"></i></button> &nbsp';;
                }else {
                    $actionBtn .= '<button type="button" title="Active" class="active btn btn-success btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-up"></i></button>  &nbsp';
                }
            }
                // $actionBtn .= '<button type="button" onclick="return confirm(\'Are you sure to delete ?\')" title="Delete" class="delete btn btn-danger btn-sm ml-2" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>';
                $actionBtn .= '<button type="button" title="Delete" class="delete btn btn-danger btn-sm ml-2" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>';

            return $actionBtn;
        })
        ->rawColumns(['image','action','price'])
        ->make(true);
    }



    public function activeById($id){
        if (!auth()->user()->can('product-action')){
            return Message::getPermissionMessage();
        }
        $this->productContract->active($id);
        return Message::activeSuccessMessage();
    }


    public function inactiveById($id){
        if (!auth()->user()->can('product-action')){
            return Message::getPermissionMessage();
        }
        $this->productContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }


    public function destroy($id){
        if (!auth()->user()->can('product-action')){
            return Message::getPermissionMessage();
        }
        $product = $this->productContract->getById($id);
        $product->categories()->detach();  // PIVOT
        $product->tags()->detach();  // PIVOT

        $this->productContract->destroy('id', $id);
        $this->productTranslationContract->destroy('product_id', $id);
        $this->productAttributeValueContract->destroy('product_id', $id);
        //Image
        $this->deleteImageFileByProductId($id);
        $this->productImageContract->destroy('product_id', $id);

        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids){
        if (!auth()->user()->can('product-action')){
            return Message::getPermissionMessage();
        }
        if ($type=='delete') {
            $this->productTranslationContract->bulkAction($type, 'product_id', $ids);
            $this->categoryProductContract->bulkAction($type, 'product_id', $ids);
            $this->productTagContract->bulkAction($type, 'product_id', $ids);
            $this->productAttributeValueContract->bulkAction($type, 'product_id', $ids);
            // Image Section
            $products = $this->productContract->getAllProductsByIds($ids);
            foreach ($products as $item) {
                $this->deleteImageFileByProductId($item->id);
            }
            $this->productImageContract->bulkAction($type, 'product_id', $ids);
        }
        $this->productContract->bulkAction($type, 'id', $ids);

        if ($type=='delete') {
            return Message::deleteSuccessMessage();
        }else{
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }

    protected function deleteImageFileByProductId($product_id){
        $productImage = $this->productImageContract->getAllImageByProductId($product_id);
        if ($productImage){
            foreach ($productImage as $value){
                if (File::exists(public_path().$value->image)){
                    File::delete(public_path().$value->image);
                    File::delete(public_path().$value->image_medium);
                    File::delete(public_path().$value->image_small);
                }
            }
        }
    }

}
