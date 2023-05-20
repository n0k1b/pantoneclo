<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Attribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'attribute_set_id',
        'category_id',
        'is_filterable',
        'is_active'
    ];
    protected $dates = ['deleted_at'];


    //New For AttributeRepository
    public function attributeTranslations()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(AttributeTranslation::class,'attribute_id')
                    ->where('locale',$locale)
                    ->orWhere('locale','en');
    }

    //New For AttributeRepository
    public function attributeSetTranslations()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(AttributeSetTranslation::class,'attribute_set_id','attribute_set_id')
                    ->where('locale',$locale)
                    ->orWhere('locale','en');
    }


    //Attribute
    public function attributeTranslation() //Remove Later
    {
        $locale = Session::get('currentLocal');
        return $this->hasOne(AttributeTranslation::class,'attribute_id')
                    ->where('locale',$locale);
    }

    public function attributeTranslationEnglish() //Remove Later
    {
        return $this->hasOne(AttributeTranslation::class,'attribute_id')
                    ->where('locale','en');
    }

    //Attribute Set
    public function attributeSetTranslation() //Remove Later
    {
        $locale = Session::get('currentLocal');
        return $this->hasOne(AttributeSetTranslation::class,'attribute_set_id','attribute_set_id')
                    ->where('locale',$locale);
    }

    public function attributeSetTranslationEnglish() //Remove Later
    {
        return $this->hasOne(AttributeSetTranslation::class,'attribute_set_id','attribute_set_id')
                    ->where('locale','en');
    }

    public function attributeValue()
    {
    	return $this->hasOne(AttributeValue::class,'attribute_id');
    }

    //For AttibuteController
    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class,'attribute_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class); //db: attribute_category
    }


}
