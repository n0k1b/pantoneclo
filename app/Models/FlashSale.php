<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class FlashSale extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function flashSaleTranslations()
    {
    	return $this->hasMany(FlashSaleTranslations::class,'flash_sale_id');
    }

    public function flashSaleProducts()
    {
    	return $this->hasMany(FlashSaleProduct::class,'flash_sale_id');
    }

    //latest
    public function flashSaleTranslation()
    {
        $locale = Session::get('currentLocal');
    	return $this->hasOne(FlashSaleTranslations::class,'flash_sale_id')
                ->where('local',$locale);
    }

    public function flashSaleTranslationEnglish()
    {
    	return $this->hasOne(FlashSaleTranslations::class,'flash_sale_id')
                ->where('local','en');
    }


}
