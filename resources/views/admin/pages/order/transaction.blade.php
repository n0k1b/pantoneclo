@php
    if (Session::has('currency_rate')){
        $CHANGE_CURRENCY_RATE = Session::get('currency_rate');
    }else{
        $CHANGE_CURRENCY_RATE = 1;
        Session::put('currency_rate', $CHANGE_CURRENCY_RATE);
    }
@endphp

@extends('admin.main')
@section('title','Admin | Transactions')
@section('admin_content')

<section>
    <div class="container-fluid"><span id="alert_message"></span></div>
    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">{{__('Transactions')}}</h4>
        <div id="success_alert" role="alert"></div>
        <br>

    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
                    <th scope="col">{{trans('file.Reference No')}}</th>
        		    <th scope="col">{{trans('file.Transaction Id')}}</th>
                    <th scope="col">{{trans('file.Payment Method')}}</th>
                    <th scope="col">{{trans('file.Status')}}</th>
                    <th scope="col">{{trans('file.Created')}}</th>
                    <th scope="col">{{trans('file.Action')}}</th>
        	   </tr>
    	  	</thead>
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
                    initComplete: function () {
                        this.api().columns([1]).every(function () {
                            var column = this;
                            var select = $('<select><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                                $('select').selectpicker('refresh');
                            });
                        });
                    },
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
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'reference_no',
                            name: 'reference_no',
                        },
                        {
                            data: 'payment_id',
                            name: 'payment_id',
                        },
                        {
                            data: 'payment_method',
                            name: 'payment_method',
                        },
                        {
                            data: 'order_status',
                            name: 'order_status',
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                        },
                        {
                            data: 'action',
                            name: 'action',
                        }
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
                            // 'targets': [0, 3],
                            'targets': [0],
                        },
                        {
                            'render': function (data, type, row, meta) {
                                if (type === 'display') {
                                    data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                                }

                                return data;
                            },
                            'checkboxes': {
                                'selectRow': true,
                                'selectAllRender': '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>'
                            },
                            'targets': [0]
                        }
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
                        },
                        {
                            extend: 'csv',
                            text: '<i title="export to csv" class="fa fa-file-text-o"></i>',
                            exportOptions: {
                                columns: ':visible:Not(.not-exported)',
                                rows: ':visible'
                            },
                        },
                        {
                            extend: 'print',
                            text: '<i title="print" class="fa fa-print"></i>',
                            exportOptions: {
                                columns: ':visible:Not(.not-exported)',
                                rows: ':visible'
                            },
                        },
                        {
                            extend: 'colvis',
                            text: '<i title="column visibility" class="fa fa-eye"></i>',
                            columns: ':gt(0)'
                        },
                    ],
                });
                new $.fn.dataTable.FixedHeader(table);
            });
        })(jQuery);

        const deleteURL = "{{route('admin.order.delete')}}";
    </script>

    <!-- Common Action For All CRUD-->
    @include('admin.includes.common_action',['delete'=>true])
@endpush
