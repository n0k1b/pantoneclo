@extends('admin.main')
@section('title','Admin | Pages')

@section('admin_content')


<section>
    <div class="container-fluid mb-3">

        <h4 class="font-weight-bold mt-3">{{__('file.Pages')}}</h4>
        <div id="alert_message" role="alert"></div>
        <br>

        @if (auth()->user()->can('page-store'))
            <button type="button" class="btn btn-info" name="formModal" data-toggle="modal" data-target="#formModal">
                <i class="fa fa-plus"></i> {{__('file.Add Page')}}
            </button>
        @endif

        @if (auth()->user()->can('page-action'))
            <button type="button" class="btn btn-danger" name="bulk_delete" id="bulk_action">
                <i class="fa fa-minus-circle"></i> {{trans('file.Bulk Action')}}
            </button>
        @endif

    </div>
    <div class="table-responsive">
    	<table id="dataListTable" class="table ">
    	    <thead>
        	   <tr>
        		    <th class="not-exported"></th>
        		    <th scope="col">{{trans('file.Page Name')}}</th>
        		    <th scope="col">{{trans('file.Status')}}</th>
        		    <th scope="col">{{__('file.URL Link')}}</th>
        		    <th scope="col">{{trans('file.action')}}</th>
        	   </tr>
    	  	</thead>
    	</table>
    </div>
</section>


@include('admin.pages.page.create_modal')
@include('admin.pages.page.edit_modal')
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
                        url: "{{ route('admin.page.datatable') }}",
                    },
                    columns: [
                        {
                            data: null,
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'page_name',
                            name: 'page_name',
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
                            data: 'copy_url',
                            name: 'copy_url',
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

            //----------Insert Data----------------------
            @include('admin.includes.common_js.submit_button_click_js')
            @include('admin.includes.common_js.submit_form_js',['route_name'=>'admin.page.store'])

            //---------- Edit -------------
            $(document).on('click', '.edit', function () {
                var rowId = $(this).data("id");
                $('#alert_message').html('');

                $.ajax({
                    url: "{{route('admin.page.edit')}}",
                    type: "GET",
                    data: {page_id:rowId},
                    success: function (data) {
                        console.log(data);
                        $('#page_id').val(data.page.id);
                        $('#page_translation_id').val(data.page_translation.id);
                        $('#page_name_edit').val(data.page_translation.page_name);
                        $('#page_url').attr('href',"{{url('/page')}}"+'/'+data.page.slug);

                        $('#meta_title_edit').val(data.page_translation.meta_title);
                        $('#meta_description_edit').val(data.page_translation.meta_description);
                        $('#meta_url_edit').val(data.page_translation.meta_url);
                        $('#meta_type_edit').val(data.page_translation.meta_type);


                        tinymce.get('body_edit').setContent(data.page_translation_body);
                        if (data.page.is_active === 1) {
                                $('#is_active_edit').prop('checked', true);
                        } else {
                            $('#is_active_edit').prop('checked', false);
                        }
                        $('#editFormModal').modal('show');
                    }
                });
            });

            //----------Update Data----------------------
            @include('admin.includes.common_js.update_button_click_js')
            @include('admin.includes.common_js.update_form_js',['route_name'=>'admin.page.update'])
            //---------- Active ------------
            @include('admin.includes.common_js.active_js',['route_name'=>'admin.page.active'])
            //---------- Inactive ------------
            @include('admin.includes.common_js.inactive_js',['route_name'=>'admin.page.inactive'])
            //---------- Delete ------------
            @include('admin.includes.common_js.delete_js',['route_name'=>'admin.page.destroy'])
            //---------- Bulk Action ------------
            @include('admin.includes.common_js.bulk_action_js',['route_name_bulk_active_inactive'=>'admin.page.bulk_action'])


            //For Editor
            tinymce.init({
                selector: '.text-editor',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                },
                height: 250,

                image_title: true,
                /* enable automatic uploads of images represented by blob or data URIs*/
                automatic_uploads: true,
                invalid_elements: 'script',
                entity_encoding : "raw",
                /*
                    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                    images_upload_url: 'postAcceptor.php',
                    here we add custom filepicker only to Image dialog
                */
                file_picker_types: 'image',
                /* and here's our custom image picker*/
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    /*
                        Note: In modern browsers input[type="file"] is functional without
                        even adding it to the DOM, but that might not be the case in some older
                        or quirky browsers like IE, so you might want to add it to the DOM
                        just in case, and visually hide it. And do not forget do remove it
                        once you do not need it anymore.
                    */

                    input.onchange = function () {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = function () {
                            /*
                                Note: Now we need to register the blob in TinyMCEs image blob
                                registry. In the next release this part hopefully won't be
                                necessary, as we are looking to handle it internally.
                            */
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            /* call the callback and populate the Title field with the file name */
                            cb(blobInfo.blobUri(), {title: file.name});
                        };
                        reader.readAsDataURL(file);
                    };

                    input.click();
                },

                plugins: [
                    'advlist autolink lists link image charmap anchor textcolor',
                    'searchreplace',
                    'insertdatetime media table paste wordcount'
                ],
                menubar: '',
                toolbar: 'insertfile | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | media | forecolor backcolor | table | removeformat',
                branding: false
            });

        })(jQuery);
    </script>
@endpush

