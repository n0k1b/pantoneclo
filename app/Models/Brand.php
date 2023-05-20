<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Session;
use App\Traits\TranslationTrait;

class Brand extends Model
{
    use Notifiable, TranslationTrait;

    protected $fillable = [
        'slug','brand_logo', 'is_active',
    ];

    public $with = ['brandTranslations'];

    public function format()
    {
        return [
            'id'=>$this->id,
            'slug'=>$this->slug,
            'is_active'=>$this->is_active,
            'brand_logo'=>$this->brand_logo ?? null,
            // 'brand_name'=>$this->brandTranslation->brand_name ?? $this->brandTranslationEnglish->brand_name ?? null,
            'brand_name'=>$this->translations($this->brandTranslations)->brand_name,
        ];
    }

    public function products()
    {
    	return $this->hasMany(Product::class,'brand_id');
    }

    public function brandTranslation() //Remove Later
    {
        $locale = Session::get('currentLocal');
        return $this->hasOne(BrandTranslation::class,'brand_id')
                    ->where('local',$locale);
    }

    public function brandTranslationEnglish() //Remove Later
    {
        return $this->hasOne(BrandTranslation::class,'brand_id')
                    ->where('local','en');
    }

    //New For Repository
    public function brandTranslations()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(BrandTranslation::class,'brand_id')
                    ->where('local',$locale)
                    ->orWhere('local','en');
    }
}
