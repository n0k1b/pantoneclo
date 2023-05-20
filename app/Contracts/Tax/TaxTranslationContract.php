<?php

namespace App\Contracts\Tax;

interface TaxTranslationContract
{
    public function store($data);

    public function getByIdAndLocale($tax_id, $locale);

    public function updateOrCreate($data);

    public function destroy($tax_id);

    public function bulkAction($type, $ids);
}
