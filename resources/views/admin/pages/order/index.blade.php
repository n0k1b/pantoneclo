@php
    if (Session::has('currency_rate')){
        $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
    }else{
        $CHANGE_CURRENCY_RATE = 1;
        Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
    }
@endphp



@extends('admin.main')
@section('title','Admin | Order')
@section('admin_content')


<section>
    <div class="container-fluid"><span id="alert_message"></span></div>
    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">{{__('Orders')}}</h4>
        <div id="success_alert" role="alert"></div>
        <br>
    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
                    <th scope="col">{{trans('file.Reference No')}}</th>
                    <th scope="col">{{trans('file.Status')}}</th>
                    <th scope="col">{{trans('file.Delivery Date')}}</th>
                    <th scope="col">{{trans('file.Delivery Time')}}</th>
        		    <th scope="col">{{trans('file.Customer Name')}}</th>
                    <th scope="col">{{trans('file.Customer Email')}}</th>
        		    <th scope="col">{{trans('file.Total')}}</th>
        		    <th scope="col">{{trans('file.Created')}}</th>
        		    <th scope="col">{{trans('file.Action')}}</th>
        	   </tr>
    	  	</thead>
              <tfoot>
                <tr>
                    <th>{{trans('file.Total')}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
    	</table>
    </div>
</section>
@endsection

@push('scripts')



    <script type="text/javascript">
        (function ($) {
            "use strict";

                $(document).ready(function () {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    let table = $('#dataListTable').DataTable({
                        responsive: true,
                        fixedHeader: {
                            header: true,
                            footer: true
                        },
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.order.index') }}",
                        },
                        columns: [
                            {
                                data: 'reference_no',
                                name: 'reference_no',
                            },
                            {
                                data: 'order_status',
                                name: 'order_status',
                            },
                            {
                                data: 'delivery_date',
                                name: 'delivery_date',
                            },
                            {
                                data: 'delivery_time',
                                name: 'delivery_time',
                            },
                            {
                                data: 'customer_name',
                                name: 'customer_name',
                            },
                            {
                                data: 'customer_email',
                                name: 'customer_email',
                            },
                            {
                                data: 'total',
                                name: 'total',
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                            },
                            {
                                data: 'action',
                                name: 'action',
                            },
                        ],

                        "order": [],
                        'language': {
                            'lengthMenu': '_MENU_ {{__("records per page")}}',
                            "info": '{{trans("file.Showing")}} _START_ - _END_ (_TOTAL_)',
                            "search": '{{trans("file.Search")}}',
                            'paginate': {
                                'previous': '{{trans("file.Previous")}}',
                                'next': '{{trans("file.Next")}}'
                            }
                        },
                        'columnDefs': [
                            {
                                "orderable": false,
                                'targets': [0],
                            },
                        ],
                        'select': {style: 'multi', selector: 'td:first-child'},
                        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
                        dom: '<"row"lfB>rtip',
                        buttons: [
                            {
                                extend: 'pdf',
                                text: '<i title="export to pdf" class="fa fa-file-pdf-o"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                                footer:true
                            },
                            {
                                extend: 'csv',
                                text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                                footer:true
                            },
                            {
                                extend: 'print',
                                text: '<i title="print" class="fa fa-print"></i>',
                                exportOptions: {
                                    columns: ':visible:Not(.not-exported)',
                                    rows: ':visible'
                                },
                                footer:true
                            },
                            {
                                extend: 'colvis',
                                text: '<i title="column visibility" class="fa fa-eye"></i>',
                                columns: ':gt(0)'
                            },
                        ],
                        drawCallback: function () {
                            var api = this.api();
                            // for (let i = 7; i <8; i++) {
                            //     datatable_sum(api, i);
                            // }
                            datatable_sum(api, 6);
                        }
                    });
                    new $.fn.dataTable.FixedHeader(table);
                });

                function datatable_sum(dt_selector, columnNo) {
                    var rows = dt_selector.rows().indexes();
                    var rowsdataCol = dt_selector.cells( rows, columnNo, { page: 'current' } ).data();

                    let text, data, total = 0, resultOfFooter;
                    for (let i = 0; i < rowsdataCol.length; i++) {
                        text = rowsdataCol[i];
                        data = text.replace("$", "");
                        total  += parseFloat(data);
                    }
                    var resultOfSum  = total.toFixed(2);

                    var currencyFormat        = {!! json_encode(env('CURRENCY_FORMAT')) !!};
                    var defaultCurrencySymbol = {!! json_encode(env('DEFAULT_CURRENCY_SYMBOL')) !!};
                    if (currencyFormat=='prefix') {
                        resultOfFooter = defaultCurrencySymbol + resultOfSum;
                    }else{
                        resultOfFooter = resultOfSum + defaultCurrencySymbol;
                    }
                    $(dt_selector.column(columnNo).footer()).html(resultOfFooter);
                }

                $(document).on('click', '.date_field', function () {
                    $(".update_btn").attr("hidden",true);
                    $(this).siblings('.update_btn').removeAttr('hidden');
                });

                $(document).on('click', '.update_btn', function () {
                    var rowId = $(this).data("id");
                    var date  = $(this).siblings('.date_field').val();
                    console.log(date);
                    $.ajax({
                        url: "{{route('admin.order.order_date')}}",
                        type: "POST",
                        data: {id:rowId,date:date},
                        success: function (data) {
                            if(data.success){
                                $(".update_btn").attr("hidden",true);
                                $('#success_alert').fadeIn("slow"); //Check in top in this blade
                                $('#success_alert').addClass('alert alert-success').html(data.success);
                                setTimeout(function() {
                                    $('#success_alert').fadeOut("slow");
                                }, 3000);
                            }
                        }
                    })
                });

                $(document).on('click', '.time_field', function () {
                    $(".update_time_btn").attr("hidden",true);
                    $(this).siblings('.update_time_btn').removeAttr('hidden');;
                });

                $(document).on('click', '.update_time_btn', function () {
                    var rowId = $(this).data("id");
                    var time  = $(this).siblings('.time_field').val();
                    $.ajax({
                        url: "{{route('admin.order.delivery_time')}}",
                        type: "POST",
                        data: {id:rowId,time:time},
                        success: function (data) {
                            if(data.success){
                                $(".update_time_btn").attr("hidden",true);
                                $('#success_alert').fadeIn("slow"); //Check in top in this blade
                                $('#success_alert').addClass('alert alert-success').html(data.success);
                                setTimeout(function() {
                                    $('#success_alert').fadeOut("slow");
                                }, 3000);
                            }
                        }
                    })
                });

                $('.date_field').datepicker().on('changeDate', function (ev) {
                    $('.date_field').Close();
                });
            })(jQuery);

            const deleteURL = "{{route('admin.order.delete')}}";
    </script>

    <!-- Common Action For All CRUD-->
    @include('admin.includes.common_action',['delete'=>true])
@endpush
