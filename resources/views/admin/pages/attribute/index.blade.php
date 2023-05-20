@extends('admin.main')
@section('title','Admin | Attributes')
@section('admin_content')
<section>

    <div class="container-fluid"><span id="general_result"></span></div>
    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">@lang('file.Attributes')</h4>
        <div id="success_alert" role="alert"></div>
        <br>

        @if (auth()->user()->can('attribute-store'))
            <a href="{{route('admin.attribute.create')}}" class="btn btn-info">
                <i class="fa fa-plus"></i> @lang('file.Create Attribute')
            </a>
        @endif
        @if (auth()->user()->can('attribute-action'))
            <button type="button" class="btn btn-danger" id="bulk_action">
                <i class="fa fa-minus-circle"></i> {{trans('file.Bulk Action')}}
            </button>
        @endif
    </div>

    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
        		    <th scope="col">@lang('file.Attribute Name')</th>
        		    <th scope="col">@lang('file.Attribute Set')</th>
        		     <th scope="col">@lang('file.Filterable')</th>
        		    <th scope="col">@lang('file.Status')</th>
        		   <th scope="col">@lang('file.Action')</th>
        	   </tr>
    	  	</thead>
    	</table>
    </div>
</section>

@include('admin.includes.confirm_modal')

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
                        url: "{{ route('admin.attribute.datatable') }}",
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'attribute_name',
                            name: 'attribute_name',
                        },
                        {
                            data: 'attribute_set_name',
                            name: 'attribute_set_name',
                        },
                        {
                            data: 'is_filterable',
                            name: 'is_filterable',
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            render:function (data) {
                                if (data == 1) {
                                    return "<span class='p-2 badge badge-success'>Active</span>";
                                }else{
                                    return "<span class='p-2 badge badge-danger'>Inactive</span>";
                                }
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
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
                            //'targets': [0, 3],
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


            //---------- Active ------------
            @include('admin.includes.common_js.active_js',['route_name'=>'admin.attribute.active'])
            //---------- Inactive ------------
            @include('admin.includes.common_js.inactive_js',['route_name'=>'admin.attribute.inactive'])
            //---------- Delete ------------
            @include('admin.includes.common_js.delete_js',['route_name'=>'admin.attribute.destroy'])
            //---------- Bulk Action ------------
            @include('admin.includes.common_js.bulk_action_js',['route_name_bulk_active_inactive'=>'admin.attribute.bulk_action'])

            //Bulk Action
            // $("#bulk_action").on("click",function(){
            //     var idsArray = [];
            //     let table = $('#dataListTable').DataTable();
            //     idsArray = table.rows({selected: true}).ids().toArray();

            //     if(idsArray.length === 0){
            //         alert("Please Select at least one checkbox.");
            //     }else{
            //         $('#bulkConfirmModal').modal('show');
            //         let action_type;

            //         $("#active").on("click",function(){
            //             console.log(idsArray);
            //             action_type = "active";
            //             $.ajax({
            //                 url: "{{route('admin.attribute.bulk_action')}}",
            //                 method: "GET",
            //                 data: {idsArray:idsArray,action_type:action_type},
            //                 success: function (data) {
            //                     if(data.success){
            //                         $('#bulkConfirmModal').modal('hide');
            //                         table.rows('.selected').deselect();
            //                         $('#dataListTable').DataTable().ajax.reload();
            //                         $('#alert_message').fadeIn("slow"); //Check in top in this blade
            //                         $('#alert_message').addClass('alert alert-success').html(data.success);
            //                         setTimeout(function() {
            //                             $('#alert_message').fadeOut("slow");
            //                         }, 3000);
            //                     }
            //                 }
            //             });
            //         });
            //         $("#inactive").on("click",function(){
            //             action_type = "inactive";
            //             console.log(idsArray);
            //             $.ajax({
            //                 url: "{{route('admin.attribute.bulk_action')}}",
            //                 method: "GET",
            //                 data: {idsArray:idsArray,action_type:action_type},
            //                 success: function (data) {
            //                     if(data.success){
            //                         $('#bulkConfirmModal').modal('hide');
            //                         table.rows('.selected').deselect();
            //                         $('#dataListTable').DataTable().ajax.reload();
            //                         $('#alert_message').fadeIn("slow"); //Check in top in this blade
            //                         $('#alert_message').addClass('alert alert-success').html(data.success);
            //                         setTimeout(function() {
            //                             $('#alert_message').fadeOut("slow");
            //                         }, 3000);
            //                     }
            //                 }
            //             });
            //         });
            //     }
            // });


        })(jQuery);
    </script>
@endpush
