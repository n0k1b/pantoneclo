<?php

namespace App\Repositories\Attribute;

use App\Contracts\Attribute\AttributeContract;
use App\Models\Attribute;
use App\Traits\ActiveInactiveTrait;
use App\Traits\TranslationTrait;

class AttributeRepository implements AttributeContract
{
    use TranslationTrait, ActiveInactiveTrait;

    public function getAll()
    {
        $data = Attribute::with('attributeTranslations','attributeSetTranslations')
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($attribute){
                    return [
                        'id'         =>$attribute->id,
                        'slug'       => $attribute->slug,
                        'attribute_set_id' => $attribute->attribute_set_id,
                        'is_filterable' => $attribute->is_filterable,
                        'is_active'  =>$attribute->is_active,
                        'locale'     => $this->translations($attribute->attributeTranslations)->locale ?? null,
                        'attribute_set_name'  => $this->translations($attribute->attributeSetTranslations)->attribute_set_name ?? null,
                        'attribute_name'  => $this->translations($attribute->attributeTranslations)->attribute_name ?? null,
                    ];
                });

        return json_decode(json_encode($data), FALSE);
    }

    public function getAllActiveData()
    {
        $data = Attribute::with('attributeTranslations','attributeSetTranslations')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($attribute){
                    return [
                        'id'         =>$attribute->id,
                        'slug'       => $attribute->slug,
                        'attribute_set_id' => $attribute->attribute_set_id,
                        'is_filterable' => $attribute->is_filterable,
                        'is_active'  =>$attribute->is_active,
                        'locale'     => $this->translations($attribute->attributeTranslations)->locale ?? null,
                        'attribute_set_name'  => $this->translations($attribute->attributeSetTranslations)->attribute_set_name ?? null,
                        'attribute_name'  => $this->translations($attribute->attributeTranslations)->attribute_name ?? null,
                    ];
                });

        return json_decode(json_encode($data), FALSE);
    }

    public function store($data){
        return Attribute::create($data);
    }

    public function getById($id){
        return Attribute::with('categories')->find($id);
    }

    public function update($data){
        return $this->getById($data['attribute_id'])->update($data);
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
        return $this->bulkActionData($type, Attribute::whereIn('id',$ids));
    }
}
