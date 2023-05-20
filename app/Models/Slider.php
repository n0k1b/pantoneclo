<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Slider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slider_slug',
        'type',
        'category_id',
        'url',
        'slider_image',
        'slider_image_full_width',
        'slider_image_secondary',
        'target',
        'is_active',
        'text_alignment',
        'text_color'
    ];

    protected $dates = ['deleted_at'];

    public function sliderTranslation() //Remove Later
    {
    	return $this->hasMany(SliderTranslation::class,'slider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    //New For Repository
    public function sliderTranslations()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(SliderTranslation::class,'slider_id')
                    ->where('locale',$locale)
                    ->orWhere('locale','en');
    }
}
