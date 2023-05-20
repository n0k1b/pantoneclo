(function ($) {
    "use strict";

    $("#bulk_action").on("click",function(){
        var idsArray = [];
        let table = $('#dataListTable').DataTable();
        idsArray = table.rows({selected: true}).ids().toArray();

        if(idsArray.length === 0){
            alert("Please Select at least one checkbox.");
        }else{
            $('#bulkConfirmModal').modal('show');
            let action_type;

            $("#active").on("click",function(){
                console.log(idsArray);
                action_type = "active";
                $.get({
                    url: bulkActionURL,
                    data: {idsArray:idsArray,action_type:action_type},
                    success: function (data) {
                        if(data.success){
                            $('#bulkConfirmModal').modal('hide');
                            table.rows('.selected').deselect();
                            $('#dataListTable').DataTable().ajax.reload();
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-success').html(data.success);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }
                        else if (data.demo) {
                            $('#bulkConfirmModal').modal('hide');
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-danger').html(data.demo);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }

                    }
                });
            });
            $("#inactive").on("click",function(){
                action_type = "inactive";
                console.log(idsArray);
                $.get({
                    url: bulkActionURL,
                    data: {idsArray:idsArray,action_type:action_type},
                    success: function (data) {
                        console.log(data);
                        if(data.success){
                            $('#bulkConfirmModal').modal('hide');
                            table.rows('.selected').deselect();
                            $('#dataListTable').DataTable().ajax.reload();
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-success').html(data.success);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }
                        else if (data.demo) {
                            $('#bulkConfirmModal').modal('hide');
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-danger').html(data.demo);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }
                    }
                });
            });


            $("#bulkDelete").on("click",function(){
                action_type = "delete";
                $.get({
                    url: bulkActionURL,
                    data: {idsArray:idsArray,action_type:action_type},
                    success: function (data) {
                        console.log(data);
                        if(data.success){
                            $('#bulkConfirmModal').modal('hide');
                            table.rows('.selected').deselect();
                            $('#dataListTable').DataTable().ajax.reload();
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-success').html(data.success);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }
                        else if (data.demo) {
                            $('#bulkConfirmModal').modal('hide');
                            $('#dataListTable').DataTable().ajax.reload();
                            $('#alert_message').fadeIn("slow"); //Check in top in this blade
                            $('#alert_message').addClass('alert alert-danger').html(data.demo);
                            setTimeout(function() {
                                $('#alert_message').fadeOut("slow");
                            }, 3000);
                        }
                    }
                });
            });
        }
    });

})(jQuery);

