<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Review extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(User::class);
    }

    public function productTranslation()
    {
    	$locale = Session::get('currentLocal');
    	return $this->hasOne(ProductTranslation::class,'product_id','product_id')
                ->where('local',$locale);
    }

    public function productTranslationEnglish()
    {
    	return $this->hasOne(ProductTranslation::class,'product_id','product_id')
                        ->where('local','en');
    }
}
