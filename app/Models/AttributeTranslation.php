<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class AttributeTranslation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attribute_id',
        'locale',
        'attribute_name'
    ];
    protected $dates = ['deleted_at'];


    //Shop Product Controller index()
    public function attributeValueTranslation()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(AttributeValueTranslation::class,'attribute_id','attribute_id')
                    ->where('local',$locale);
    }
}
