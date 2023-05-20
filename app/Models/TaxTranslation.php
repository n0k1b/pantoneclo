<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxTranslation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tax_id',
        'locale',
        'tax_class',
        'tax_name',
        'state',
        'city',
    ];
    
    protected $dates = ['deleted_at'];

}
