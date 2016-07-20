$(document).ready(function(){ 
    
    $.ajax({
        url: "/modules/np/city.php",
        method: 'POST',
        beforeSend: function(){
        },
        success: function(data){
            //alert(data);
            $('#city').append(data);
            $("#city").chosen();
        },
        error: function() { alert('Error API nova_poshta: city.php'); }                    
    }); 
    
    $('#button-np').click(function(){
        var ttn = $('#ttn').val();
        if(ttn.length < 1){
            alert('Введите номер декларации!');
        }else{
                $.ajax({
                    url: "/modules/np/tracking.php",
                    method: 'POST',
                    data : {ttn:ttn},
                    beforeSend: function(){
                        $('#result-np').html('<img src="/image/ajax-load.gif"> &nbsp Подождите, пожалуйста...');
                    },
                    success: function(data){
                        //alert(data);
                        $('#result-np').html(data);
                        //MessageTray('Таблица '+id_table+' удалена.');
                        //location.reload();
                        //window.location.href = '/?action=dropshipping';
                    },
                    error: function() { alert('Error API nova_poshta: tracking.php'); }                    
                });
            }
    });
    
    $('#city').change(function(){
        var city = $('#city').val();
            $.ajax({
                url: "/modules/np/warenhouse.php",
                method: 'POST',
                data : {city:city},
                beforeSend: function(){
                    $("#city-list").html('<img src="/image/ajax-load.gif"> &nbsp Подождите, пожалуйста...');
                },
                success: function(data){
                    console.log(data);
                    $("#city-list").html(data);
                },
                error: function() { alert('Error API nova_poshta: warenhouse.php'); }                    
            });
    });

    $('.update-warehouses-list-button').click(function(event) {
        $.ajax({
            url: 'modules/np/updateWarehousesList.php',
            type: 'POST',
            beforeSend: function(){
                WaitingBarShow('Обновление списка городов и отделений');
            },
            success: function(response){
                WaitingBarHide();
                MessageTray(response);
            }
        });
        
    });
                       
});    