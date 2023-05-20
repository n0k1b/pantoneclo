<?php

namespace App\Repositories\Product;

use App\Contracts\Product\ProductContract;
use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Traits\TranslationTrait;

class ProductRepository extends BaseRepository implements ProductContract
{
    use TranslationTrait;


    public function __construct(Product $model){
        parent::__construct($model);
    }

    public function getAll(){
        $products = Product::with('baseImage','productTranslations')
                    ->orderBy('is_active','DESC')
                    ->orderBy('id','DESC')
                    ->get()
                    ->map(function($product){
                        return [
                            'id'                    => $product->id,
                            'brand_id'              => $product->brand_id,
                            'tax_id'                => $product->tax_id,
                            'slug'                  => $product->slug,
                            'price'                 => $product->price,
                            'special_price'         => $product->special_price,
                            'special_price_type'    => $product->special_price_type,
                            'is_special'            => $product->is_special,
                            'special_price_start'   => $product->special_price_start,
                            'special_price_end'     => $product->special_price_end,
                            'selling_price'         => $product->selling_price,
                            'sku'                   => $product->sku,
                            'manage_stock'          => $product->manage_stock,
                            'qty'                   => $product->qty,
                            'in_stock'              => $product->in_stock,
                            'viewed'                => $product->viewed,
                            'is_active'             => $product->is_active,
                            'new_from'              => $product->new_from,
                            'new_to'                => $product->new_to,
                            'avg_rating'            => $product->avg_rating,
                            'local'                 => $this->translations($product->productTranslations)->local ?? null,
                            'product_name'          => $this->translations($product->productTranslations)->product_name ?? null,
                            'description'           => $this->translations($product->productTranslations)->description ?? null,
                            'short_description'     => $this->translations($product->productTranslations)->short_description ?? null,
                            'meta_title'            => $this->translations($product->productTranslations)->meta_title ?? null,
                            'meta_description'      => $this->translations($product->productTranslations)->meta_description ?? null,
                            'baseImage'             => $product->baseImage,
                        ];
                    });

        return json_decode(json_encode($products), FALSE);
    }

    public function getAllActiveData(){
        $products = Product::with('baseImage','productTranslation','productTranslationEnglish')
                    ->where('is_active',1)
                    ->orderBy('is_active','DESC')
                    ->orderBy('id','DESC')
                    ->get();

        return $products;
    }

    public function getAllProductsByIds($ids){
        return Product::whereIn('id',$ids)->get();
    }
}
