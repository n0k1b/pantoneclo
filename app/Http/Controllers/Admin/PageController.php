<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\PageStoreRequest;
use App\Http\Requests\Page\PageUpdateRequest;
use App\Services\PageService;
use Illuminate\Http\Request;
use App\Traits\ActiveInactiveTrait;
use App\Traits\SlugTrait;

class PageController extends Controller
{
    use ActiveInactiveTrait, SlugTrait;

    private $pageService;
    public function __construct(PageService $pageService){
        $this->pageService   = $pageService;
    }

    public function index()
    {
        if (auth()->user()->can('page-view')){
            return view('admin.pages.page.index');
        }
        return abort('403', __('You are not authorized'));
    }

    public function dataTable(){
        return $this->pageService->dataTable();
    }

    public function store(PageStoreRequest $request)
    {
        if (auth()->user()->can('page-store')){
            return $this->pageService->storePage($request);
        }
    }

    public function edit(Request $request)
    {
        $page               = $this->pageService->findPage($request->page_id);
        $page_translation   = $this->pageService->findPageTranslation($request->page_id);
        return response()->json(['page'=> $page, 'page_translation' => $page_translation, 'page_translation_body'=> htmlspecialchars_decode($page_translation->body)]);
    }

    public function update(PageUpdateRequest $request)
    {
        if (auth()->user()->can('page-edit')){
            return $this->pageService->updatePage($request);
        }
    }

    public function active(Request $request){
        return $this->pageService->activeById($request->id);
    }

    public function inactive(Request $request){
        return $this->pageService->inactiveById($request->id);
    }

    public function destroy(Request $request){
        return $this->pageService->destroy($request->id);
    }

    public function bulkAction(Request $request){
        return $this->pageService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
    }


}
