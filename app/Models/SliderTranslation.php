<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SliderTranslation extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'slider_id',
        'locale',
        'slider_title',
        'slider_subtitle',
    ];

    protected $dates = ['deleted_at'];
}
