<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/modules/np/get_np_city_list.php');
?>
<h2>Товары</h2>

<div id="stat-pr"  class="stat-container2">
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
        По дате: 
        с <input type="text" id="between-start-pr" class="prStatDateInp" value="<?=date('Y-m-d')?>" size="10"> 
        по <input type="text" id="between-end-pr" class="prStatDateInp"  value="<?=date('Y-m-d')?>" size="10">
        &nbsp;
        <button id="p-today" class="button button-period">Cегодня</button>
        <button id="p-yesterday" class="button button-period">Вчера</button>
        <button id="p-week" class="button button-period">Текущая неделя</button>
        <button id="p-month" class="button button-period">Текущий месяц</button>
        <button id="p-all" class="p-all button button-period">За всё время</button>
    </div>
</div>

<link type="text/css" rel="stylesheet" href="/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="/template/additionalFiles/statProducts/statProducts.css">
<script type="text/javascript" src="/template/additionalFiles/statProducts/statProducts.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/modules/np/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/modules/np/chosen/chosen.css">


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

            $('#between-start-pr').val(dateStart);
            $('#between-end-pr').val(dateEnd);
            getStatProducts();
        });
    });
</script>

<table id="table-list1" class="stat-total-table table-list" border="0" cellspacing="0">
    <thead>
        <tr>
            <td>ИТОГО</td>
            <td>Заявок</td>
            <td>Из них NEW</td>
            <td>Заказов</td>
            <td>CV2</td>
            <td>Товаров<br>продано</td>
            <td>N СЧ</td>
            <td>N СЧДП</td>
            <td>Выручка</td>
            <td>$ СЧ</td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="filters-container">
    <div>
        <button class="add-filter-button button">
            Добавить фильтр
        </button>
        <label><input type="checkbox" class="checkbox-no-utm">Заказы без меток</label>
    </div>
    <div class="filter-panel"> 
    </div>
    <div>
        <button class="apply-filters-button button">
            Применить фильтры
        </button>
    </div>
</div>

<table id="table-list" class="stat-products-table" border="0" cellspacing="0">
    <thead>
        <tr>
            <td>Товар</td>
            <td>Заявок</td>
            <td>Из них NEW</td>
            <td>Заказов</td>
            <td>CV2</td>
            <td>Товаров<br>продано</td>
            <td>N СЧ</td>
            <td>N СЧДП</td>
            <td>Выручка</td>
            <td>$ СЧ</td>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>
