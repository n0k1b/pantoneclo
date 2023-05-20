@php
    if (Session::has('currency_rate')){
        $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
    }else{
        $CHANGE_CURRENCY_RATE = 1;
        Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
    }
@endphp

@extends('admin.main')
@section('title','Admin| Show Order')
@section('admin_content')

<section>



    <div class="container-fluid">

        <div class="container-fluid mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="font-weight-bold mt-3"> #{{$order->reference_no}} <small>{{__('file.Order Details')}}</small></h3>
                    <a class="btn btn-danger" href="{{route('admin.order.download-invoice',$order->reference_no)}}" style="color: #fff "><i class="fa fa-file-pdf-o" aria-hidden="true">&nbsp;&nbsp; @lang('file.Download Invoice')</i></a>
                </div>
            </div>
        </div>


        <div class="row ml-1">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="ml-3">{{__('file.Items Ordered')}} ({{$order->orderDetails->count()}})</h4>
                        <hr>
                        <div class="row">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>{{__('file.Product')}}</th>
                                        <th>{{__('file.Unit Price')}}</th>
                                        <th>{{__('file.Quantity')}}</th>
                                        <th>{{__('file.Total Unite Price')}}</th>
                                        <th>@lang('file.Attributes')</th>

                                    </tr>
                                </thead>
                                @forelse ($order->orderDetails as $item)
                                    <tr>
                                        <td>{{$item->product->productTranslation->product_name}}</td>
                                        <td>
                                            <span>
                                                @if(env('CURRENCY_FORMAT')=='suffix')
                                                    {{ number_format((float)$item->price  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                @else
                                                    @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$item->price * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{$item->qty}}</td>
                                        <td>
                                            <span>
                                                @if(env('CURRENCY_FORMAT')=='suffix')
                                                    {{ number_format((float)$item->subtotal  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                @else
                                                    @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$item->subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $attributes = json_decode($item->options);
                                            @endphp
                                            @forelse ($attributes as $key => $item)
                                                @if ($key!='image' && $key!='product_id' && $key!='attributes' && $key!='product_slug' && $key!='category_id' && $key!= 'manage_stock' && $key!='stock_qty' && $key!='in_stock' && $key!='brand_id')
                                                    <p><i><b>{{$key}}</b> :{{$item}}</i></p>
                                                @endif
                                            @empty
                                                <p>NONE</p>
                                            @endforelse
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr>
                                    <td colspan="2"></td>
                                    <td><strong>@lang('file.Total Quantity')</strong> : {{$order->orderDetails->sum('qty')}}</td>
                                    <td><strong>@lang('file.Subtotal')</strong> :
                                        <span>
                                            @if(env('CURRENCY_FORMAT')=='suffix')
                                                {{ number_format((float)$order->orderDetails->sum('subtotal')  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                            @else
                                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->orderDetails->sum('subtotal') * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th><span contenteditable>@lang('file.Subtotal')</span></th>
                                            <td>
                                                <span contenteditable>
                                                    <span>
                                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                                            {{ number_format((float)$order->orderDetails->sum('subtotal')  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                        @else
                                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->orderDetails->sum('subtotal') * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                        @endif
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable>@lang('file.Tax')</span></th>
                                            <td>
                                                <span contenteditable>
                                                    <span>
                                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                                            {{ number_format((float)$order->tax  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                        @else
                                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->tax * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                        @endif
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable>@lang('file.Shipping Cost')</span></th>
                                            <td>
                                                <span contenteditable>
                                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                                        {{ number_format((float)$order->shipping_cost  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                    @else
                                                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->shipping_cost * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable>@lang('file.Discount')</span></th>
                                            <td>
                                                <span contenteditable>
                                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                                        {{ number_format((float)$order->discount  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                    @else
                                                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->discount * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                    @endif
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><span contenteditable><h6 class="text-danger"><b>@lang('file.Total Amount')</b></h6></span></th>
                                            <td>
                                                <h6 class="text-danger">
                                                    <b>
                                                        <span contenteditable>
                                                            @if(env('CURRENCY_FORMAT')=='suffix')
                                                                {{ number_format((float)$order->total  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                            @else
                                                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$order->total * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                            @endif
                                                        </span>
                                                    </b>
                                                </h6>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>{{__('file.Order Information')}}</h4>
                                <hr>
                                <span class="mt-2"><strong>@lang('file.Order Date') :</strong> {{date('d M, Y',strtotime($order->created_at))}}</span><br>
                                <span class="mt-2"><strong>{{__('file.Order Status')}} : </strong>
                                    @php
                                        $btn_color = '';
                                        if ($order->order_status=='order_placed') {
                                            $badge_color = 'badge badge-primary';
                                        }else if($order->order_status=='pending'){
                                            $badge_color = 'badge badge-danger';
                                        }else if($order->order_status=='order_confirmed'){
                                            $badge_color = 'badge badge-secondary';
                                        }else if($order->order_status=='delivery_scheduled'){
                                            $badge_color = 'badge badge-warning';
                                        }else if($order->order_status=='delivery_successful'){
                                            $badge_color = 'badge badge-info';
                                        }else if($order->order_status=='payment_successful'){
                                            $badge_color = 'badge badge-light';
                                        }else if($order->order_status=='order_completed'){
                                            $badge_color = 'badge badge-success';
                                        }
                                    @endphp
                                    <span class="{{$badge_color}} p-1"> {{ucwords(str_replace('_', ' ',$order->order_status))}} </span>
                                </span>
                                <span class="mt-2"><strong>{{__('file.Transaction Id')}} : </strong>{{$order->payment_id ?? null}}</span><br>
                                <span class="mt-2"><strong>{{__('file.Shipping Method')}} : </strong>{{ucwords(str_replace('_', ' ',$order->shipping_method))}}</span><br>
                                <span class="mt-2"><strong>{{__('file.Payment Method')}} : </strong> {{ucwords(str_replace('_', ' ',$order->payment_method))}}</span><br>
                                <span class="mt-2"><strong>{{__('file.Currency')}} : </strong> {{env('DEFAULT_CURRENCY_CODE')}}</span><br>
                                <span class="mt-2"><strong>{{__('file.Currency Rate')}} : </strong> {{number_format((float)$currency_rate, env('FORMAT_NUMBER'), '.', '')}}</span><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>{{__('file.Customer Information')}}</h4>
                                <hr>
                                <span class="mt-2"><strong>@lang('file.Name') :</strong> {{$order->billing_first_name.' '.$order->billing_last_name ?? null}}</span> <br>
                                <span class="mt-2"><strong>@lang('file.Email') :</strong>{{$order->billing_email ?? null}}</span><br>
                                <span class="mt-2"><strong>@lang('file.Phone') :</strong> {{$order->billing_phone ?? null}}</span><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>{{__('file.Billing Address')}}</h4>
                                <hr>
                                <span>{{$order->billing_address_1 ?? null}}</span> <br>
                                <span>{{$order->billing_city ?? null}}</span><br>
                                <span>{{$order->billing_state ?? null}}</span><br>
                                <span>{{$order->billing_zip_code ?? null}}</span><br>
                                <span>{{$order->billing_country ?? null}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($order->shippingDetails)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4>{{__('file.Shipping Address')}}</h4>
                                    <hr>
                                    <span>{{$order->shippingDetails->shipping_address_1 ?? null}}</span> <br>
                                    <span>{{$order->shippingDetails->shipping_city ?? null}}</span><br>
                                    <span>{{$order->shippingDetails->shipping_state ?? null}}</span><br>
                                    <span>{{$order->shippingDetails->shipping_zip_code ?? null}}</span><br>
                                    <span>{{$order->shippingDetails->shipping_country ?? null}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function ($) {
            "use strict";

            $('.orderStatus').on('click',function(){
                var order_status = $(this).val();
                var order_id = $('#order_id').val();
                $.ajax({
                    url: "{{ route('admin.order.status') }}",
                        type: "GET",
                        data: {
                            order_id:order_id,
                            order_status:order_status,
                        },
                        success: function (data) {
                            console.log(data);
                            if (data.type=='success') {
                                location.reload(true);
                            }
                        }
                });
            });


        })(jQuery);
    </script>
@endpush

