<?php

namespace App\Repositories\AttributeValue;

use App\Contracts\AttributeValue\AttributeValueContract;
use App\Models\AttributeValue;

class AttributeValueRepository implements AttributeValueContract
{
    public function store($data){
        return AttributeValue::create($data);
    }

    public function getAttributeValueIds($attribute_id){
        return AttributeValue::where('attribute_id',$attribute_id)->pluck('id'); //show- attribute_values.id as [2,3,4,5]
    }

    public function deleteByAttributeId($attribute_id){
        AttributeValue::where('attribute_id',$attribute_id)->delete();
    }

    public function deleteByAttributeIdWhereNotInIds($attribute_id, $attributeValueIdArray){
        AttributeValue::where('attribute_id',$attribute_id)->whereNotIn('id',$attributeValueIdArray)->delete();
    }
}
