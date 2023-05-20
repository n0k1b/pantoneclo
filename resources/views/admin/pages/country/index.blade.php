@extends('admin.main')
@section('title','Admin | Country')
@section('admin_content')

<section>
    <div class="container-fluid mb-3">
        <h4 class="font-weight-bold mt-3">@lang('file.Country')</h4>
        <div id="alert_message" role="alert"></div>
        <br>

        @if (auth()->user()->can('country-store'))
            <button type="button" class="btn btn-info" name="formModal" data-toggle="modal" data-target="#formModal">
                <i class="fa fa-plus"></i> @lang('file.Add Country')
            </button>
        @endif
        @if (auth()->user()->can('country-action'))
            <button type="button" class="btn btn-danger" name="bulk_delete" id="bulkDelete">
                <i class="fa fa-minus-circle"></i> @lang('file.Bulk Action')
            </button>
        @endif
    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
        		    <th scope="col">{{trans('file.Country Name')}}</th>
        		    <th scope="col">{{trans('file.Country Code')}}</th>
        		    <th scope="col">{{trans('file.Action')}}</th>
        	   </tr>
    	  	</thead>
    	</table>
    </div>
</section>

    @include('admin.pages.country.create_modal')
    @include('admin.pages.country.edit_modal')
    @include('admin.includes.confirm_delete_modal')

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
                        url: "{{ route('admin.country.datatable') }}",
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'country_name',
                            name: 'country_name',
                        },
                        {
                            data: 'country_code',
                            name: 'country_code',
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


            //----------Insert Data----------------------
            @include('admin.includes.common_js.submit_button_click_js')

            @include('admin.includes.common_js.submit_form_js',['route_name'=>'admin.country.store'])


            //---------- Show data by id-------------
            $(document).on('click', '.edit', function () {
                var rowId = $(this).data("id");
                $('#alert_message').html('');

                $.ajax({
                    url: "{{route('admin.country.edit')}}",
                    type: "GET",
                    data: {country_id:rowId},
                    success: function (data) {
                        console.log(data);
                        $('#countryId').val(data.id);
                        $('#countryName').val(data.country_name);
                        $('#countryCode').val(data.country_code);
                        $('#editFormModal').modal('show');
                    }
                })
            });


            //---------- Update -------------
            @include('admin.includes.common_js.update_button_click_js')

            @include('admin.includes.common_js.update_form_js',['route_name'=>'admin.country.update'])


            //---------- Delete ------------
            @include('admin.includes.common_js.delete_js',['route_name'=>'admin.country.destroy'])

            // //---------- Bulk Action Delete ------------
            @include('admin.includes.common_js.bulk_delete_js',['route_name_bulk_delete'=>'admin.country.bulk_action_delete'])

        })(jQuery);
    </script>
@endpush
