<?php

namespace App\Repositories\Tag;

use App\Contracts\Tag\TagContract;
use App\Models\Tag;
use App\Traits\ActiveInactiveTrait;
use App\Traits\TranslationTrait;

class TagRepository implements TagContract
{
    use TranslationTrait, ActiveInactiveTrait;

    public function getAll()
    {
        $data = Tag::with('tagTranslation')
            ->orderBy('is_active','DESC')
            ->orderBy('id','DESC')
            ->get()
            ->map(function($tag){
                return [
                    'id'=>$tag->id,
                    'is_active'=>$tag->is_active,
                    'tag_name'=> $this->translations($tag->tagTranslation)->tag_name ?? null,
                    'local'=> $this->translations($tag->tagTranslation)->local ?? null,
                ];
            });

        return json_decode(json_encode($data), FALSE);

    }

    public function getAllActiveData()
    {
        $data = Tag::with('tagTranslation')
            ->where('is_active',1)
            ->orderBy('is_active','DESC')
            ->orderBy('id','DESC')
            ->get()
            ->map(function($tag){
                return [
                    'id'=>$tag->id,
                    'is_active'=>$tag->is_active,
                    'tag_name'=> $this->translations($tag->tagTranslation)->tag_name ?? null,
                    'local'=> $this->translations($tag->tagTranslation)->local ?? null,
                ];
            });
        return json_decode(json_encode($data), FALSE);
    }

    public function storeData($data){
        return Tag::create($data);
    }

    public function getById($id){
        return Tag::find($id);
    }

    public function updateDataById($id, $data){
        return $this->getById($id)->update($data);
    }

    public function active($id){
        return $this->activeData($this->getById($id));
    }

    public function inactive($id){
        return $this->inactiveData($this->getById($id));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, Tag::whereIn('id',$ids));
    }

}
