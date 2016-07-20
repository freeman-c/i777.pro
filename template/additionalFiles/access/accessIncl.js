$(document).ready(function() {
    $('.button-save-access').click(function() {
        if ($('#access-id').val() == "")
            operation = "addAccess";
        else
            operation = "editAccess";

        $.ajax({
            url: '/template/additionalFiles/access/accessController.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#access-id').val(),
                name: $('#name').val(),
                link: $('#link').val()
            },
        })
        .always(function(response) {
            console.log(response);
        });

        searchStr = $('.dataTables_filter').find($("input[type='text']")).val();
        CloseModal();
         getAccesses(); 
         getExceptions();       
        $('#button-operation-delete').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});