function getBanIpRulesList(){
    $('.delete-ban-ip-rule').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/banIp/banIp.php',
        method: 'POST',
        data: {
            operation: 'getBanIpRulesList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            proces = true;  
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".ban-ip-table tbody tr").remove();
            
            banIpTable.fnClearTable();
            banIpTable.fnDraw();
            banIpTable.fnDestroy();

            $(".ban-ip-table tbody").append(data);

            banIpTable = $('.ban-ip-table').dataTable({
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

function addBanIpRule(){
    var title = 'Добавление правила бана по IP';
    var content = '<div id="box_add_ban_ip_rule"></div>';    
    modal(title,content);
    $('#box_add_ban_ip_rule').load('/template/include/ban_ip.php');
}

function editBanIpRule(id){
    var title = 'Редактирование правила бана по IP';
    var content = '<div id="box_edit_ban_ip_rule"></div>';    
    modal(title,content);
    $('#box_edit_ban_ip_rule').load('/template/include/ban_ip.php?id='+id);
}

function deleteBanIpRule(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/banIp/banIp.php",
            method: 'POST',
            data : $('#form-ban-ip').serialize()+'&operation=deleteBanIpRule',
            beforeSend: function(){
                WaitingBarShow('Удаление правил...');
            },
            success: function(response){
                response = $.parseJSON(response);
                if (response.success == 'false')
                    alert('Error! '+response.error);
                getBanIpRulesList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}
var banIpTable;
$(document).ready(function(){

    banIpTable = $('.ban-ip-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getBanIpRulesList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-ban-ip-rule').click(function() {
        addBanIpRule();
    });

    $('.edit-ban-ip-rule').click(function() {
        editBanIpRule();
    });

    $('.delete-ban-ip-rule').click(function() {
        deleteBanIpRule();
        getBanIpRulesList();
    });
});   