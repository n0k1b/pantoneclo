(function ($) {
    "use strict";

    $(document).on("click",".delete",function(e){
        e.preventDefault();
        let id = $(this).data("id");

        if (!confirm('Are you sure you want to continue?')) {
            alert(false);
        }else{
            $.get({
                url: deleteURL,
                data: {id:id},
                error: function(response){
                    var dataKeys   = Object.keys(response.responseJSON.errors);
                    var dataValues = Object.values(response.responseJSON.errors);
                    let html = '<div class="alert alert-danger">';
                    for (let count = 0; count < dataValues.length; count++) {
                        html += '<p>' + dataValues[count] + '</p>';
                    }
                    html += '</div>';
                    $('#alert_message').fadeIn("slow"); //Check in top in this blade
                    $('#alert_message').html(html);
                    setTimeout(function() {
                        $('#alert_message').fadeOut("slow");
                    }, 3000);
                },
                success: function(data){
                    console.log(data);

                    $('#alert_message').fadeIn("slow");
                    if(data.success){
                        $('#dataListTable').DataTable().ajax.reload();
                        $('#alert_message').addClass('alert alert-success').html(data.success);
                    }
                    else if (data.demo) {
                        $('#alert_message').addClass('alert alert-danger').html(data.demo);
                    }
                    setTimeout(function() {
                        $('#alert_message').fadeOut("slow");
                    }, 3000);
                }
            });
        }
    });

})(jQuery);
