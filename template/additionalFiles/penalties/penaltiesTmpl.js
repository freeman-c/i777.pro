var searchStr = '';
function getPenaltiesList(){
    $('.delete-penalty').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/penalties/penaltiesController.php',
        method: 'POST',
        data: {
            operation: 'getPenaltiesList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
            $('.navigation').hide();   
        },
        success: function(data){
            // console.log(data);

            $(".penalties-table tbody tr").remove();
            
            penaltiesTable.fnClearTable();
            penaltiesTable.fnDraw();
            penaltiesTable.fnDestroy();

            $(".penalties-table tbody").append(data);

            penaltiesTable = $('.penalties-table').dataTable({
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

function addPenalty(){
    var title = 'Добавление штрафа';
    var content = '<div id="box_add_penalty"></div>';    
    modal(title,content);
    $('#box_add_penalty').load('/template/include/penalties.php');
}

function editPenalty(id){
    var title = 'Редактирование штрафа';
    var content = '<div id="box_edit_penalty"></div>';    
    modal(title,content);
    $('#box_edit_penalty').load('/template/include/penalties.php?id='+id);
}

function deletePenalties(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/penalties/penaltiesController.php",
            method: 'POST',
            data : $('#form-penalties').serialize()+'&operation=deletePenalties',
            beforeSend: function(){
                WaitingBarShow('Удаление товаров...');
            },
            success: function(response){
                WaitingBarHide();
                // response = $.parseJSON(response);
                // if (response.success == 'false')
                //     alert('Error! '+response.error);
                console.log(response);
                getPenaltiesList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}


var penaltiesTable;
$(document).ready(function(){

    penaltiesTable = $('.penalties-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getPenaltiesList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-penalty').click(function() {
        addPenalty();
    });

    $('.edit-penalty').click(function() {
        editPenalty();
    });

    $('.delete-penalty').click(function() {
        deletePenalties();
        getPenaltiesList();
    });
});   