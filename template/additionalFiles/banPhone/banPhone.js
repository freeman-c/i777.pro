function getBanPhoneRulesList(){
    $('.delete-ban-phone-rule').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/banPhone/banPhone.php',
        method: 'POST',
        data: {
            operation: 'getBanPhoneRulesList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".ban-phone-table tbody tr").remove();
            
            banPhoneTable.fnClearTable();
            banPhoneTable.fnDraw();
            banPhoneTable.fnDestroy();

            $(".ban-phone-table tbody").append(data);

            banPhoneTable = $('.ban-phone-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
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
            proces = false;                                                     

            WaitingBarHide();
        }
    }); 
}

function addBanPhoneRule(){
    var title = 'Добавление правила бана по телефону';
    var content = '<div id="box_add_ban_phone_rule"></div>';    
    modal(title,content);
    $('#box_add_ban_phone_rule').load('/template/include/ban_phone.php');
}

function editBanPhoneRule(id){
    var title = 'Редактирование правила бана по телефону';
    var content = '<div id="box_edit_ban_phone_rule"></div>';    
    modal(title,content);
    $('#box_edit_ban_phone_rule').load('/template/include/ban_phone.php?id='+id);
}

function deleteBanPhoneRule(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/banPhone/banPhone.php",
            method: 'POST',
            data : $('#form-ban-phone').serialize()+'&operation=deleteBanPhoneRule',
            beforeSend: function(){
                WaitingBarShow('Удаление правил...');
            },
            success: function(response){
                response = $.parseJSON(response);
                if (response.success == 'false')
                    alert('Error! '+response.error);
                getBanPhoneRulesList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}
var banPhoneTable;
$(document).ready(function(){

    banPhoneTable = $('.ban-phone-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getBanPhoneRulesList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-ban-phone-rule').click(function() {
        addBanPhoneRule();
    });

    $('.edit-ban-phone-rule').click(function() {
        editBanPhoneRule();
    });

    $('.delete-ban-phone-rule').click(function() {
        deleteBanPhoneRule();
        getBanPhoneRulesList();
    });
});   