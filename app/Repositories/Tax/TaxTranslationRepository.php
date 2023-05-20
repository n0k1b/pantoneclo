<?php

namespace App\Repositories\Tax;

use App\Contracts\Tax\TaxTranslationContract;
use App\Models\TaxTranslation;
use App\Traits\ActiveInactiveTrait;

class TaxTranslationRepository implements TaxTranslationContract
{
    use ActiveInactiveTrait;

    public function store($data){
        return TaxTranslation::create($data);
    }

    public function getByIdAndLocale($tax_id, $locale){
        return TaxTranslation::where('tax_id',$tax_id)->where('locale', $locale)->first();
    }

    public function updateOrCreate($data){
        TaxTranslation::updateOrCreate(
            ['tax_id'  => $data['tax_id'], 'locale' => $data['locale']],
            [
                'tax_class' => $data['tax_class'],
                'tax_name'  => $data['tax_name'],
                'state'     => $data['state'],
                'city'      => $data['city'],
            ]
        );
    }

    public function destroy($tax_id){
        TaxTranslation::where('tax_id', $tax_id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, TaxTranslation::whereIn('tax_id',$ids));
    }

}
