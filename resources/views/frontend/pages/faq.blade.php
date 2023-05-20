@extends('frontend.layouts.master')
@section('frontend_content')
    <!--FAQ Section starts-->

    <!--FAQ Section starts-->
    <section class="faq-section">
        <div class="container">

            <div class="row col-12">
                <h1 class="page-title h2 text-center uppercase mb-5">@lang('file.Frequently Asked Questions')</h1>
            </div>

            <div class="row mb-5">
                <div class="col-3"></div>
                <div class="col-6 d-none d-lg-flex d-xl-flex middle-column justify-content-center">
                    <form class="header-search" action="{{route('cartpro.search-faq')}}" method="GET">
                        @csrf
                        <input type="text" list="browsers" placeholder="Search" name="search_faq">
                        <button class="btn btn-search" type="submit"><i class="ti-search"></i></button>
                    </form>
                </div>
                <div class="col-3"></div>
            </div>


            <div class="row">
                <div class="col-md-12 single-faq-section mar-tb-30">
                    @if (!isset($search_product))
                        @foreach ($faq_types as $key => $item)
                            @if ($item->faqs->isNotEmpty())
                                <div class="row">
                                    <div class="col-md-3 col-sm-12 faq-category">
                                        <h3 class="title" data-bs-toggle="collapse" href="#collapseShipping-{{$key}}" aria-expanded="true">{{$item->faqTypeTranslation->type_name}}</h3>
                                    </div>
                                    <div class="col-md-9 col-sm-12 collapse show" id="collapseShipping-{{$key}}">
                                        @foreach ($item->faqs as $value)
                                            <div class="row single-faq-item">
                                                <div class="col-md-6">
                                                    <div class="faq-query ">
                                                        <h5>{{$value->faqTranslation->title}}</h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">
                                                    <div class="faq-ans ">
                                                        <p>{{$value->faqTranslation->description}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @isset($search_product)
                        @foreach ($search_product as $key => $items)
                            <div class="row">
                                <div class="col-md-3 col-sm-12 faq-category">
                                    <h3 class="title" data-bs-toggle="collapse" href="#collapseShipping-{{$key}}" aria-expanded="true">{{$key ?? null}}</h3>
                                </div>
                                <div class="col-md-9 col-sm-12 collapse show" id="collapseShipping-{{$key}}">
                                    @foreach ($items as $value)
                                        <div class="row single-faq-item">
                                            <div class="col-md-6">
                                                <div class="faq-query ">
                                                    <h5>{{$value->title ?? null}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="faq-ans ">
                                                    <p>{{$value->description ?? null}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </section>


    <!--FAQ Section ends-->
@endsection
