$(document).ready(function() {
    $('.button-save-binotel-phone').click(function() {
        if ($('.binotel-phone-id').val() == "")
            operation = "addBinotelPhone";
        else
            operation = "editBinotelPhone";
        $.ajax({
            url: '/template/additionalFiles/binotelPhones/binotelPhones.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('.binotel-phone-id').val(),
                bngroup: $('.bngroup').val(),
                phone: $('.phone').val(),
            },
        })
        .always(function(response) {
            console.log(response);
        });
        
        CloseModal();
        getBinotelPhonesList();
        $('.delete-binotel-phone').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});