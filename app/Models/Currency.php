<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $fillable = ['currency_name','currency_code','currency_symbol'];

    protected $dates = ['deleted_at'];
}
