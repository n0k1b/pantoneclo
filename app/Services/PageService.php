<?php
namespace App\Services;

use App\Contracts\Page\PageContract;
use App\Contracts\Page\PageTranslationContract;
use App\Traits\SlugTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PageService
{
    use SlugTrait;


    private $pageContract;
    private $pageTranslationContract;
    public function __construct(PageContract $pageContract, PageTranslationContract $pageTranslationContract)
    {
        $this->pageContract            = $pageContract;
        $this->pageTranslationContract = $pageTranslationContract;
    }

    public function getAllPages(){
        return $this->pageContract->getAll(); //This is use when we use map()->format
    }

    public function dataTable()
    {
        $pages = $this->getAllPages();
        return datatables()->of($pages)
                ->setRowId(function ($row){
                    return $row->id;
                })
                ->addColumn('page_name', function ($row)
                {
                    return $row->page_name;
                })
                ->addColumn('action', function ($row)
                {
                    $actionBtn = "";
                    $url = url('/');
                    $actionBtn .= '<a target="_blank" title="View" class="btn btn-success btn-sm" href="'.$url.'/page/'.$row->slug.'"><i class="dripicons-preview"></i></a>&nbsp; ';
                    if (auth()->user()->can('page-edit'))
                    {
                        $actionBtn .= '<button type="button" title="Edit" class="edit btn btn-info btn-sm" title="Edit" data-id="'.$row->id.'"><i class="dripicons-pencil"></i></button>
                        &nbsp; ';
                    }
                    if (auth()->user()->can('page-action'))
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
                ->addColumn('copy_url', function ($row)
                {
                    return $row->slug;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    protected function requestHandleData($request)
    {
        $data  = [];
        $data['slug']           = $this->slug(htmlspecialchars_decode($request->page_name));
        $data['is_active']      = $request->input('is_active',0);
        $data['locale']         = Session::get('currentLocal');
        $data['page_name']      = htmlspecialchars_decode($request->page_name);
        $data['body']           = $request->body;

        $data['meta_title']     = $request->meta_title;
        $data['meta_description']= $request->meta_description;
        $data['meta_url']       = $request->meta_url;
        $data['meta_type']      = $request->meta_type;

        return $data;
    }

    public function storePage($request)
    {
        $data   = $this->requestHandleData($request);
        DB::beginTransaction();
        try{
            $page = $this->pageContract->store($data);
            $data['page_id'] = $page->id;
            $this->pageTranslationContract->store($data);
            DB::commit();
        }catch (Exception $e){
            DB::rollback();
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }
        return response()->json(['success' => __('Data Successfully Saved')]);
    }

    public function findPage($id){
        return $this->pageContract->getById($id);
    }


    public function findPageTranslation($page_id)
    {
        $pageTranslation = $this->pageTranslationContract->getByIdAndLocale($page_id, session('currentLocal'));
        if (!isset($pageTranslation)) {
            $pageTranslation =  $this->pageTranslationContract->getByIdAndLocale($page_id, 'en');
        }
        return $pageTranslation;
    }

    public function updatePage($request)
    {
        $data            = $this->requestHandleData($request);
        $data['page_id'] = $request->page_id;
        $this->pageContract->update($data);
        $this->pageTranslationContract->updateOrInsert($data);
        return response()->json(['success' => 'Data Updated Successfully']);
    }

    public function activeById($id){
        return $this->pageContract->active($id);
    }

    public function inactiveById($id){
        return $this->pageContract->inactive($id);
    }

    public function destroy($id)
    {
        $this->pageContract->destroy($id);
        $this->pageTranslationContract->destroy($id);
        return response()->json(['success' => 'Data Deleted Successfully']);
    }

    public function bulkActionByTypeAndIds($type, $ids)
    {
        if ($type=='delete') {
            $this->pageContract->bulkAction($type, $ids);
            return $this->pageTranslationContract->bulkAction($type, $ids);
        }else{
            return $this->pageContract->bulkAction($type, $ids);
        }
    }

}
