<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTranslation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'local',
        'product_name',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
    ];

    protected $dates = ['deleted_at'];

    public function product()
    {
    	return $this->belongsTo(Product::class,'product_id','id');
    }

    public function categoryProducts()
    {
        return $this->hasMany(CategoryProduct::class,'product_id','product_id');
    }
}
