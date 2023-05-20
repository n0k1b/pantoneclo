<?php
namespace App\Traits\Temporary;

use App\Models\SettingHomePageSeo;
use App\Traits\TranslationTrait;

trait SettingHomePageSeoTrait
{
    use TranslationTrait;

    public function settingHomePageSeo()
    {
        $setting_home_page_seo = [];
        $data_home_page  = SettingHomePageSeo::with('settingHomePageSeoTranslations')->get()
                                ->map(function($item){
                                    return [
                                        'id'               => $item->id,
                                        'meta_url'         => $item->meta_url ?? null,
                                        'meta_type'        => $item->meta_type ?? null,
                                        'meta_image'       => $item->meta_image ?? null,
                                        'locale'           => $this->translations($item->settingHomePageSeoTranslations)->locale ?? null,
                                        'meta_site_name'   => $this->translations($item->settingHomePageSeoTranslations)->meta_site_name ?? null,
                                        'meta_title'       => $this->translations($item->settingHomePageSeoTranslations)->meta_title ?? null,
                                        'meta_description' => $this->translations($item->settingHomePageSeoTranslations)->meta_description ?? null,
                                    ];
                                });

        if($data_home_page->isNotEmpty()){
            $setting_home_page_seo = json_decode(json_encode($data_home_page[0]), FALSE);
        }

        return  $setting_home_page_seo;
    }

}
