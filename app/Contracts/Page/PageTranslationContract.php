<?php

namespace App\Contracts\Page;


interface PageTranslationContract
{
    public function store($data);

    public function getByIdAndLocale($page_id, $locale);

    public function updateOrInsert($data);

    public function destroy($page_id);

    public function bulkAction($type, $ids);

}
