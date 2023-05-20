<?php
namespace App\Services;

use App\Contracts\Tax\TaxContract;
use App\Contracts\Tax\TaxTranslationContract;
use App\Traits\SlugTrait;
use App\Traits\WordCheckTrait;
use App\Utilities\Message;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaxService extends Message
{
    use SlugTrait, WordCheckTrait;

    private $taxContract, $taxTranslationContract;
    public function __construct(TaxContract $taxContract, TaxTranslationContract $taxTranslationContract){
        $this->taxContract = $taxContract;
        $this->taxTranslationContract = $taxTranslationContract;
    }

    public function getAllTax(){
        if ($this->wordCheckInURL('taxes')) {
            return $this->taxContract->getAll();
        }else{
            return $this->taxContract->getAllActiveData();
        }
    }

    public function dataTable()
    {
        $taxes = $this->getAllTax();

        return datatables()->of($taxes)
            ->setRowId(function ($row){
                return $row->id;
            })
            ->addColumn('action', function ($row){
                $actionBtn = "";
                if (auth()->user()->can('tax-edit')){
                    $actionBtn .= '<button type="button" title="Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                    &nbsp; ';
                }
                if (auth()->user()->can('tax-action')){
                    if ($row->is_active==1) {
                        $actionBtn .= '<button type="button" title="Inactive" class="inactive btn btn-warning btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-down"></i></button>';
                    }else {
                        $actionBtn .= '<button type="button" title="Active" class="active btn btn-success btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-up"></i></button>';
                    }
                    $actionBtn .= '<button type="button" title="Delete" class="delete btn btn-danger btn-sm ml-2" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function taxStore($request)
    {
        if (!auth()->user()->can('tax-store')){
            return Message::getPermissionMessage();
        }

        $data   = $this->requestHandleData($request);
        DB::beginTransaction();
        try{
            $tax = $this->taxContract->store($data);
            $data['tax_id'] = $tax->id;
            $this->taxTranslationContract->store($data);
            DB::commit();
        }catch (Exception $e){
            DB::rollback();
            return Message::getErrorMessage($e->getMessage());
        }
        return Message::storeSuccessMessage();
    }

    public function taxEdit($id)
    {
        $tax            = $this->findPage($id);
        $taxTranslation = $this->findTaxTranslation($id);
        return ['tax' => $tax, 'taxTranslation' => $taxTranslation ];
    }

    public function findPage($id){
        return $this->taxContract->getById($id);
    }

    public function findTaxTranslation($tax_id){
        $taxTranslation = $this->taxTranslationContract->getByIdAndLocale($tax_id, session('currentLocal'));
        if (!isset($taxTranslation)) {
            $taxTranslation =  $this->taxTranslationContract->getByIdAndLocale($tax_id, 'en');
        }
        return $taxTranslation;
    }

    public function taxUpdate($request)
    {
        if (!auth()->user()->can('tax-edit')){
            return Message::getPermissionMessage();
        }

        $data   = $this->requestHandleData($request, $request->tax_id);
        DB::beginTransaction();
        try{
            $this->taxContract->update($data);
            $this->taxTranslationContract->updateOrCreate($data);
            DB::commit();
        }catch (Exception $e){
            DB::rollback();
            return Message::getErrorMessage($e->getMessage());
        }
        return Message::updateSuccessMessage();
    }

    public function activeById($id){
        if (!auth()->user()->can('tax-action')){
            return Message::getPermissionMessage();
        }
        $this->taxContract->active($id);
        return Message::activeSuccessMessage();
    }

    public function inactiveById($id){
        if (!auth()->user()->can('tax-action')){
            return Message::getPermissionMessage();
        }
        $this->taxContract->inactive($id);
        return Message::inactiveSuccessMessage();
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('tax-action')){
            return Message::getPermissionMessage();
        }
        $this->taxContract->destroy($id);
        $this->taxTranslationContract->destroy($id);
        return Message::deleteSuccessMessage();
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if (!auth()->user()->can('tax-action')){
            return Message::getPermissionMessage();
        }

        if ($type=='delete') {
            $this->taxContract->bulkAction($type, $ids);
            $this->taxTranslationContract->bulkAction($type, $ids);
            return Message::deleteSuccessMessage();
        }else{
            $this->taxContract->bulkAction($type, $ids);
            return $type=='active' ? Message::activeSuccessMessage() : Message::inactiveSuccessMessage();
        }
    }

    protected function requestHandleData($request, $id=null)
    {
        $data  = [];
        $data['country']  = $request->country;
        $data['zip']      = $request->zip;
        $data['rate']     = $request->rate;
        $data['based_on'] = $request->based_on;
        $data['is_active']= $request->is_active;

        $data['locale']   = Session::get('currentLocal');
        $data['tax_class']= $request->tax_class;
        $data['tax_name'] = $request->tax_name;
        $data['state']    = $request->state;
        $data['city']     = $request->city;

        if($id){
            $data['tax_id'] = $id;
        }
        return $data;
    }
}
