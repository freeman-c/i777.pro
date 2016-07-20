function getBinotelGroupsList(){
    $('.delete-binotel-group').fadeOut();
    $('#select-all-checkbox').removeAttr('checked');

    $.ajax({
        url: '/template/additionalFiles/binotelGroups/binotelGroups.php',
        method: 'POST',
        data: {
            operation: 'getBinotelGroupsList',
        },
        beforeSend: function() {
            WaitingBarShow('Обработка запроса...');
        },
        success: function(data){
            // console.log(data);

            $(".binotel-groups-table tbody tr").remove();
            
            binotelGroups.fnClearTable();
            binotelGroups.fnDraw();
            binotelGroups.fnDestroy();

            $(".binotel-groups-table tbody").append(data);

            binotelGroups = $('.binotel-groups-table').dataTable({
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

function addBinotelGroup(){
    var title = 'Добавление группы Binotel';
    var content = '<div id="box_add_binotel_groups"></div>';    
    modal(title,content);
    $('#box_add_binotel_groups').load('/template/include/binotel_groups.php');
}

function editBinotelGroup(id){
    var title = 'Редактирование группы Binotel';
    var content = '<div id="box_edit_binotel_groups"></div>';    
    modal(title,content);
    $('#box_edit_binotel_groups').load('/template/include/binotel_groups.php?id='+id);
}

function deleteBinotelGroups(){
    if(confirm('Удаляем выделенные элементы?')){  
        $.ajax({
            url: "/template/additionalFiles/binotelGroups/binotelGroups.php",
            method: 'POST',
            data : $('#form-binotel-groups').serialize()+'&operation=deleteBinotelGroups',
            beforeSend: function(){
                WaitingBarShow('Удаление групп...');
            },
            success: function(response){
                console.log(response);
                getBinotelGroupsList();                
            }
        });
    }
    $('#select-all-checkbox').removeAttr('checked');
}
var binotelGroups;
$(document).ready(function(){

    binotelGroups = $('.binotel-groups-table').dataTable({
                "aLengthMenu": [ 2,10,25,50,100,200,500,1000 ],
                "iDisplayLength": 50,
                "bJQueryUI" : true,
                "sPaginationType": "full_numbers"});

    getBinotelGroupsList();

    $('#table-list tbody td').click(function(event){
        $('#table-list tbody tr').removeClass('selected-row-in-table');
        t=event.target||event.srcElement;
        $(t).parent('tr').addClass('selected-row-in-table');
    });   

    $('.add-binotel-group').click(function() {
        addBinotelGroup();
    });

    $('.edit-binotel-group').click(function() {
        editBinotelGroup();
    });

    $('.delete-binotel-group').click(function() {
        deleteBinotelGroups();
        getBinotelGroupsList();
    });
});   