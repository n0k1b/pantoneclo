<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingHomePageSeoTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_home_page_seo_id',
        'locale',
        'meta_site_name',
        'meta_title',
        'meta_slug',
        'meta_description',
    ];
}
