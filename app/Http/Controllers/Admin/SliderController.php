<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Slider\SliderStoreRequest;
use App\Http\Requests\Slider\SliderUpdateRequest;
use App\Services\CategoryService;
use App\Services\SliderService;
use Illuminate\Http\Request;
use App\Traits\SlugTrait;
use App\Traits\imageHandleTrait;
use App\Traits\ActiveInactiveTrait;
use App\Traits\DeleteWithFileTrait;

class SliderController extends Controller
{
    use SlugTrait, imageHandleTrait, ActiveInactiveTrait, DeleteWithFileTrait;

    private $sliderService;
    private $categoryService;
    public function __construct(SliderService $sliderService, CategoryService $categoryService){
        $this->sliderService   = $sliderService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        if (auth()->user()->can('slider-view'))
        {
            $categories = $this->categoryService->getAllCategories();
            return view('admin.pages.slider.index',compact('categories'));
        }
        return abort('403', __('You are not authorized'));
    }

    public function dataTable(){
        return $this->sliderService->dataTable();
    }


    public function store(SliderStoreRequest $request)
    {
        if (auth()->user()->can('slider-store'))
        {
            if ($request->ajax()) {
                return $this->sliderService->storeSlider($request);
            }
        }

    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $slider            = $this->sliderService->findSlider($request->slider_id);
            $slider_image      = $this->sliderService->sliderImage($slider->slider_image);
            $sliderTranslation = $this->sliderService->findSliderTranslation($request->slider_id);
            return response()->json(['slider' => $slider, 'sliderTranslation'=>$sliderTranslation,'slider_image'=>$slider_image]);
        }
    }


    public function update(SliderUpdateRequest $request)
    {
        if (auth()->user()->can('slider-edit'))
        {
            if ($request->ajax()) {
                return $this->sliderService->updateSlider($request);
            }
        }

    }

    public function active(Request $request)
    {
        if (auth()->user()->can('slider-action'))
        {
            if ($request->ajax()){
                return $this->sliderService->activeById($request->id);
            }
        }

    }

    public function inactive(Request $request)
    {
        if (auth()->user()->can('slider-action'))
        {
            if ($request->ajax()){
                return $this->sliderService->inactiveById($request->id);
            }
        }

    }

    public function destroy(Request $request)
    {
        if (auth()->user()->can('slider-action'))
        {
            if ($request->ajax()){
                return $this->sliderService->destroy($request->id);
            }
        }

    }

    public function bulkAction(Request $request)
    {
        if (auth()->user()->can('slider-action'))
        {
            if ($request->ajax()) {
                return $this->sliderService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
            }
        }

    }
}
