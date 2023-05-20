<?php

namespace App\Repositories\Page;

use App\Contracts\Page\PageTranslationContract;
use App\Models\PageTranslation;
use App\Traits\ActiveInactiveTrait;

class PageTranslationRepository implements PageTranslationContract
{
    use ActiveInactiveTrait;

    public function store($data){
        return PageTranslation::create($data);
    }

    public function getByIdAndLocale($page_id, $locale){
        return PageTranslation::where('page_id',$page_id)->where('locale', $locale)->first();
    }

    public function updateOrInsert($data)
    {
        PageTranslation::updateOrCreate(
            ['page_id'  => $data['page_id'], 'locale' => $data['locale']],
            [
                'page_name'     => $data['page_name'],
                'body'          => $data['body'],
                'meta_title'    => $data['meta_title'],
                'meta_description'=> $data['meta_description'],
                'meta_url'      => $data['meta_url'],
                'meta_type'     => $data['meta_type'],
            ]
        );
    }

    public function destroy($page_id){
        PageTranslation::where('page_id', $page_id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, PageTranslation::whereIn('page_id',$ids));
    }

}
