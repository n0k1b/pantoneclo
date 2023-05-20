@extends('admin.main')
@section('title','Report | Shipping')
@section('admin_content')


<section>
    <h4 class="mb-3 ml-4 text-bold"> @lang('file.Shipping Report') </h4>
    <div class="table-responsive ml-3">
        <div class="row">
            <div class="col-md-8">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th class="wd-15p">@lang('file.Date')</th>
                            <th class="wd-15p">@lang('file.Shipping Method')</th>
                            <th class="wd-15p">@lang('file.Orders')</th>
                            <th class="wd-15p">@lang('file.Total')</th>
                        </tr>
                    </thead>
                    <tbody id="reportResult">
                        @forelse ($shipping_reports as $item)
                            <tr>
                                <td>{{date('d M, Y',strtotime($item->date))}}</td>
                                {{-- <td>{{$item->shipping_method}}</td> --}}
                                {{-- <td>{{$item->orderDetails->count()}}</td>
                                <td>{{$item->total_order}}</td>
                                <td>
                                    @if(env('CURRENCY_FORMAT')=='suffix')
                                        {{ number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '') }} {{env('DEFAULT_CURRENCY_SYMBOL')}}
                                    @else
                                        {{env('DEFAULT_CURRENCY_SYMBOL')}} {{ number_format((float)$item->total, env('FORMAT_NUMBER'), '.', '') }}
                                    @endif
                                </td> --}}
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
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
                        <form id="filterForm" method="get">

                            <div class="form-group mt-4">
                                <h5 class="mt-2 card-subtitle mb-2 text-muted">@lang('file.Report Type')</h5>
                                <select name="report_type" class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" onchange="location = this.value;">
                                    <option value="{{route('admin.reports.shipping_report')}}">@lang('file.Shpping Report')</option>
                                    <option value="{{route('admin.reports.coupon')}}">@lang('file.Coupon Report')</option>
                                    <option value="{{route('admin.reports.customer_orders')}}">@lang('file.Customer Order Report')</option>
                                    <option value="{{route('admin.reports.product_stock_report')}}">@lang('file.Product Stock Report')</option>
                                    <option value="{{route('admin.reports.product_view_report')}}">@lang('file.Product View Report')</option>
                                    <option value="{{route('admin.reports.sales_report')}}">@lang('file.Sales Report')</option>
                                    <option value="{{route('admin.reports.search_report')}}">@lang('file.Search Report')</option>
                                    <option value="{{route('admin.reports.tax_report')}}">@lang('file.Tax Report')</option>
                                    <option value="{{route('admin.reports.product_purchase_report')}}">@lang('file.Product Purchase Report')</option>
                                </select>
                            </div>

                            <div class="form-group mt-4">
                                <label for="exampleInputEmail1">@lang('file.Start Date')</label>
                                <input type="date" class="form-control datepicker" id="exampleInputEmail1" aria-describedby="emailHelp"  name="start_date">
                            </div>

                            <div class="form-group mt-4">
                                <label for="exampleInputEmail1">@lang('file.End Date')</label>
                                <input type="date" class="form-control datepicker" id="exampleInputEmail1" aria-describedby="emailHelp"  name="end_date">
                            </div>


                            <div class="form-group mt-4">
                                <h5 class="mt-4 card-subtitle mb-2 text-muted">@lang('file.Shipping Method')</h5>
                                <select name="report_type" class="form-control">
                                    <option value="">@lang('file.-- Select --')</option>
                                    <option value="Free Shipping">@lang('file.Free Shipping')</option>
                                    <option value="Local Pickup">@lang('file.Local Pickup')</option>
                                    <option value="Flat Rate">@lang('file.Flat Rate')</option>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/js.min.js"></script>
<script>
    $(function(){
      'use strict';

        $(document).ready(function () {
            $('#datatable').DataTable({
                drawCallback: function () {
                    var api = this.api();
                    datatable_sum(api, 3);
                },
                @include('admin.includes.common_js.pdf_excel')
            });
            @include('admin.includes.common_js.datatable_sum')
        });


        $("#filterForm").on("submit",function(e){
            e.preventDefault();
            $.ajax({
                url: "{{route('admin.reports.shipping_report')}}",
                method: "GET",
                data: $('#filterForm').serialize(),
                success: function (data) {
                    $('#datatable').html(data);
                    console.log(data);
                }
            });
        });

    });
  </script>
@endsection
