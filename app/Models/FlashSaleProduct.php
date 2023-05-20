<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class FlashSaleProduct extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['flash_sale_id','product_id','end_date','price','qty','position'];


    public function product()
    {
    	return $this->belongsTo(Product::class,'product_id');
    }
}
