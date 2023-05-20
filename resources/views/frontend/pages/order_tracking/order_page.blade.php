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
@section('title','Order Tracking')

@section('frontend_content')
    <!--Breadcrumb Area start-->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul>
                        <li><a href="{{route('cartpro.home')}}">@lang('file.Home')</a></li>
                        <li class="active">@lang('file.Order History')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Breadcrumb Area ends-->

    <!--My account Dashboard starts-->
    <section class="my-account-section">
        <div class="container">
            <div class="row">
                @if ($order)
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>@lang('file.Order Reference No') : {{$order->reference_no}} </strong>
                                    <span class="d-block">{{date('d M, Y',strtotime($order->date))}}</span>
                                </div>
                                <div>
                                    <strong>@lang('file.Delivery Date')</strong>
                                    <span class="d-block">@if($order->delivery_date){{date('d M, Y',strtotime($order->delivery_date))}}@else NONE @endif</span>
                                </div>
                                <div>
                                    <strong>@lang('file.Delivery Time')</strong>
                                    <span class="d-block">{{$order->delivery_time ??'NONE'}}</span>
                                </div>
                                <div>
                                    @php
                                        $bg_color = '';
                                        if ($order->order_status=='order_placed') {
                                            $bg_color = 'primary';
                                        }else if($order->order_status=='pending'){
                                            $bg_color = 'danger';
                                        }else if($order->order_status=='order_confirmed'){
                                            $bg_color = 'secondary';
                                        }else if($order->order_status=='delivery_scheduled'){
                                            $bg_color = 'warning';
                                        }else if($order->order_status=='delivery_successful'){
                                            $bg_color = 'info';
                                        }else if($order->order_status=='payment_successful'){
                                            $bg_color = 'light';
                                        }else if($order->order_status=='order_completed'){
                                            $bg_color = 'success';
                                        }
                                    @endphp
                                    <span class="p-2 badge rounded-pill bg-{{$bg_color}}">{{ucwords(str_replace('_', ' ',$order->order_status))}}</span>
                                </div>
                            </div>
                            <hr class="mt-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            {{ number_format((float)$order->total  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                        @else
                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->total * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                        @endif
                                    </h4>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-success" href="{{route('cartpro.order_tracking_find_details',$order->reference_no)}}"><i class="ti-eye"></i></a> &nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card text-center">
                        <div class="card-body">
                            <h1>@lang('file.You have no order right now')</h1>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

@endsection
