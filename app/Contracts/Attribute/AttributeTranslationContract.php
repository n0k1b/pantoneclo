<?php

namespace App\Contracts\Attribute;

interface AttributeTranslationContract
{
    public function store($data);

    public function getByIdAndLocale($attribute_id, $locale);

    public function updateOrInsert($data);

    public function destroy($attribute_id);

    public function bulkAction($type, $attribute_ids);
}
