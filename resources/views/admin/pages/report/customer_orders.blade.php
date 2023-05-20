@extends('admin.main')
@section('title','Report | Customer Orders')
@section('admin_content')


<section>
    <h4 class="mb-3 ml-4 text-bold">@lang('file.Customer Order Report')</h4>
    <div class="table-responsive ml-3">
        <div class="row">
            <div class="col-md-8">
                <table id="customerOrderTable" class="table">
                    <thead>
                        <tr>
                            <th class="wd-15p">@lang('file.Date')</th>
                            <th class="wd-15p">@lang('file.Customer Name')</th>
                            <th class="wd-15p">@lang('file.Customer Email')</th>
                            <th class="wd-15p text-center">@lang('file.Orders')</th>
                            <th class="wd-15p text-center">@lang('file.Status')</th>
                            <th class="wd-15p text-center">@lang('file.Total')</th>
                        </tr>
                    </thead>
                    <tbody id="reportResult">
                        {{-- @foreach ($customer_order_reports as $item)
                            @if ($item->orders->isNotEmpty())
                                <tr>
                                    <td>
                                        {{date('d M, Y',strtotime($item->orders[0]->date))}} - {{date('d M, Y',strtotime($item->orders[count($item->orders)-1]->date))}}
                                    </td>
                                    <td> @if($item->first_name ==NULL && $item->last_name ==NULL) (@lang('file.Unknown'))  @else {{$item->first_name}} {{$item->last_name}}@endif</td>
                                    <td>{{$item->email}}</td>
                                    <td class="text-center">{{$item->orders->count()}}</td>
                                    <td class="text-center">
                                        @php $total_products = 0; @endphp
                                        @foreach ($item->orders as $value)
                                            @php $total_products += $value->orderDetails->count() @endphp
                                        @endforeach
                                        {{$total_products}}
                                    </td>
                                    <td class="text-center">
                                        @php $total_amount = 0; @endphp
                                        @foreach ($item->orders as $item)
                                            @php   $total_amount += $item->total; @endphp
                                        @endforeach

                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            {{ number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                        @else
                                            {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$total_amount, env('FORMAT_NUMBER'), '.', '') }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach --}}
                        @foreach ($customer_order_reports as $item)
                            {{-- @if ($item->orders->isNotEmpty()) --}}
                                <tr>
                                    <td>{{date('d M, Y',strtotime($item->date))}}</td>
                                    <td> {{$item->customer_name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td class="text-center">{{$item->total_products}}</td>
                                    <td class="text-center">
                                        {{$item->status}}
                                    </td>
                                    <td class="text-center">
                                        @if(env('CURRENCY_FORMAT')=='suffix')
                                            {{ number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                        @else
                                            {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$item->total_amount, env('FORMAT_NUMBER'), '.', '') }}
                                        @endif
                                    </td>
                                </tr>
                            {{-- @endif --}}
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align:right">{{trans('file.Total')}} :</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('file.Filter')</h4>
                        <form action="{{route('admin.reports.customer_orders')}}" id="filterForm" method="get">

                            @if (isset($report_type))
                                <input type="hidden" name="report_type" value="{{$report_type}}">
                            @endif

                            <div class="form-group mt-4">
                                <h5 class="mt-2 card-subtitle mb-2 text-muted">@lang('file.Report Type')</h5>
                                <select name="report_type" class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" onchange="location = this.value;">
                                    <option value="{{route('admin.reports.customer_orders')}}">@lang('file.Customer Order Report')</option>
                                    <option value="{{route('admin.reports.coupon')}}">@lang('file.Coupon Report')</option>
                                    <option value="{{route('admin.reports.product_stock_report')}}">@lang('file.Product Stock Report')</option>
                                    <option value="{{route('admin.reports.product_view_report')}}">@lang('file.Product View Report')</option>
                                    <option value="{{route('admin.reports.sales_report')}}">@lang('file.Sales Report')</option>
                                    <option value="{{route('admin.reports.search_report')}}">@lang('file.Search Report')</option>
                                    <option value="{{route('admin.reports.shipping_report')}}">@lang('file.Shpping Report')</option>
                                    <option value="{{route('admin.reports.tax_report')}}">@lang('file.Tax Report')</option>
                                    <option value="{{route('admin.reports.product_purchase_report')}}">@lang('file.Product Purchase Report')</option>
                                </select>
                            </div>



                            <div class="form-group mt-4">
                                <label for="exampleInputEmail1">@lang('file.Start Date')</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  name="start_date" >
                            </div>

                            <div class="form-group mt-4">
                                <label for="exampleInputEmail1">@lang('file.End Date')</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"  name="end_date" >
                            </div>

                            <div class="form-group mt-4">
                                <h5 class="mt-4 card-subtitle mb-2 text-muted">@lang('file.Order Status')</h5>
                                <select name="report_type" class="form-control report_type">
                                    <option value="">@lang('file.-- Select --')</option>
                                    <option value="completed">@lang('file.Completed')</option>
                                    <option value="pending">@lang('file.Pending')</option>
                                    <option value="processing">@lang('file.Processing')</option>
                                    <option value="cancaled">@lang('file.Cancaled')</option>
                                    <option value="refund">@lang('file.Refund')</option>
                                    <option value="deliver">@lang('file.Deliver')</option>
                                    <option value="pending_payment">@lang('file.Pending Payment')</option>
                                </select>
                            </div>

                            {{-- <div class="form-group mt-4">
                                <h5 class="">@lang('file.Customer Name')</h5>
                                <input type="text" name="customer_name" id="customer_name" class="form-control">
                            </div> --}}

                            {{-- <div class="form-group mt-4">
                                <h5 class="">@lang('file.Customer Email')</h5>
                                <input type="text" name="customer_email" id="customer_email" class="form-control">
                            </div> --}}

                            <button type="submit" class="mt-4 btn btn-success">@lang('file.Filter')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script>
    $(function(){
      'use strict';

        $(document).ready(function () {
            $('#customerOrderTable').DataTable({
                drawCallback: function () {
                    var api = this.api();
                    datatable_sum(api, 5);
                },
                @include('admin.includes.common_js.pdf_excel')
            });
            @include('admin.includes.common_js.datatable_sum')
        });


        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

        $("#filterForm").on("submit",function(e){
            e.preventDefault();
            $.ajax({
                url: "{{route('admin.reports.customer_orders')}}",
                method: "GET",
                data: $('#filterForm').serialize(),
                success: function (data) {
                    console.log(data);
                    $('#reportResult').html(data);
                }
            });
        });

    });
  </script>
@endsection
