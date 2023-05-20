<?php

namespace App\Repositories\AttributeSet;

use App\Contracts\AttributeSet\AttributeSetContract;
use App\Models\AttributeSet;
use App\Traits\ActiveInactiveTrait;

class AttributeSetRepository implements AttributeSetContract
{
    use ActiveInactiveTrait;

    public function getAll(){
        return AttributeSet::with('attributeSetTranslations')
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map->format();
    }

    public function getAllActiveData()
    {
        return AttributeSet::with('attributeSetTranslations')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map->format();
    }

    public function getAllWithAttributesAndValues()
    {
        return AttributeSet::with('attributeSetTranslation','attributeSetTranslationEnglish','attributes.attributeTranslation',
                'attributes.attributeTranslationEnglish','attributes.attributeValues.attributeValueTranslation')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get();
    }

    public function storeAttributeSet($data){
        return AttributeSet::create($data);
    }

    public function getById($id){
        return AttributeSet::find($id);
    }

    public function updateAttributeSetById($id, $data){
        return AttributeSet::whereId($id)->update($data);
    }

    public function active($id){
        return $this->activeData($this->getById($id));
    }

    public function inactive($id){
        return $this->inactiveData($this->getById($id));
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, AttributeSet::whereIn('id',$ids));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }
}


