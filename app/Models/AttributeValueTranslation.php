<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValueTranslation extends Model
{
    protected $fillable = [
        'attribute_id',
        'attribute_value_id',
        'local',
        'value_name',
    ];
}
