<?php

namespace App\Contracts\Tag;

interface TagTranslationContract
{
    public function storeData($data);

    public function getByIdAndLocale($tag_id, $locale);

    public function updateOrInsertTagTranslation($data);

    public function destroy($tag_id);

    public function bulkAction($type, $ids);
}
