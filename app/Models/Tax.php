<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Tax extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'country',
        'zip',
        'rate',
        'based_on',
        'is_active',
    ];

    protected $dates = ['deleted_at'];


    // New Added
    public function taxTranslations()
    {
        $locale = Session::get('currentLocal');
    	return $this->hasMany(TaxTranslation::class,'tax_id')
                    ->where('locale', $locale)
                    ->orWhere('locale','en');
    }

    // Remove Later
    public function taxTranslation()
    {
        $locale = Session::get('currentLocal');
    	return $this->hasOne(TaxTranslation::class,'tax_id')
                ->where('locale',$locale);
    }
    // Remove Later
    public function taxTranslationDefaultEnglish()
    {
    	 return $this->hasOne(TaxTranslation::class,'tax_id')
                        ->where('locale','en');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'tax_id');
    }

    public function taxNameTest()
    {
    	return $this->hasMany(TaxTranslation::class,'tax_id');
    }
}
