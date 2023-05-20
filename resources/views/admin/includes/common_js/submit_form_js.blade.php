$("#submitForm").on("submit",function(e){
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
            $('#errorMessage').fadeIn("slow");
            $('#errorMessage').html(html);
            setTimeout(function() {
                $('#errorMessage').fadeOut("slow");
            }, 3000);
            $('#submitButton').text('Save');
        },
        success: function (response) {
            console.log(response);
            
            $('#dataListTable').DataTable().ajax.reload();
            $('#submitForm')[0].reset();
            $("#formModal").modal('hide');
            $('#alert_message').fadeIn("slow");
            if (response.demo) {
                $('#alert_message').addClass('alert alert-danger').html(response.demo);
            }else{
                $('#alert_message').addClass('alert alert-success').html(response.success);
            }
            setTimeout(function() {
                $('#alert_message').fadeOut("slow");
            }, 3000);
            $('#submitButton').text('Save');
        }
    });
});
