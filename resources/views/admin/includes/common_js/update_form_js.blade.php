$("#updateForm").on("submit",function(e){
    e.preventDefault();
    let routeName = "{{route($route_name)}}";
    $.ajax({
        url: routeName,
        method: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: "json",
        error: function(response){
            console.log(response)
            var dataKeys   = Object.keys(response.responseJSON.errors);
            var dataValues = Object.values(response.responseJSON.errors);
            let html = '<div class="alert alert-danger">';
            for (let count = 0; count < dataValues.length; count++) {
                html += '<p>' + dataValues[count] + '</p>';
            }
            html += '</div>';
            $('#errorMessageEdit').fadeIn("slow");
            $('#errorMessageEdit').html(html);
            setTimeout(function() {
                $('#errorMessageEdit').fadeOut("slow");
            }, 3000);
            $('#updateButton').text('Update');
        },
        success: function (data) {
            console.log(data);
            $('#dataListTable').DataTable().ajax.reload();
            $('#updateForm')[0].reset();
            $("#editFormModal").modal('hide');
            $('#alert_message').fadeIn("slow");
            if (data.demo) {
                $('#alert_message').addClass('alert alert-danger').html(data.demo);
            }else{
                $('#alert_message').addClass('alert alert-success').html(data.success);
            }
            setTimeout(function() {
                $('#alert_message').fadeOut("slow");
            }, 3000);
            $('#updateButton').text('Update');
        }
    });
});
