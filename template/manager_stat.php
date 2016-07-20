<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/statManagers/statManagers.css">
<script type="text/javascript" src="/template/additionalFiles/statManagers/statManagers.js"></script>
<script type="text/javascript" src="/modules/np/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/modules/np/chosen/chosen.css">

<script> 
$(document).ready(function(){
    $('#between-start-mng').val('<?=date('Y-m-d')?>');
    $('#between-end-mng').val('<?=date('Y-m-d')?>');     

    $('.button-period').click(function(){
        $('.week-bonus-animation').hide();
        $('.month-bonus-animation').hide();

        period = $(this).attr('id');
        var dateStart = '', dateEnd = '';
        switch(period){
            case 'p-today':
                dateStart = dateEnd = '<?=date('Y-m-d')?>';
                break;
            case 'p-yesterday': 
                dateStart = dateEnd = '<?=date('Y-m-d', time()-24*60*60)?>';
                break;
            case 'p-week':
                <?php    
                    $dow = date('N')-1;
                ?>
                dateStart = '<?=date('Y-m-d', time()-(date('N')-1)*24*60*60)?>';
                dateEnd = '<?=date('Y-m-d')?>';
                break;
            case 'p-month':
                dateStart = '<?=date('Y-m-d', time()-(date('j')-1)*24*60*60)?>';
                dateEnd = '<?=date('Y-m-d')?>';
                break;
        }

        $('#between-start-mng').val(dateStart);
        $('#between-end-mng').val(dateEnd);

        getStatManagers();
        getStatTrainees();
    });

    getStatManagers();
    getStatTrainees();
   
});
</script>
<div id="print-statistic">
    <div id="stat-mng"  class="stat-container2">
        <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px; margin: 6px 6px -10px 6px;">
            Период&nbsp; 
                с <input type="text" id="between-start-mng" class="mngStatDateInp" size="10"> 
                по <input type="text" id="between-end-mng"  class="mngStatDateInp"  size="10">
            &nbsp;
            <button id="p-today" class="button button-period">Cегодня</button>
            <button id="p-yesterday" class="button button-period">Вчера</button>
            <button id="p-week" class="button button-period">Текущая неделя</button>
            <button id="p-month" class="button button-period">Текущий месяц</button>
            <button id="p-all" class="button button-period">За всё время</button>
        </div><br>
        <h3>Рейтинг менеджеров</h3>
        <table id="managerStat" class="stat-managers-table" border="0" cellspacing="0">
            <thead>
                <tr>
                    <td>Менеджер</td>                               
                    <td>Заявок</td>                                 
                    <td>Заказов</td>                                
                    <td>CV2</td>                                    
                    <td>Допродажи</td>                              
                    <td>% ДП</td>                                   
                    <td>Допродано<br>товаров</td>                   
                    <td>Допродано<br>перекрёстных<br>товаров</td>   
                    <td>N СЧ</td>                                   
                    <td>Заработано<br>бонусов,<br>грн.</td>         
                    <td class="rating-col-head">Рейтинг</td>
                    <td class="anim-col"></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <br>
        <div style="margin-top: 50px"></div>
        <h3>Рейтинг стажеров</h3>
        <table id="managerStat1" class="stat-trainees-table" border="0" cellspacing="0">
            <thead>
                <tr>
                    <td>Менеджер</td>                               
                    <td>Заявок</td>                                 
                    <td>Заказов</td>                                
                    <td>CV2</td>                                    
                    <td>Допродажи</td>                              
                    <td>% ДП</td>                                   
                    <td>Допродано<br>товаров</td>                   
                    <td>Допродано<br>перекрёстных<br>товаров</td>   
                    <td>N СЧ</td>
                    <td>Заработано<br>бонусов,<br>грн.</td>         
                    <td class="rating-col-head">Рейтинг</td>
                    <td class="anim-col"></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div style="margin-top: 50px"></div>
        <h3>ИТОГИ</h3>
        <table id="managerStat2" class="stat-total-table" border="0" cellspacing="0">
            <thead>
                <tr>
                    <td></td>                               
                    <td>Заявок</td>                                 
                    <td>Заказов</td>                                
                    <td>CV2</td>                                    
                    <td>Допродажи</td>                              
                    <td>% ДП</td>                                   
                    <td>Допродано<br>товаров</td>                   
                    <td>Допродано<br>перекрёстных<br>товаров</td>   
                    <td>N СЧ</td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div> 
</div>
</div>