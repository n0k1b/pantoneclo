<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagTranslation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tag_id',
        'local',
        'tag_name',
    ];

    protected $dates = ['deleted_at'];
}
