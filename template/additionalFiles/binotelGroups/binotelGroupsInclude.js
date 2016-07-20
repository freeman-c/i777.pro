$(document).ready(function() {
    $('.button-save-binotel_group').click(function() {
        if ($('.binotel-group-id').val() == "")
            operation = "addBinotelGroup";
        else
            operation = "editBinotelGroup";
        $.ajax({
            url: '/template/additionalFiles/binotelGroups/binotelGroups.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('.binotel-group-id').val(),
                bngroup: $('.bngroup').val(),
                name: $('.name').val(),
            },
        })
        .always(function(response) {
            console.log(response);
        });
        
        CloseModal();
        getBinotelGroupsList();
        $('.delete-binotel-group').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});