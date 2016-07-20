function getBinotelPhonesList(){
    $('.delete-binotel-phone').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/binotelPhones/binotelPhones.php',
        method: 'POST',
        data: {
            operation: 'getBinotelPhonesList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
        },
        success: function(data){
            // console.log(data);

            $(".binotel-phones-table tbody tr").remove();
            
            binotelPhones.fnClearTable();
            binotelPhones.fnDraw();
            binotelPhones.fnDestroy();

            $(".binotel-phones-table tbody").append(data);

            binotelPhones = $('.binotel-phones-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

            $('.fg-toolbar').click(function(){
                setTableListListeners();
            }).keyup(function(){
                setTableListListeners();
            });

            $('.selected').unbind('click');
            $('.selected').bind('click', function(){ 
                SELECT_SHIFT(); 
            });

            $('#table-list tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  
        },
        complete: function(){
            WaitingBarHide();
        }
    }); 
}

function addBinotelPhone(){
    var title = 'Добавление телефона Binotel';
    var content = '<div id="box_add_binotel_phones"></div>';    
    modal(title,content);
    $('#box_add_binotel_phones').load('/template/include/binotel_phones.php');
}

function editBinotelPhone(id){
    var title = 'Редактирование телефона Binotel';
    var content = '<div id="box_edit_binotel_phones"></div>';    
    modal(title,content);
    $('#box_edit_binotel_phones').load('/template/include/binotel_phones.php?id='+id);
}

function deleteBinotelPhones(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/binotelPhones/binotelPhones.php",
            method: 'POST',
            data : $('#form-binotel-phones').serialize()+'&operation=deleteBinotelPhones',
            beforeSend: function(){
                WaitingBarShow('Удаление телефонов...');
            },
            success: function(response){
                console.log(response);
                getBinotelPhonesList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}
var binotelPhones;
$(document).ready(function(){

    binotelPhones = $('.binotel-phones-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getBinotelPhonesList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-binotel-phone').click(function() {
        addBinotelPhone();
    });

    $('.edit-binotel-phone').click(function() {
        editBinotelPhone();
    });

    $('.delete-binotel-phone').click(function() {
        deleteBinotelPhones();
        getBinotelPhonesList();
    });
});   