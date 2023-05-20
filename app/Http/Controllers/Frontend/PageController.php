<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\TranslationTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    use TranslationTrait;

    public function pageShow($page_slug)
    {
        $page =  Page::with('pageTranslations')->where('slug',$page_slug)->first();
        if ($page) {
            return view('frontend.pages.page',compact('page'));
        }else {
            return view('frontend.includes.page_not_found');
        }
    }

    public function aboutUs()
    {
        $page =  Page::with('pageTranslations')->where('slug','about-us')->first();

        return view('frontend.pages.page',compact('page'));
    }

    public function termAndCondition()
    {
        $page =  Page::with('pageTranslations')->where('slug','terms_and_conditions')->first();
        return view('frontend.pages.page',compact('page'));
    }

    public function faq()
    {
        $page =  Page::with('pageTranslations')->where('slug','faq')->first();

        return view('frontend.pages.page',compact('page'));
    }
}
