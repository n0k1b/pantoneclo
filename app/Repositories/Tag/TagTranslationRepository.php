<?php

namespace App\Repositories\Tag;

use App\Contracts\Tag\TagTranslationContract;
use App\Models\TagTranslation;
use App\Traits\ActiveInactiveTrait;

class TagTranslationRepository implements TagTranslationContract
{
    use ActiveInactiveTrait;

    public function storeData($data){
        return TagTranslation::create($data);
    }

    public function getByIdAndLocale($tag_id, $locale){
        return TagTranslation::where('tag_id',$tag_id)->where('local', $locale)->first();
    }

    public function updateOrInsertTagTranslation($data){
        TagTranslation::updateOrCreate(
            ['tag_id'  => $data['tag_id'], 'local' => $data['local'] ],
            ['tag_name'=> $data['tag_name'] ]
        );
    }

    public function destroy($tag_id){
        TagTranslation::where('tag_id', $tag_id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, TagTranslation::whereIn('tag_id',$ids));
    }
}
