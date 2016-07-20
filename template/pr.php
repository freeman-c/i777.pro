<h2>
    <img src="<?=SITE_URL?>/image/LOGO-RP.png" style="width: 100px;">
</h2>
<style>
    #table-np{
        min-width: 980px;
        width: 100%;
        border-top: 4px solid #0055A5;
        padding: 4px;
    }
    #table-np td{
        padding: 0px 8px;
    }
    #table-np pre{
        font-size: 11px;
    }
    #ajax-result-np-table{
        border: 2px dashed #FF392E;
        padding: 1px;
    }
    .td-grey{
        color:#757575;
    }
    #ajax-result-np-table td{
        border-bottom: 1px solid #E3E3E3;
        background: #FFE;
    }
    #button-pr{
        background: #0055A5;
        color: #FFF;
        border: 1px solid #00498C;
        padding: 3px 6px 3px 8px;
        cursor: pointer;
        border-radius: 3px;
    }
    #result-np{
        padding: 4px;         
    }
    #city-list{
        overflow: auto; 
        height: 290px;
        border:1px solid #F6F6F6;
    }
    #table-warenhouse-list td{
        font-size: 11px;
        padding: 2px;
    }
    #table-warenhouse-list thead td{
        background: #DCE5EF;
        border-bottom: 2px solid #ACCADC;
        text-align: center;
        font-weight: bold;
    }
    #table-warenhouse-list tbody tr:nth-child(odd){
        background: #FFF;
    }
    #table-warenhouse-list tbody tr:nth-child(even){        
        background: #E9EFF8;
    }
    /*--------------- table api result --------------*/
    #table-pr td{
        font-family: Tahoma;
        font-size: 11px;
        text-align: center;
        background: #EEE;
        border: 1px solid #F6F6F6;
    }
    .title-table-pr td{
        background: #DDD !important;
        text-align: center;
        font-weight: bold;
    }
</style>
<!--<script type="text/javascript" src="<?=SITE_URL?>/modules/np/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/modules/np/chosen/chosen.css">-->
<script>
$(document).ready(function(){    
    
    $('#button-pr').click(function(){
        var ttn = $('#ttn').val();
        if(ttn.length < 1){
            alert('Введите номер декларации!');
        }else{
                $.ajax({
                    url: "<?=SITE_URL?>/modules/pr/info.php",
                    method: 'POST',
                    data : {ttn:ttn},
                    beforeSend: function(){
                        $('#result-pr').html('<img src="<?=SITE_URL?>/image/ajax-load.gif"> &nbsp Подождите, пожалуйста...');
                    },
                    success: function(data){
                        $('#result-pr').html(data);
                    },
                    error: function() { alert('Error API nova_poshta: tracking.php'); }                    
                });
            }
    });    
                       
});    
</script>
<table id="table-np" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td valign="top" style="border-right: 1px solid #E2E2E2;">
            <div style="color: #0055A5; padding: 10px; font-size: 17px;">
                <img src="<?=SITE_URL?>/image/TTH.png" style="width: 45px; margin-bottom: -10px;"> Отследить
            </div>
            <div>
            <input type="text" id="ttn" size="20" placeholder="Номер" value="" style="padding: 3px 6px; margin-right: -3px;">
            <!--60001663027986-->
            <button id="button-pr">►</button>
            <div style="color:#808080; padding:1px 2px 8px; font-size:11px; white-space:nowrap;">Введите почтовый идентификатор</div>
            </div>  

        </td>        
        
        <td valign="top">
            <div id="result-pr">&nbsp;</div>
            <!--<div style="color: #0055A5; padding: 4px; font-size: 17px;">
                <img src="<?=SITE_URL?>/image/warenhouse.png" style="margin-bottom: -6px;"> Список отделений почтовой связи
            </div>
            <!--<input type="text" id="city" placeholder="Назва мiста" size="30" style="padding: 3px 6px; margin-top: 5px;">--
            <br>
               <select id="city" style="width:340px;">
                    <option value="">Выберите город</option>
               </select>
            <br>
            <div style="color:#808080; padding:2px; font-size:11px; position:relative;">Введите название города (на русском)</div>
            <div id="city-list"></div>-->
            
        </td>
    </tr>
</table>