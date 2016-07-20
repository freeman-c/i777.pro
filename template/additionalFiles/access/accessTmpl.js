var searchStr = '';
function getAccesses(){
    $('.button-operation-delete').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/access/accessController.php',
        method: 'POST',
        data: {
            operation: 'getAccesses',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".access-table tbody tr").remove();
            
            accessTable.fnClearTable();
            accessTable.fnDraw();
            accessTable.fnDestroy();

            $(".access-table tbody").append(data);

            accessTable = $('.access-table').dataTable({
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
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}

function getExceptions(){
    $('.button-operation-delete').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/access/accessController.php',
        method: 'POST',
        data: {
            operation: 'getExceptions',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".exceptions-table tbody tr").remove();
            
            exceptionsTable.fnClearTable();
            exceptionsTable.fnDraw();
            exceptionsTable.fnDestroy();

            $(".exceptions-table tbody").append(data);

            exceptionsTable = $('.exceptions-table').dataTable({
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

            $('#table-list2 tbody tr').hover(function(){
                $(this).find(".option-button").show();
            }, function() {
                $(this).find(".option-button").hide();
            });  
        },
        complete: function(){
            WaitingBarHide();
            $('.dataTables_filter').find($("input[type='text']")).val(searchStr).keyup();
        }
    }); 
}

function editAccess(id) {
    var title = 'Редактирование доступа';
    var content = '<div id="box_edit_access"></div>';    
    modal(title,content);
    $('#box_edit_access').load('/template/include/addNewAccessPage.php?id='+id);
}

var accessTable;
var exceptionsTable;

$(document).ready(function() {
    
    accessTable = $('.access-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    exceptionsTable = $('.exceptions-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});


    getAccesses();
    getExceptions();

    $('#button-operation-add').click(function() {
        var title = 'Добавление новой страницы';
        var content = '<div id="box_add_new_access_page"></div>';    
        modal(title,content);
        $('#box_add_new_access_page').load('/template/include/addNewAccessPage.php');
    });

    $('#button-operation-delete').click(function() {
        if(confirm('Удаляем выделенные элементы?')){  
            $.ajax({
                url: "/template/additionalFiles/access/accessController.php",
                method: 'POST',
                data : $('#form-access').serialize()+'&operation=deleteAccess',
                beforeSend: function(){
                    WaitingBarShow('Удаление товаров...');
                },
                success: function(response){
                    WaitingBarHide();
                    response = $.parseJSON(response);
                    if (response.success == 'false')
                        alert('Error! '+response.error);
                    getAccesses();  
                    getExceptions();
                    $('#button-operation-delete').fadeOut();            
                }
            });
        }
    });

});