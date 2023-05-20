<?php

namespace App\Repositories\Tax;

use App\Contracts\Tax\TaxContract;
use App\Models\Tax;
use App\Traits\TranslationTrait;
use App\Utilities\Action;

class TaxRepository extends Action implements TaxContract
{
    use TranslationTrait;

    public function getAll()
    {
        $tax = Tax::with('taxTranslations')
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($tax){
                    return [
                        'id'         => $tax->id,
                        'country'    => $tax->country,
                        'zip'        => $tax->zip,
                        'rate'       => $tax->rate,
                        'based_on'   => $tax->based_on,
                        'is_active'  => $tax->is_active,
                        'locale'     => $this->translations($tax->taxTranslations)->locale ?? null,
                        'tax_class'  => $this->translations($tax->taxTranslations)->tax_class ?? null,
                        'tax_name'   => $this->translations($tax->taxTranslations)->tax_name ?? null,
                        'state'      => $this->translations($tax->taxTranslations)->state,
                        'city'       => $this->translations($tax->taxTranslations)->city,
                    ];
                });

        return json_decode(json_encode($tax), FALSE);
    }

    public function getAllActiveData()
    {
        $tax = Tax::with('taxTranslations')
                ->where('is_active',1)
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($tax){
                    return [
                        'id'         => $tax->id,
                        'country'    => $tax->country,
                        'zip'        => $tax->zip,
                        'rate'       => $tax->rate,
                        'based_on'   => $tax->based_on,
                        'is_active'  => $tax->is_active,
                        'locale'     => $this->translations($tax->taxTranslations)->locale ?? null,
                        'tax_class'  => $this->translations($tax->taxTranslations)->tax_class ?? null,
                        'tax_name'   => $this->translations($tax->taxTranslations)->tax_name ?? null,
                        'state'      => $this->translations($tax->taxTranslations)->state,
                        'city'       => $this->translations($tax->taxTranslations)->city,
                    ];
                });

        return json_decode(json_encode($tax), FALSE);
    }

    public function store($data){
        return Tax::create($data);
    }

    public function getById($id){
        return Tax::find($id);
    }

    public function update($data){
        return $this->getById($data['tax_id'])->update($data);
    }

    public function active($id){
        Action::setActive($this->getById($id));
    }

    public function inactive($id){
        Action::setInactive($this->getById($id));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }

    public function bulkAction($type, $ids){
        Action::setBulkAction($type, Tax::whereIn('id',$ids));
    }
}
