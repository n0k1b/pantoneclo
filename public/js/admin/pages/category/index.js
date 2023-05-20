(function($) {


    "use strict";

    $(document).ready(function () {
        let table_table = $('#dataListTable').DataTable({
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
                url: indexURL,
            },

            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_image',
                    name: 'category_image',
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                },
                {
                    data: 'parent',
                    name: 'parent',

                },
                {
                    data: 'is_active',
                    name: 'is_active',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                }
            ],


            "order": [],
            'language': {
                'lengthMenu': `_MENU_ ${recordPerPage}`,
                "info": `${showing} _START_ - _END_ (_TOTAL_)`,
                "search": search,
                'paginate': {
                    'previous': previous,
                    'next': next
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
        new $.fn.dataTable.FixedHeader(table_table);
    });


    $(document).on('click', '.edit', function () {
        var id = $(this).data("id");
        $('#alert_message').html('');
        $.ajax({
            url: editURL,
            type: "GET",
            data: {category_id:id},
            success: function (data) {
                console.log(data);
                $('#category_id').val(data.category.id);
                $('#category_translation_id').val(data.categoryTranslation.id);
                $('#category_name_edit').val(data.categoryTranslation.category_name);
                $('#description_edit').val(data.category.description);
                $('#cateogry_icon_edit').val(data.category.icon);
                $('#parent_id_edit').selectpicker('val', data.category.parent_id);
                $('#description_position_edit').selectpicker('val', data.category.description_position);
                if (data.category.top === 1) {
                    $('#top_edit').prop('checked', true);
                } else {
                    $('#top_edit').prop('checked', false);
                }
                if (data.category.is_active === 1) {
                    $('#isActive_edit').prop('checked', true);
                } else {
                    $('#isActive_edit').prop('checked', false);
                }
                $('#editFormModal').modal('show');
            }
        })
    });

})(jQuery);

