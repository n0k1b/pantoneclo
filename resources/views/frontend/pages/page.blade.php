



@extends('frontend.layouts.master')

@inject('translation', 'App\Http\Controllers\Frontend\PageController')

@if ($page->pageTranslations)
    <meta product="og:title" content="{{$translation->translations($page->pageTranslations)->meta_title}}">
    <meta product="og:description" content="{{$translation->translations($page->pageTranslations)->meta_description}}">
    <meta product="og:url" content="{{$translation->translations($page->pageTranslations)->meta_url}}">
    <meta product="og:type" content="{{$translation->translations($page->pageTranslations)->meta_type}}">
@endif

@section('frontend_content')

    <div class="breadcrumb-section">
        <div class="container">
            <ul>
                <li><a href="{{route('cartpro.home')}}">@lang('file.Home')</a></li>
                <li>{{$translation->translations($page->pageTranslations)->page_name}}</li>
            </ul>
        </div>
    </div>
    <!--FAQ Section starts-->
    <section class="faq-section">
        <div class="container">
            <div class="row">
                @if ($page->pageTranslations)
                    {!! htmlspecialchars_decode($translation->translations($page->pageTranslations)->body ?? null) !!}
                @else
                    <h1>@lang('file.Empty Data')</h1>
                @endif
            </div>
        </div>
    </section>
    <!--FAQ Section ends-->
@endsection
