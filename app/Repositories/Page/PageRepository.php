<?php

namespace App\Repositories\Page;

use App\Contracts\Page\PageContract;
use App\Models\Page;
use App\Traits\ActiveInactiveTrait;
use App\Traits\TranslationTrait;

class PageRepository implements PageContract
{
    use TranslationTrait, ActiveInactiveTrait;

    public function getAll()
    {
        $data = Page::with('pageTranslations')
                ->orderBy('is_active','DESC')
                ->orderBy('id','DESC')
                ->get()
                ->map(function($page){
                    return [
                        'id'         =>$page->id,
                        'slug'       => $page->slug,
                        'is_active'  =>$page->is_active,
                        'locale'     => $this->translations($page->pageTranslations)->locale ?? null,
                        'page_name'  => $this->translations($page->pageTranslations)->page_name ?? null,
                        'body'       => $this->translations($page->pageTranslations)->body ?? null,
                        'meta_title' => $this->translations($page->pageTranslations)->meta_title ?? null,
                        'meta_description' => $this->translations($page->pageTranslations)->meta_description ?? null,
                        'meta_url'   => $this->translations($page->pageTranslations)->meta_url ?? null,
                        'meta_type'  => $this->translations($page->pageTranslations)->meta_type ?? null,
                    ];
                });

        return json_decode(json_encode($data), FALSE);
    }

    public function store($data){
        return Page::create($data);
    }

    public function getById($id){
        return Page::find($id);
    }

    public function update($data){
        return $this->getById($data['page_id'])->update($data);
    }

    public function active($id){
        return $this->activeData($this->getById($id));
    }

    public function inactive($id){
        return $this->inactiveData($this->getById($id));
    }

    public function destroy($id){
        $this->getById($id)->delete();
    }

    public function bulkAction($type, $ids){
        return $this->bulkActionData($type, Page::whereIn('id',$ids));
    }


}
