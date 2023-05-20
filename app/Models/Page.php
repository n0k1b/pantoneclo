<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Page extends Model
{
    protected $fillable = ['slug','is_active'];
    protected $dates = ['deleted_at'];


    public function pageTranslations()
    {
        $locale = Session::get('currentLocal');
    	return $this->hasMany(PageTranslation::class,'page_id')
                    ->where('locale',$locale)
                    ->orWhere('locale','en');
    }


    public function pageTranslation() //Remove Later
    {
        $locale = Session::get('currentLocal');
    	return $this->hasOne(PageTranslation::class,'page_id')
                    ->where('locale',$locale);
    }

}
