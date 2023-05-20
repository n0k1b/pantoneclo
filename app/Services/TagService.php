<?php
namespace App\Services;

use App\Contracts\Tag\TagContract;
use App\Contracts\Tag\TagTranslationContract;
use App\Traits\SlugTrait;
use App\Traits\WordCheckTrait;

class TagService
{
    use SlugTrait, WordCheckTrait;

    private $tagContract, $tagTranslationContract;
    public function __construct(TagContract $tagContract, TagTranslationContract $tagTranslationContract){
        $this->tagContract = $tagContract;
        $this->tagTranslationContract = $tagTranslationContract;
    }

    public function getAllTag()
    {
        if ($this->wordCheckInURL('tags')) {
            return $this->tagContract->getAll();
        }else{
            return $this->tagContract->getAllActiveData();
        }
    }

    public function dataTable()
    {
        if (request()->ajax()){
            $tags = $this->getAllTag();

            return datatables()->of($tags)
            ->setRowId(function ($row){
                return $row->id;
            })
            ->addColumn('tag_name', function ($row){
                return $row->tag_name ?? "";
            })
            ->addColumn('action', function ($row)
            {
                $actionBtn = "";
                if (auth()->user()->can('tag-edit'))
                {
                    $actionBtn .= '<button type="button" title="Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                    &nbsp; ';
                }
                if (auth()->user()->can('tag-action'))
                {
                    if ($row->is_active==1) {
                        $actionBtn .= '<button type="button" title="Inactive" class="inactive btn btn-warning btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-down"></i></button>';
                    }else {
                        $actionBtn .= '<button type="button" title="Active" class="active btn btn-success btn-sm" data-id="'.$row->id.'"><i class="fa fa-thumbs-up"></i></button>';
                    }
                    $actionBtn .= '<button type="button" title="Delete" class="delete btn btn-danger btn-sm ml-2" title="Delete" data-id="'.$row->id.'"><i class="dripicons-trash"></i></button>
                            &nbsp; ';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    protected function requestHandleData($request)
    {
        $data = [];
        if (session('currentLocal')=='en') {
            $data['slug']      = $this->slug($request->tag_name);
        }
        $data['is_active'] = $request->input('is_active',0);
        $data['tag_name']  = $request->tag_name;
        $data['local']     = session('currentLocal');

        return $data;
    }

    public function storeTag($request)
    {
        if (request()->ajax())
        {
            if (env('USER_VERIFIED')!=1) {
                return response()->json(['demo' => 'Disabled for demo !']);
            }
            $data  = $this->requestHandleData($request);
            $tag   = $this->tagContract->storeData($data);

            $data['tag_id'] = $tag->id;
            $this->tagTranslationContract->storeData($data);

            return response()->json(['success' => __('Data Successfully Saved')]);
        }
    }

    public function findTag($id){
        return $this->tagContract->getById($id);
    }

    public function findTagTranslation($tag_id){
        $tagTranslation = $this->tagTranslationContract->getByIdAndLocale($tag_id, session('currentLocal'));
        if (!isset($tagTranslation)) {
            $tagTranslation =  $this->tagTranslationContract->getByIdAndLocale($tag_id, 'en');
        }
        return $tagTranslation;
    }

    public function updateTag($request)
    {
        if (request()->ajax())
        {
            if (env('USER_VERIFIED')!=1) {
                return response()->json(['demo' => 'Disabled for demo !']);
            }
            $data  = $this->requestHandleData($request);
            $data['tag_id'] = $request->tag_id;

            $this->tagContract->updateDataById($request->tag_id, $data);
            $this->tagTranslationContract->updateOrInsertTagTranslation($data);
            return response()->json(['success' => 'Data Updated Successfully']);
        }
    }

    public function activeById($id)
    {
        if (env('USER_VERIFIED')!=1) {
            return response()->json(['demo' => 'Disabled for demo !']);
        }
        return $this->tagContract->active($id);
    }

    public function inactiveById($id)
    {
        if (env('USER_VERIFIED')!=1) {
            return response()->json(['demo' => 'Disabled for demo !']);
        }
        return $this->tagContract->inactive($id);
    }

    public function destroy($id)
    {
        if (env('USER_VERIFIED')!=1) {
            return response()->json(['demo' => 'Disabled for demo !']);
        }
        $this->tagContract->destroy($id);
        $this->tagTranslationContract->destroy($id);
        return response()->json(['success' => 'Data Deleted Successfully']);
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if (env('USER_VERIFIED')!=1) {
            return response()->json(['demo' => 'Disabled for demo !']);
        }
        if ($type=='delete') {
            $this->tagContract->bulkAction($type, $ids);
            return $this->tagTranslationContract->bulkAction($type, $ids);
        }else{
            return $this->tagContract->bulkAction($type, $ids);
        }
    }

}
