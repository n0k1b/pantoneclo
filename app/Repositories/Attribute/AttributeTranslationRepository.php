<?php

namespace App\Repositories\Attribute;

use App\Contracts\Attribute\AttributeTranslationContract;
use App\Models\AttributeTranslation;
use App\Traits\ActiveInactiveTrait;

class AttributeTranslationRepository implements AttributeTranslationContract
{
    use ActiveInactiveTrait;

    public function store($data){
        AttributeTranslation::create($data);
    }

    public function getByIdAndLocale($attribute_id, $locale){
        return AttributeTranslation::where('attribute_id',$attribute_id)->where('locale', $locale)->first();
    }

    public function updateOrInsert($data){
        AttributeTranslation::updateOrCreate(
            [
                'attribute_id'  => $data['attribute_id'],
                'locale' => $data['locale']
            ],
            [
                'attribute_name' => $data['attribute_name'],
            ]
        );
    }

    public function destroy($attribute_id){
        AttributeTranslation::where('attribute_id', $attribute_id)->delete();
    }

    public function bulkAction($type, $attribute_ids){
        return $this->bulkActionData($type, AttributeTranslation::whereIn('attribute_id',$attribute_ids));
    }
}
