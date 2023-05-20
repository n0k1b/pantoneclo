<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\AttributeStoreRequest;
use App\Http\Requests\Attribute\AttributeUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValueTranslation;
use App\Services\AttributeService;
use App\Services\AttributeSetService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Session;
use App\Traits\ActiveInactiveTrait;
use App\Traits\SlugTrait;

class AttributeController extends Controller
{
    use ActiveInactiveTrait, SlugTrait;

    private $attributeSetService;
    private $attributeService;
    private $categoryService;
    public function __construct(AttributeService $attributeService, AttributeSetService $attributeSetService, CategoryService $categoryService){
        $this->attributeSetService = $attributeSetService;
        $this->attributeService    = $attributeService;
        $this->categoryService     = $categoryService;
    }

    public function index()
    {
        if (auth()->user()->can('attribute-view')){
            return view('admin.pages.attribute.index');
        }
        return abort('403', __('You are not authorized'));
    }

    public function datatable(){
        return $this->attributeService->dataTable();
    }

    public function create()
    {
        $attributeSets = $this->attributeSetService->getAllAttributeSet();
        $categories    = $this->categoryService->getAllCategories();
        return view('admin.pages.attribute.create',compact('attributeSets','categories'));
    }

    public function store(AttributeStoreRequest $request)
    {
        if (auth()->user()->can('attribute-store'))
        {
            $this->attributeService->storeAttribute($request);
            $this->setSuccessMessage('Data Saved Successfully');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $attribute                 = $this->attributeService->findAttribute($id);
        $attributeTranslation      = $this->attributeService->findAttributeTranslation($id);
        $attributeValueIds         = $this->attributeService->getAttributeValueIds($id);
        $attributeValueTranslation = $this->attributeService->getAttribiuteValueTranslation($attributeValueIds);
        $attributeSets             = $this->attributeSetService->getAllAttributeSet();
        $categories                = $this->categoryService->getAllCategories();
        return view('admin.pages.attribute.edit',compact('attribute','attributeTranslation','attributeValueTranslation','attributeSets','categories'));
    }


    public function update(AttributeUpdateRequest $request, $id)
    {
        if (auth()->user()->can('attribute-edit'))
        {
            $this->attributeService->updateAttribute($request, $id);
            $this->setSuccessMessage('Successfully Updated');
            return redirect()->back();
        }
    }


    public function active(Request $request){
        return $this->attributeService->activeById($request->id);
    }

    public function inactive(Request $request){
        return $this->attributeService->inactiveById($request->id);
    }


    public function destroy(Request $request){
        return $this->attributeService->destroy($request->id);
    }

    public function bulkAction(Request $request){
        return $this->attributeService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
    }

    public function getAttributeValues(Request $request)
    {
        $attribute = Attribute::find($request->attribute_id);

        if (isset($attribute->attributeValue)) {
            $attributeValueTranslation = AttributeValueTranslation::where('attribute_id',$request->attribute_id)->where('local',Session::get('currentLocal'))->get();
        }else {
            $attributeValueTranslation = NULL;
        }


        $output = '';
		foreach ($attributeValueTranslation as $row)
		{
			$output .= '<option value=' . $row->attribute_value_id . '>' . $row->value_name . '</option>';
		}

        return response()->json($output);
    }
}
