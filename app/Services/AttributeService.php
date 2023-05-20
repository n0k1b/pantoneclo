<?php
namespace App\Services;

use App\Contracts\Attribute\AttributeContract;
use App\Contracts\Attribute\AttributeTranslationContract;
use App\Contracts\AttributeValue\AttributeValueContract;
use App\Contracts\AttributeValue\AttributeValueTranslationContract;
use App\Traits\WordCheckTrait;
use App\Traits\SlugTrait;
use Illuminate\Support\Facades\Session;

class AttributeService
{
    use WordCheckTrait,SlugTrait;

    private $attributeContract;
    private $attributeTranslationContract;
    private $attributeValueContract;
    private $attributeValueTranslationContract;
    public function __construct(AttributeContract $attributeContract, AttributeTranslationContract $attributeTranslationContract, AttributeValueContract $attributeValueContract, AttributeValueTranslationContract $attributeValueTranslationContract)
    {
        $this->attributeContract            = $attributeContract;
        $this->attributeTranslationContract = $attributeTranslationContract;
        $this->attributeValueContract = $attributeValueContract;
        $this->attributeValueTranslationContract = $attributeValueTranslationContract;
    }

    public function getAllAttribute()
    {
        if ($this->wordCheckInURL('attributes')) {
            return $this->attributeContract->getAll();
        }else{
            return $this->attributeContract->getAllActiveData();
        }
    }

    public function dataTable()
    {
        if (request()->ajax())
        {
            $attributes = $this->getAllAttribute();

            return datatables()->of($attributes)
                ->setRowId(function ($row){
                    return $row->id;
                })
                ->addColumn('attribute_name', function ($row)
                {
                    return $row->attribute_name ?? null;
                })
                ->addColumn('attribute_set_name', function ($row)
                {
                    return $row->attribute_set_name ?? null;
                })
                ->addColumn('is_filterable', function ($row){
                    if ($row->is_filterable==1) {
                        return "YES";
                    }else {
                        return "NO";
                    }
                })
                ->addColumn('action', function ($row)
                {
                    $actionBtn = "";
                    if (auth()->user()->can('attribute-edit'))
                    {
                        $actionBtn .= '<a href="'.route('admin.attribute.edit', $row->id) .'" class="edit btn btn-primary btn-sm" title="Edit"><i class="dripicons-pencil"></i></a>
                                    &nbsp; ';
                    }
                    if (auth()->user()->can('attribute-action'))
                    {
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
    }


    public function storeAttribute($request)
    {
        $data = $this->requestHandleData($request);
        $attribute = $this->attributeContract->store($data);
        $data['attribute_id'] = $attribute->id;
        $this->attributeTranslationContract->store($data);

        //----------------- Attribute-Category Sync --------------
        $this->attributeCategorySync($request, $attribute);

        //-------- Attribute-Value ----------
        $attributeValueNameArray= $request->value_name;
        if(array_filter($attributeValueNameArray) != []){ //if value_empty it show  [null] when use return, checking that way.

            $attributeValueNameArray = $request->value_name;
            foreach ($attributeValueNameArray as $key => $value)
            {
                $attributeValue = $this->attributeValueContract->store($data);
                $attributeValueData = $this->requestHandleAttributeValueData($data['attribute_id'], $attributeValue->id, $attributeValueNameArray[$key]);
                $this->attributeValueTranslationContract->store($attributeValueData);
            }
        }
        return;
    }


    public function findAttribute($id){
        return $this->attributeContract->getById($id);
    }

    public function findAttributeTranslation($attribute_id)
    {
        $attributeTranslation = $this->attributeTranslationContract->getByIdAndLocale($attribute_id, session('currentLocal'));
        if (!isset($attributeTranslation)) {
            $attributeTranslation =  $this->attributeTranslationContract->getByIdAndLocale($attribute_id, 'en');
        }
        return $attributeTranslation;
    }

    public function getAttributeValueIds($attribute_id){
        return $this->attributeValueContract->getAttributeValueIds($attribute_id);
    }

    public function getAttribiuteValueTranslation($attributeValueIds)
    {
        $attributeValueTranslation = $this->attributeValueTranslationContract->getAttribiuteValueTranslationByIdsAndLocale($attributeValueIds, session('currentLocal'));
        if (count($attributeValueTranslation)==0) {
            $attributeValueTranslation =  $this->attributeValueTranslationContract->getAttribiuteValueTranslationByIdsAndLocale($attributeValueIds, 'en');
        }
        return $attributeValueTranslation;
    }

    public function updateAttribute($request, $id)
    {
        $data = $this->requestHandleData($request);
        $data['attribute_id'] = $id;
        $this->attributeContract->update($data);
        $this->attributeTranslationContract->updateOrInsert($data);

        $attribute = $this->findAttribute($id);

        //----------------- Attribute-Category Sync --------------
        $this->attributeCategorySync($request, $attribute);

        //--------- Value Part--------
        $attributeValueNameArray = $request->value_name;
        $attributeValueIdArray   = $request->attribute_value_id;

        if (empty($attributeValueNameArray)) {
            $this->attributeValueContract->deleteByAttributeId($id);
        }
        if (isset($attributeValueNameArray) && isset($attributeValueIdArray)) {
            $this->attributeValueContract->deleteByAttributeIdWhereNotInIds($id,$attributeValueIdArray);
            foreach ($attributeValueNameArray as $key => $value) {
                $this->attributeValueTranslationContract->update($data, $attributeValueIdArray[$key], $attributeValueNameArray[$key]);
            }
        }
        if(isset($request->add_more_value_name)) {
            $attributeValueNameArray = $request->add_more_value_name;
            foreach ($attributeValueNameArray as $key => $value) {
                $attributeValue = $this->attributeValueContract->store($data);
                $attributeValueData = $this->requestHandleAttributeValueData($data['attribute_id'], $attributeValue->id, $attributeValueNameArray[$key]);
                $this->attributeValueTranslationContract->store($attributeValueData);
            }
        }

    }

    public function activeById($id){
        return $this->attributeContract->active($id);
    }

    public function inactiveById($id){
        return $this->attributeContract->inactive($id);
    }


    public function destroy($id)
    {
        $this->attributeContract->destroy($id);
        $this->attributeTranslationContract->destroy($id);
        return response()->json(['success' => 'Data Deleted Successfully']);
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if ($type=='delete') {
            $this->attributeContract->bulkAction($type, $ids);
            return $this->attributeTranslationContract->bulkAction($type, $ids);
        }else{
            return $this->attributeContract->bulkAction($type, $ids);
        }
    }



    protected function requestHandleData($request)
    {
        $data = [];
        $data['attribute_set_id'] = $request->attribute_set_id;
        $data['slug']             = $this->slug($request->attribute_name);
        $data['is_filterable']    = $request->input('is_filterable',0);
        $data['is_active']        = $request->input('is_active',0);
        $data['locale']           = Session::get('currentLocal');
        $data['attribute_name']   = $request->attribute_name;

        return $data;
    }

    protected function requestHandleAttributeValueData($attribute_id, $attributeValueId, $attributeValueNameArrayKey)
    {
        $data = [];
        $data['attribute_id']         = $attribute_id;
        $data['attribute_value_id']   = $attributeValueId;
        $data['local']                = Session::get('currentLocal');
        $data['value_name']           = $attributeValueNameArrayKey;

        return $data;
    }


    protected function attributeCategorySync($request, $attribute)
    {
        if (!empty($request->category_id)) {
            $categoryArrayIds = $request->category_id;
            $attribute->categories()->sync($categoryArrayIds);
        }
    }

}
