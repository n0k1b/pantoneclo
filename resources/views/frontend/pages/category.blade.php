@php
if (Session::has('currency_rate')){
    $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
}else{
    $CHANGE_CURRENCY_RATE = 1;
    Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
}

if (Session::has('currency_symbol')){
    $CHANGE_CURRENCY_SYMBOL = Session::get('currency_symbol');
}else{
    $CHANGE_CURRENCY_SYMBOL = env('DEFAULT_CURRENCY_SYMBOL');
    Session::put('currency_symbol',$CHANGE_CURRENCY_SYMBOL);
}
@endphp




@extends('frontend.layouts.master')
@section('title','All Categories')

@section('frontend_content')

@inject('category', 'App\Http\Controllers\Frontend\CategoryProductController')

    <!--Breadcrumb Area start-->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="home.html">@lang('file.Home')</a></li>
                        <li class="active">@lang('file.All Category')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Breadcrumb Area ends-->

    <!-- Content Wrapper -->
    <section class="content-wrapper mt-0 mb-5">
        <div class="container">
            @forelse ($categories->where('parent_id',NULL) as $item)
                <div class="row mt-5">
                    @if (isset($item->image) && Illuminate\Support\Facades\File::exists(public_path($item->image)))
                        <h4><a href="{{route('cartpro.category_wise_products',$item->slug)}}"> <img src="{{asset('public/'.$item->image)}}" height="60px" width="80px"> &nbsp;{{$category->translations($item->categoryTranslation)->category_name}} ({{$category_product_count[$item->id]}}) </a></h4>
                    @else
                        <h4><a href="{{route('cartpro.category_wise_products',$item->slug)}}"> <img src="https://dummyimage.com/221x221/e5e8ec/e5e8ec&text=CartPro" height="60px" width="80px"> &nbsp;{{$category->translations($item->categoryTranslation)->category_name}} ({{$category_product_count[$item->id]}}) </a></h4>
                    @endif
                    <hr>
                    <div class="row">
                        @forelse ($item->child as $value)
                            <div class="col-md-2 mt-3">
                                <p><a href="{{route('cartpro.category_wise_products',$value->slug)}}"><b> {{$category->translations($value->categoryTranslation)->category_name}} ({{$category_product_count[$value->id]}})</b></a></p>
                                @forelse ($value->child as $data)
                                    &nbsp;&nbsp;&nbsp; ---- <a href="{{route('cartpro.category_wise_products',$data->slug)}}">{{$category->translations($data->categoryTranslation)->category_name}} ({{$category_product_count[$data->id]}}) </a> <br>
                                @empty
                                @endforelse
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </section>
@endsection
