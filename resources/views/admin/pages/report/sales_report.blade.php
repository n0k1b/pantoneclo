@extends('admin.main')
@section('title','Report | Sales Report')
@section('admin_content')


<section>
    <h4 class="mb-3 ml-4 text-bold"> @lang('file.Sales Report') </h4>
    <div class="table-responsive ml-3">
        <div class="row">
            <div class="col-md-8">
                <table id="datatable" class="table text-center">
                    <thead>
                        <tr>
                            <th class="wd-15p text-center">@lang('file.Date')</th>
                            <th class="wd-15p text-center">@lang('file.Order No')</th>
                            <th class="wd-15p text-center">@lang('file.Products')</th>
                            <th class="wd-15p text-center">@lang('file.Subtotal')</th>
                            <th class="wd-15p text-center">@lang('file.Shipping')</th>
                            <th class="wd-15p text-center">@lang('file.Total')</th>
                            <th class="wd-15p text-center">@lang('file.Status')</th>
                        </tr>
                    </thead>
                    <tbody id="reportResult">
                        @foreach ($sales_report as $item)
                            <tr>
                                <td>
                                    {{date('d M, Y',strtotime($item->date))}}
                                </td>
                                <td>{{$item->id}}</td>
                                <td>{{$item->orderDetails->count()}}</td>
                                <td>
                                    @php $sum_subtotal = 0; @endphp
                                    @forelse ($item->orderDetails as $value)
                                        @php   $sum_subtotal += $value->price; @endphp
                                    @empty
                                    @endforelse

                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                        {{ number_format((float)$sum_subtotal, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                    @else
                                        {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$sum_subtotal, env('FORMAT_NUMBER'), '.', '') }}
                                    @endif
                                </td>

                                <td>
                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                        {{ number_format((float)$item->shipping_cost, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                    @else
                                        {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$item->shipping_cost, env('FORMAT_NUMBER'), '.', '') }}
                                    @endif
                                </td>
                                <td>
                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                        {{ number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                    @else
                                        {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '') }}
                                    @endif
                                </td>
                                <td>{{$item->order_status}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{trans('file.Total')}} :</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('file.Filter')</h4>
                        <form action="{{route('admin.reports.sales_report')}}" id="filterForm" method="get">

                            <div class="form-group mt-4">
                                <h5 class="mt-2 card-subtitle mb-2 text-muted">@lang('file.Report Type')</h5>
                                <select name="report_type" class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" onchange="location = this.value;">
                                    <option value="{{route('admin.reports.sales_report')}}">@lang('file.Sales Report')</option>
                                    <option value="{{route('admin.reports.coupon')}}">@lang('file.Coupon Report')</option>
                                    <option value="{{route('admin.reports.product_stock_report')}}">@lang('file.Product Stock Report')</option>
                                    <option value="{{route('admin.reports.product_view_report')}}">@lang('file.Product View Report')</option>
                                    <option value="{{route('admin.reports.customer_orders')}}">@lang('file.Customer Order Report')</option>
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

      $('#datatable').DataTable({
            drawCallback: function () {
                let api = this.api();
                for (let i = 3; i < 7; i++) {
                    datatable_sum(api, i);
                }
                // var pageTotalColumn3 = api.column(3,{page:'current'}).data().sum();
                // $(api.column(3).footer()).html('$ ' + pageTotalColumn3);
            },
            @include('admin.includes.common_js.pdf_excel')
        });
        @include('admin.includes.common_js.datatable_sum')

      // Select2
      $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

    $("#filterForm").on("submit",function(e){
        e.preventDefault();
        $.ajax({
            url: "{{route('admin.reports.sales_report')}}",
            method: "GET",
            data: $('#filterForm').serialize(),
            success: function (data) {
                $('#reportResult').html(data);
            }
        });
    });

    });
  </script>
@endsection
