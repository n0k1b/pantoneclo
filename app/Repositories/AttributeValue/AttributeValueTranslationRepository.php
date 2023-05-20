<?php

namespace App\Repositories\AttributeValue;

use App\Contracts\AttributeValue\AttributeValueTranslationContract;
use App\Models\AttributeValueTranslation;

class AttributeValueTranslationRepository implements AttributeValueTranslationContract
{
    public function store($data){
        AttributeValueTranslation::create($data);
    }

    public function getAttribiuteValueTranslationByIdsAndLocale($attributeValueIds, $locale)
    {
        return  AttributeValueTranslation::whereIn('attribute_value_id',$attributeValueIds)
                    ->where('local',$locale)
                    ->get();
    }

    public function update($data, $attributeValueId, $attributeValueName)
    {
        AttributeValueTranslation::updateOrCreate(
            [
                'attribute_id'  => $data['attribute_id'],
                'attribute_value_id'  => $attributeValueId,
                'local'               => $data['locale'],
            ],
            [
                'value_name' => $attributeValueName,
            ]
        );
    }


}
