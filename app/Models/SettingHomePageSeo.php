<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class SettingHomePageSeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_url',
        'meta_type',
        'meta_image',
    ];

    public function settingHomePageSeoTranslations()
    {
        $locale = Session::get('currentLocal');
        return $this->hasMany(SettingHomePageSeoTranslation::class,'setting_home_page_seo_id')
                    ->where('locale',$locale)
                    ->orWhere('locale','en');
    }
}
