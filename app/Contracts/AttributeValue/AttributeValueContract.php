<?php

namespace App\Contracts\AttributeValue;

interface AttributeValueContract
{
    public function store($data);

    public function getAttributeValueIds($attribute_id);

    public function deleteByAttributeId($attribute_id);

    public function deleteByAttributeIdWhereNotInIds($attribute_id, $attributeValueIdArray);
}
