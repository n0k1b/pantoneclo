<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\TagStoreRequest;
use App\Http\Requests\Tag\TagUpdateRequest;
use App\Models\Tag;
use App\Models\TagTranslation;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActiveInactiveTrait;
use App\Traits\SlugTrait;
use Illuminate\Support\Facades\App;

class TagController extends Controller
{
    use ActiveInactiveTrait, SlugTrait;

    private $tagService;
    public function __construct(TagService $tagService){
        $this->tagService = $tagService;
    }

    public function index()
    {
        if (auth()->user()->can('tag-view')){
            return view('admin.pages.tag.index');
        }
        return abort('403', __('You are not authorized'));
    }

    public function dataTable(){
        return $this->tagService->dataTable();
    }


    public function store(TagStoreRequest $request){
        return $this->tagService->storeTag($request);
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $tag = $this->tagService->findTag($request->tag_id);
            $tag_translation = $this->tagService->findTagTranslation($request->tag_id);
            return response()->json(['tag' => $tag , 'tag_translation' => $tag_translation]);
        }
    }

    public function update(TagUpdateRequest $request){
        if (auth()->user()->can('tag-edit')){
            return $this->tagService->updateTag($request);
        }
    }

    public function active(Request $request){
        if ($request->ajax()){
            return $this->tagService->activeById($request->id);
        }
    }

    public function inactive(Request $request){
        if ($request->ajax()){
            return $this->tagService->inactiveById($request->id);
        }
    }

    public function destroy(Request $request){
        if ($request->ajax()){
            return $this->tagService->destroy($request->id);
        }
    }


    public function bulkAction(Request $request)
    {
        if ($request->ajax()) {
            return $this->tagService->bulkActionByTypeAndIds($request->action_type, $request->idsArray);
        }
    }
}
