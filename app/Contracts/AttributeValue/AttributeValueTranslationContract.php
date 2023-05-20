<?php

namespace App\Contracts\AttributeValue;

interface AttributeValueTranslationContract
{
    public function store($data);

    public function getAttribiuteValueTranslationByIdsAndLocale($attributeValueIds, $locale);

    public function update($data, $attributeValueId, $attributeValueName);
}
