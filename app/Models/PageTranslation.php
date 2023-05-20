<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    protected $fillable = [
        'page_id',
        'locale',
        'page_name',
        'body',

        'meta_title',
        'meta_description',
        'meta_url',
        'meta_type',
    ];

    protected $dates = ['deleted_at'];

}
