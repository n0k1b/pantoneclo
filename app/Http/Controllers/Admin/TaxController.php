<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tax\TaxStoreRequest;
use App\Http\Requests\Tax\TaxUpdateRequest;
use Illuminate\Http\Request;
use App\Services\CountryService;
use App\Services\TaxService;

class TaxController extends Controller
{
    private $countryService;
    private $taxService;
    public function __construct(CountryService $countryService, TaxService $taxService){
        $this->countryService = $countryService;
        $this->taxService     = $taxService;
    }

    public function index(){
        if (auth()->user()->can('tax-view')){
            $countries = $this->countryService->getAllCountry();
            return view('admin.pages.tax.index',compact('countries'));
        }
        return abort('403', __('You are not authorized'));
    }

    public function dataTable(){
        return $this->taxService->dataTable();
    }

    public function store(TaxStoreRequest $request){
        return $this->taxService->taxStore($request);
    }

    public function edit(Request $request){
        $data = $this->taxService->taxEdit($request->tax_id);
        return response()->json(['tax' => $data['tax'], 'taxTranslation'=>$data['taxTranslation']]);
    }

    public function update(TaxUpdateRequest $request){
        return $this->taxService->taxUpdate($request);
    }

    public function active(Request $request){
        return $this->taxService->activeById($request->id);
    }

    public function inactive(Request $request){
        return $this->taxService->inactiveById($request->id);
    }

    public function delete(Request $request){
        return $this->taxService->destroy($request->id);
    }

    public function bulkAction(Request $request){
        return $this->taxService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
    }
}
