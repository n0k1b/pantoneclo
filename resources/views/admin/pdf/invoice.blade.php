@php
    if (Session::has('currency_rate')){
        $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
    }else{
        $CHANGE_CURRENCY_RATE = 1;
        Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
    }
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Hello, world!</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        p {
            font-size: 90%;
            line-height: 80%;
        }

        tbody {
            font-size: 80%;
            margin:0px;
            padding: 5px;
        }

        .table thead tr th {
            border: 1px solid #000;
            font-size: 80%;
            margin:0px;
            padding: 5px;

        }
        .table tr td {
            border: 1px solid #000;
            font-size: 80%;
            margin:0px;
            padding: 5px;
        }
        #heading{
            font-size: 80%;
            color: #CE7749;
            text-align: center;
        }
        #normal-heading{
            font-size: 70%;
            color: #000
        }
    </style>
  </head>
  <body>

    <div class="container mt-5">
            <div align="center">
                <img class="img-fluid mb-4" src="{{asset($header_logo_path)}}" width="200px">
            </div>

            <div class="container-fluid mb-3">
                <div class="card">
                    <div class="card-body">
                        <p class="font-weight-bold"> # {{$reference_no}} <small>{{__('file.Order Details')}}</small></p>
                    </div>
                </div>
            </div>

            <div class="row ml-1">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <p class="ml-3">{{__('file.Items Ordered')}} ({{count($order_details)}})</p>
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

                                    @forelse ($order_details as $item)
                                        <tr>
                                            <td>{{$item['product']['product_translation']['product_name']}}</td>
                                            <td>
                                                <span>
                                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                                        {{ number_format((float)$item['price']  * 1, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                    @else
                                                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$item['price'] * 1, env('FORMAT_NUMBER'), '.', '') }}
                                                    @endif
                                                </span>
                                            </td>

                                            <td>{{$item['qty']}}</td>
                                            <td>
                                                <span>
                                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                                        {{ number_format((float)$item['subtotal']  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                    @else
                                                        @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$item['subtotal'] * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $attributes = json_decode($item['options']);
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
                                        <td><strong>@lang('file.Total Qty')</strong> : {{$total_qty}}</td>
                                        <td colspan="2"><strong>@lang('file.Subtotal')</strong> :
                                            <span>
                                                @if(env('CURRENCY_FORMAT')=='suffix')
                                                    {{ number_format((float)$subtotal  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                @else
                                                    @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
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
                                                                {{ number_format((float)$subtotal  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                            @else
                                                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$subtotal * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
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
                                                                {{ number_format((float)$tax  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                            @else
                                                                @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$tax * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
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
                                                            {{ number_format((float)$shipping_cost  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                        @else
                                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$shipping_cost * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span contenteditable>@lang('file.Discount')</span></th>
                                                <td>
                                                    <span contenteditable>
                                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                                            {{ number_format((float)$discount  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                        @else
                                                            @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$discount * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
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
                                                                    {{ number_format((float)$total  * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }} @include('frontend.includes.SHOW_CURRENCY_SYMBOL')
                                                                @else
                                                                    @include('frontend.includes.SHOW_CURRENCY_SYMBOL') {{ number_format((float)$total * $CHANGE_CURRENCY_RATE, env('FORMAT_NUMBER'), '.', '') }}
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

                <br>
                <div class="col-md-4">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('file.Order Information')}}</h5>
                                    <hr>
                                    <p class="mt-2"><strong>@lang('file.Order Date') :</strong> {{date('d M, Y',strtotime($created_at))}}</p>
                                    <p class="mt-2"><strong>{{__('file.Order Status')}} : </strong>
                                        @php
                                            $btn_color = '';
                                            if ($order_status=='order_placed') {
                                                $badge_color = 'badge badge-primary';
                                            }else if($order_status=='pending'){
                                                $badge_color = 'badge badge-danger';
                                            }else if($order_status=='order_confirmed'){
                                                $badge_color = 'badge badge-secondary';
                                            }else if($order_status=='delivery_scheduled'){
                                                $badge_color = 'badge badge-warning';
                                            }else if($order_status=='delivery_successful'){
                                                $badge_color = 'badge badge-info';
                                            }else if($order_status=='payment_successful'){
                                                $badge_color = 'badge badge-light';
                                            }else if($order_status=='order_completed'){
                                                $badge_color = 'badge badge-success';
                                            }
                                        @endphp
                                        <span class="{{$badge_color}} p-1"> {{ucwords(str_replace('_', ' ',$order_status))}} </span>
                                    </p>
                                    <p class="mt-2"><strong>{{__('file.Transaction Id')}} : </strong>{{$payment_id ?? null}}</p>
                                    <p class="mt-2"><strong>{{__('file.Shipping Method')}} : </strong>{{ucwords(str_replace('_', ' ',$shipping_method))}}</p>
                                    <p class="mt-2"><strong>{{__('file.Payment Method')}} : </strong> {{ucwords(str_replace('_', ' ',$payment_method))}}</p>
                                    <p class="mt-2"><strong>{{__('file.Currency')}} : </strong> {{env('DEFAULT_CURRENCY_CODE')}}</p>
                                    <p class="mt-2"><strong>{{__('file.Currency Rate')}} : </strong> {{number_format((float)$currency_rate, env('FORMAT_NUMBER'), '.', '')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('file.Customer Information')}}</h5>
                                    <hr>
                                    <p class="mt-2"><strong>@lang('file.Name') :</strong> {{$billing_first_name.' '.$billing_last_name ?? null}}</p>
                                    <p class="mt-2"><strong>@lang('file.Email') :</strong>{{$billing_email ?? null}}</p>
                                    <p class="mt-2"><strong>@lang('file.Phone') :</strong> {{$billing_phone ?? null}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5>{{__('file.Billing Address')}}</h5>
                                    <hr>
                                    <p>{{$billing_address_1 ?? null}}</p>
                                    <p>{{$billing_city ?? null}}</p>
                                    <p>{{$billing_state ?? null}}</p>
                                    <p>{{$billing_zip_code ?? null}}</p>
                                    <p>{{$billing_country ?? null}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    @if($shipping_details)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>{{__('file.Shipping Address')}}</h5>
                                        <hr>
                                        <p>{{$shipping_details['shipping_address_1'] ?? null}}</p>
                                        <p>{{$shipping_details['shipping_city'] ?? null}}</p>
                                        <p>{{$shipping_details['shipping_state'] ?? null}}</p>
                                        <p>{{$shipping_details['shipping_zip_code'] ?? null}}</p>
                                        <p>{{$shipping_details['shipping_country'] ?? null}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
