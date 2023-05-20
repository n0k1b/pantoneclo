<?php
namespace App\Services;

use App\Contracts\Slider\SliderContract;
use App\Contracts\Slider\SliderTranslationContract;
use App\Traits\WordCheckTrait;
use App\Traits\imageHandleTrait;
use App\Traits\SlugTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class SliderService
{
    use SlugTrait, imageHandleTrait,WordCheckTrait;
    private $sliderContract;
    private $sliderTranslationContract;

    public function __construct(SliderContract $sliderContract, SliderTranslationContract $sliderTranslationContract)
    {
        $this->sliderContract            = $sliderContract;
        $this->sliderTranslationContract = $sliderTranslationContract;
    }

    public function getAllSlider()
    {
        if ($this->wordCheckInURL('sliders')) {
            return $this->sliderContract->getAllSlider();
        }else{
            return $this->sliderContract->getAllActiveSlider();
        }
    }

    public function dataTable()
    {
        if (request()->ajax())
        {
            $sliders = $this->getAllSlider();

            return datatables()->of($sliders)
            ->setRowId(function ($row)
            {
                return $row->id;
            })
            ->addColumn('slider_image', function ($row)
            {
                if ($row->slider_image_secondary!=NULL && (File::exists(public_path($row->slider_image_secondary)))){
                    $url = url("public/".$row->slider_image_secondary);
                    return  '<img src="'. $url .'"/>';
                }else  {
                    return '<img src="https://dummyimage.com/50x50/000000/0f6954.png&text=Slider">';
                }
            })
            ->addColumn('slider_title', function ($row)
            {
                return $row->slider_title;
            })
            ->addColumn('slider_subtitle', function ($row)
            {
                return $row->slider_subtitle;
            })
            ->addColumn('type', function ($row)
            {
                return ucfirst($row->type);
            })
            ->addColumn('text_alignment', function ($row)
            {
                return ucfirst($row->text_alignment);
            })
            ->addColumn('text_color_code', function ($row)
            {
                return $row->text_color;
            })
            ->addColumn('action', function($row)
            {
                if (auth()->user()->can('slider-edit'))
                {
                    $actionBtn    = '<a href="javascript:void(0)" name="edit" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm"><i class="dripicons-pencil"></i></a>
                    &nbsp;' ;
                }

                if (auth()->user()->can('slider-action'))
                {
                    if ($row->is_active==1) {
                        $actionBtn .= '<button type="button" title="Inactive" class="inactive btn btn-warning btn-sm" data-id="'.$row->id.'"><i class="dripicons-thumbs-down"></i></button>';
                    }else {
                        $actionBtn .= '<button type="button" title="Active" class="active btn btn-success btn-sm" data-id="'.$row->id.'"><i class="dripicons-thumbs-up"></i></button>';
                    }
                    $actionBtn .= '<button type="button" title="Delete" class="delete btn btn-danger btn-sm ml-2" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>';
                }
                return $actionBtn;
            })
            ->rawColumns(['slider_image','action'])
            ->make(true);
        }
    }


    protected function requestHandleData($request)
    {
        $data = [];
        $data['slider_slug']    = $this->slug($request->slider_title);
        $data['type']           = $request->type;
        $data['category_id']    = $request->category_id;
        $data['url']            = $request->url;
        $data['target']         = $request->target;
        if ($request->slider_image) {
            if ($request->slider_id) {
                $this->sliderPreviousImageDelete($request->slider_id);
            }
            $data['slider_image']            = $this->imageSliderStore($request->slider_image, $directory='images/sliders/',$width=775, $height=445); //half width
            $data['slider_image_full_width'] = $this->imageSliderStore($request->slider_image, $directory='images/sliders/full_width/',$width=1920, $height=650);
            $data['slider_image_secondary']  = $this->imageSliderStore($request->slider_image, $directory='images/sliders/secondary/',$width=100, $height=58);
        }
        $data['type']           = $request->type;
        $data['text_alignment'] = $request->text_alignment;
        $data['text_color']     = $request->text_color;
        $data['is_active']      = $request->is_active;

        $data['locale']         = Session::get('currentLocal');
        $data['slider_title']   = htmlspecialchars($request->slider_title);
        $data['slider_subtitle']= htmlspecialchars($request->slider_subtitle);

        return $data;
    }


    public function storeSlider($request)
    {
        $exists = $this->checkType($request);
        if (session()->has('exists')) {
            return $exists;
        }

        $data   = $this->requestHandleData($request);

        DB::beginTransaction();
        try{
            $slider = $this->sliderContract->storeData($data);
            $data['slider_id'] = $slider->id;
            $this->sliderTranslationContract->storeData($data);
            DB::commit();
        }catch (Exception $e){
            DB::rollback();
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
        return response()->json(['success' => __('Data Successfully Saved')]);
    }


    public function findSlider($id){
        return $this->sliderContract->getById($id);
    }

    public function findSliderTranslation($slider_id)
    {
        $sliderTranslation = $this->sliderTranslationContract->getByIdAndLocale($slider_id, session('currentLocal'));
        if (!isset($sliderTranslation)) {
            $sliderTranslation =  $this->sliderTranslationContract->getByIdAndLocale($slider_id, 'en');
        }
        return $sliderTranslation;
    }

    public function sliderImage($slider_image_path)
    {
        if($slider_image_path!=NULL && (File::exists(public_path($slider_image_path)))) {
            return url("public/".$slider_image_path);
        }else {
            return 'https://dummyimage.com/100x100/000000/0f6954.png&text=Slider';
        }
    }

    public function updateSlider($request)
    {
        $exists = $this->checkType($request);
        if (session()->has('exists')) {
            return $exists;
        }

        $data  = $this->requestHandleData($request);
        $data['slider_id'] = $request->slider_id;
        DB::beginTransaction();
        try{
            $this->sliderContract->updateDataById($request->slider_id, $data);
            $this->sliderTranslationContract->updateOrInsertSliderTranslation($data);
            DB::commit();
        }catch (Exception $e){
            DB::rollback();
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
        return response()->json(['success' => 'Data Updated Successfully']);
    }


    public function activeById($id){
        return $this->sliderContract->active($id);
    }

    public function inactiveById($id){
        return $this->sliderContract->inactive($id);
    }

    public function destroy($id)
    {
        $this->sliderPreviousImageDelete($id);
        $this->sliderContract->destroy($id);
        $this->sliderTranslationContract->destroy($id);
        return response()->json(['success' => 'Data Deleted Successfully']);
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if ($type=='delete') {
            for ($i=0; $i < count($ids) ; $i++) {
                $this->sliderPreviousImageDelete($ids[$i]);
            }
            $this->sliderContract->bulkAction($type, $ids);
            return $this->sliderTranslationContract->bulkAction($type, $ids);
        }else{
            return $this->sliderContract->bulkAction($type, $ids);
        }
    }


    //Previous Image Delete
    protected function sliderPreviousImageDelete($slider_id)
    {
        $this->previousImageDelete($this->findSlider($slider_id)->slider_image); //half width
        $this->previousImageDelete($this->findSlider($slider_id)->slider_image_full_width);
        $this->previousImageDelete($this->findSlider($slider_id)->slider_image_secondary);
    }


    protected function checkType($request)
    {
        if ($request->type=='category' && $request->category_id==NULL) {
            session()->flash('exists',1);
            return response()->json(['errors' => ['Please select a category']], 422);
        }
        elseif ($request->type=='url' && $request->url==NULL) {
            session()->flash('exists',1);
            return response()->json(['errors' => ['Please fillup the url']], 422);
        }
    }


}
