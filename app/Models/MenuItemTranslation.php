<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    protected $fillable = [
        'menu_item_id',
        'menu_item_name',
        'locale',
    ];
}
