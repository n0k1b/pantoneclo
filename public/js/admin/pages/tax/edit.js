    // ------------ Edit ------------------
    $(document).on("click",".edit",function(e){
        e.preventDefault();
        var taxId = $(this).data("id");
        $.ajax({
            url: editURL,
            type: "GET",
            data: {tax_id:taxId},
            error: function(response){
                console.log(response.responseJSON.errors);
            },
            success: function(data){
                console.log(data);
                $('#tax_id').val(data.tax.id);
                $('#taxTranslationId').val(data.taxTranslation.id);
                $('#tax_class').val(data.taxTranslation.tax_class);
                $('#based_on').selectpicker('val',data.tax.based_on);
                $('#tax_name').val(data.taxTranslation.tax_name);
                $('#country').selectpicker('val',data.tax.country);
                $('#state').val(data.taxTranslation.state);
                $('#city').val(data.taxTranslation.city);
                $('#zip').val(data.tax.zip);
                $('#rate').val(data.tax.rate);
                if (data.tax.is_active==1) {
                    $('#is_active').attr('checked', true)
                }else{
                    $('#is_active').attr('checked', false)
                }
                $('#editFormModal').modal('show');
            }
        });
    });
