@extends('admin.main')
@section('title','Report | Product View')
@section('admin_content')


<section>
    <h4 class="mb-3 ml-4 text-bold"> @lang('file.Products View Report') </h4>
    <div class="table-responsive ml-3">
        <div class="row">
            <div class="col-md-8">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th class="wd-15p">@lang('file.Product')</th>
                            <th class="wd-15p">@lang('file.View')</th>
                        </tr>
                    </thead>
                    <tbody id="reportResult">
                        @foreach ($product_name_and_views as $item)
                            <tr>
                                <td>{{ ucfirst(str_replace("-"," ",$item['product_name']))}}</td>
                                <td>{{$item['pageViews']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('file.Filter')</h4>
                        <form action="{{route('admin.reports.product_view_report')}}" id="filterForm" method="get">

                            @if (isset($report_type))
                                <input type="hidden" name="report_type" value="{{$report_type}}">
                            @endif

                            <div class="form-group mt-4">
                                <h5 class="mt-2 card-subtitle mb-2 text-muted">@lang('file.Report Type')</h5>
                                <select name="report_type" class="form-control selectpicker" data-live-search="true" data-live-search-style="begins" onchange="location = this.value;">
                                    <option value="{{route('admin.reports.product_view_report')}}">@lang('file.Product View Report')</option>
                                    <option value="{{route('admin.reports.coupon')}}">@lang('file.Coupon Report')</option>
                                    <option value="{{route('admin.reports.customer_orders')}}">@lang('file.Customer Order Report')</option>
                                    <option value="{{route('admin.reports.product_stock_report')}}">@lang('file.Product Stock Report')</option>
                                    <option value="{{route('admin.reports.sales_report')}}">@lang('file.Sales Report')</option>
                                    <option value="{{route('admin.reports.search_report')}}">@lang('file.Search Report')</option>
                                    <option value="{{route('admin.reports.shipping_report')}}">@lang('file.Shpping Report')</option>
                                    <option value="{{route('admin.reports.tax_report')}}">@lang('file.Tax Report')</option>
                                    <option value="{{route('admin.reports.product_purchase_report')}}">@lang('file.Product Purchase Report')</option>
                                </select>
                            </div>

                            <div class="form-group mt-4">
                                <label for="exampleInputEmail1">@lang('file.SKU')</label>
                                <input type="text" name="sku" class="form-control">
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
            @include('admin.includes.common_js.pdf_excel')
        });

      // Select2
      $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

    $("#filterForm").on("submit",function(e){
        e.preventDefault();
        $.ajax({
            url: "{{route('admin.reports.product_view_report')}}",
            method: "GET",
            data: $('#filterForm').serialize(),
            success: function (data) {
                $('#reportResult').html(data);
                console.log(data);
            }
        });
    });

    });
  </script>
@endsection
