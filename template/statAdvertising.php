<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/modules/np/get_np_city_list.php');
?>
<h2>Статистика по рекламе</h2>

<div id="stat-ad"  class="stat-container2">
        <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px;">
             <div>
                Город
                <select id="city" name="npCity">
                    <option value="">Выберите город</option>
                    <?php 
                    $npCityList = getNPCityList();
                    foreach ($npCityList as $npCityListElem) { ?>
                        <option value="<?=$npCityListElem['name']?>"><?=$npCityListElem['name']?></option>
                    <?php } ?>
                </select>
            </div>
            <br>
            Период&nbsp;
                с  <input type="text" id="between-start-ad" class="dateInp" value="<?=date('Y-m-d')?>" size="10"> 
                по <input type="text" id="between-end-ad"   class="dateInp" value="<?=date('Y-m-d')?>" size="10">
            &nbsp;
            <button id="p-today" class="p-today button button-period">Cегодня</button>
            <button id="p-yesterday" class="p-yesterday button button-period">Вчера</button>
            <button id="p-week" class="p-week button button-period">Текущая неделя</button>
            <button id="p-month" class="p-month button button-period">Текущий месяц</button>
            <button id="p-all" class="p-all button button-period">За всё время</button>
        </div>
        <br>
</div>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/statAdvertising/statAdvertising.css">
<script type="text/javascript" src="/template/additionalFiles/statAdvertising/statAdvertising.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.button-period').click(function(){
            period = $(this).attr('id');
            var dateStart = '', dateEnd = '';
            switch($(this).attr('id')){
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

            $('#between-start-ad').val(dateStart);
            $('#between-end-ad').val(dateEnd);
            
            getStatAdvertising();
        });
    });
</script>

<table id="adStatTotal" class="stat-total-table" border="0" cellspacing="0">
    <thead>
        <tr>
            <td>ИТОГО</td>
            <td>Заказов</td>
            <td>Продано<br>шт.</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div style="margin-top: 20px"></div>
<table id="adStat" class="stat-advertising-table" border="0" cellspacing="0">
    <thead>
        <tr>
            <td>UTM Source</td>
            <td>UTM Medium</td>
            <td>UTM Term</td>
            <td>UTM Content</td>
            <td>UTM Campaign</td>
            <td>Товар</td>
            <td>Заказов</td>
            <td>Продано<br>шт.</td>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>

