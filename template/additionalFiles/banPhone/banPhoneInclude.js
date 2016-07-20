$(document).ready(function() {
    $('.button-save-ban-phone-rule').click(function() {
        if ($('#ban-phone-id').val() == "")
            operation = "addBanPhoneRule";
        else
            operation = "editBanPhoneRule";
        $.ajax({
            url: '/template/additionalFiles/banPhone/banPhone.php',
            type: 'POST',
            data: {
                operation: operation,
                id: $('#ban-phone-id').val(),
                phone: $('.phone').val(),
                reason: $('.reason').val(),
            },
        })
        .always(function(response) {
            console.log(response);
        });
        
        CloseModal();
        getBanPhoneRulesList();
        $('.delete-ban-phone-rule').fadeOut();
    });

    $('.close-modal').click(function(event) {
        CloseModal();
    });
});