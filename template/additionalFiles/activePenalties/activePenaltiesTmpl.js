var searchStr = '';
function getActivePenaltiesList(){
    $('.delete-active-penalty').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/activePenalties/activePenaltiesController.php',
        method: 'POST',
        data: {
            operation: 'getActivePenaltiesList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".active-penalties-table tbody tr").remove();
            
            activePenaltiesTable.fnClearTable();
            activePenaltiesTable.fnDraw();
            activePenaltiesTable.fnDestroy();

            $(".active-penalties-table tbody").append(data);

            activePenaltiesTable = $('.active-penalties-table').dataTable({
                "aLengthMenu": [ 10,25,50,100,200,500,1000 ],
                "iDisplayLength": 10,
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

function addActivePenalty(){
    var title = 'Добавление штрафа';
    var content = '<div id="box_add_active_penalty"></div>';    
    modal(title,content);
    $('#box_add_active_penalty').load('/template/include/activePenalties.php');
}

function editActivePenalty(id){
    var title = 'Редактирование штрафа';
    var content = '<div id="box_edit_active_penalty"></div>';    
    modal(title,content);
    $('#box_edit_active_penalty').load('/template/include/activePenalties.php?id='+id);
}

function deleteActivePenalties(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/activePenalties/activePenaltiesController.php",
            method: 'POST',
            data : $('#form-active-penalties').serialize()+'&operation=deleteActivePenalties',
            beforeSend: function(){
                WaitingBarShow('Удаление товаров...');
            },
            success: function(response){
                WaitingBarHide();
                // response = $.parseJSON(response);
                // if (response.success == 'false')
                //     alert('Error! '+response.error);
                console.log(response);
                getActivePenaltiesList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}


var activePenaltiesTable;
$(document).ready(function(){

    activePenaltiesTable = $('.active-penalties-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getActivePenaltiesList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-active-penalty').click(function() {
        addActivePenalty();
    });

    $('.edit-active-penalty').click(function() {
        editActivePenalty();
    });

    $('.delete-active-penalty').click(function() {
        deleteActivePenalties();
        getActivePenaltiesList();
    });

});   