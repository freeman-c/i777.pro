<?php 
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/highcharts_theme.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat1.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat2.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat3.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat4.php';
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat5.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat6.php';
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat7.php';
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat8.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat9.php';
?>
<h2>Статистика
    <span id="panel-button-operation">  
        <button class="button-success" id="button-operation-export-statistic" onclick="export_exel_statistic('<?=SITE_URL?>');">Експорт в Exel</button>      
        <button class="button-edit" onclick="printBlock('#print-statistic');">
            <img src="<?=SITE_URL?>/image/print.ico" style="margin: 0px 4px -2px 0px;">Печать</button>
        </span>
    </h2>
    <style>
        #export-statistic{
            float: right;
        }
        #tabs-statistic{
            margin: 0px;
            padding: 2px;
            height: 50px;
            display: block;
        }
        #tabs-statistic ul{
            list-style: none;
        }
        #tabs-statistic ul li{
            float: left;
        }
        #tabs-statistic ul li a{
            display: block;
            font-size: 13px;
            text-decoration: none;
        }
        .stat-container{
            width: 100%;
            /*width: 1000px;*/
            /*border: 1px solid #6A9FD0; */
            display: none;
            z-index: 995;
            display:block;
        }
        /*----------------------*/
        #between{
            display: block;
            background: #D9EDF2;
            padding: 4px 10px;
        }
        #between .button,#between .button-edit,#between .button-success{
            font-size: 13px;
            padding: 3px 8px;
        }
        #stat-color-line{
            font-size: 11px;
            color: #454545;
            font-family: 'magistral';
        }
        .all-stat-zoom{
            padding: 0px 9px;
            border: 1px solid #757575; 
            margin-left: 24px;
        }
        #select-filter-product{
            width: 200px;
        }
        #select-filter-advertise{
            width: 200px;
        }
        table{
            border-collapse: collapse;
            font-family: Arial; 
            color: #000;         
        }
        .stat-table{
            border-collapse: collapse;
            font-family: Arial;
            border: 0px; 
            border-color: #fff;
            color: #000;         
        }
        .stat-table td,.stat-table th{
            border: 1px solid #000;
        }

        @keyframes b_anim{
            20% {background-color: #fff;}
            50% {background-color: red;}
            80% {background-color: #fff;}
        }
        @-webkit-keyframes b_anim{
            20% {background-color: #fff;}
            50% {background-color: red;}
            80% {background-color: #fff;}
        }
        @-moz-keyframes b_anim{
            20% {background-color: #fff;}
            50% {background-color: red;}
            80% {background-color: #fff;}
        }
        @-o-keyframes b_anim{
            20% {background-color: #fff;}
            50% {background-color: red;}
            80% {background-color: #fff;}
        }

        .month-bonus-animation, .week-bonus-animation{
            /*background-color: red;*/
            -webkit-border-radius: 5px;
                    border-radius: 5px;
            padding: 5px;
            -webkit-animation-name: b_anim;
               -moz-animation-name: b_anim;
                 -o-animation-name: b_anim;
                    animation-name: b_anim;
            -webkit-animation-duration: 0.5s;
               -moz-animation-duration: 0.5s;
                 -o-animation-duration: 0.5s;
                    animation-duration: 0.5s;
            -webkit-animation-iteration-count: infinite;
               -moz-animation-iteration-count: infinite;
                 -o-animation-iteration-count: infinite;
                    animation-iteration-count: infinite;
        }

        .button-period{
            padding: 2px 10px;
            font-size: 13px;
            border-radius: 5px;
        }

        .month-bonus-animation, .week-bonus-animation{
            display: none;
        }
    </style>
    <script type="text/javascript" src="<?=SITE_URL?>/modules/np/chosen/chosen.jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/modules/np/chosen/chosen.css">
    <script> 
    $(document).ready(function(){

        stat = '<?=$_GET['stat']?>';
        if(stat == ''){
            stat = '6';
            $('#defStatPage').attr("class","button button-success");
        }

        var period = 'p-today';

        function getAdStat(){
            $.ajax({
                url: '<?=SITE_URL?>/modules/get_stat_reklama.php',
                method: 'POST',
                data:{
                    productId:$('#select-filter-product').val(),
                    utm_source:$('#select-filter-advertise').val(),
                    dateFrom:$('#between-start-ad').val(),
                    dateTo:$('#between-end-ad').val(),
                    utm_term:$('#select-filter-marketgid-term').val(),
                    utm_content:$('#select-filter-marketgid-content').val(),
                    utm_campaign:$('#select-filter-marketgid-campaing').val(),
                    sort: $('#select-sort-ad').val()
                },
                beforeSend: function() {
                    WaitingBarShow('Обработка запроса...');
                    proces = true;     
                },
                success: function(data){
                    // console.log(data);
                    $("#stat-advertising tbody").remove();
                    $("#stat-advertising thead").remove();
                    $("#stat-advertising").append(data);
                },
                complete: function(){
                    proces = false;                                                     
                    WaitingBarHide();
                }
            });

        }

        function getProdStat(){
            $.ajax({
                url: '<?=SITE_URL?>/modules/get_stat_product.php',
                method: 'POST',
                data:{
                    productId:$('#select-filter-product-pr').val(),
                    dateFrom:$('#between-start-pr').val(),
                    dateTo:$('#between-end-pr').val(),
                    sort: $('#select-sort-pr').val()
                },
                beforeSend: function() {
                    WaitingBarShow('Обработка запроса...');
                    proces = true;     
                },
                success: function(data){
                    console.log(data);
                    $("#stat-product tbody").remove();
                    $("#stat-product thead").remove();
                    $("#stat-product").append(data);
                },
                complete: function(){
                    proces = false;                                                     
                    WaitingBarHide();
                }
            });

        }
        function getManagerStat(){
            $.ajax({
                url: '<?=SITE_URL?>/modules/get_stat_manager.php',
                method: 'POST',
                data:{
                    // managerId:$('#select-filter-product-pr').val(),
                    dateFrom:$('#between-start-mng').val(),
                    dateTo:$('#between-end-mng').val(),
                    sort: $('#select-sort-mng').val()
                },
                beforeSend: function() {
                    WaitingBarShow('Обработка запроса...');
                    proces = true;     
                },
                success: function(data){
                    console.log(data);
                    $("#stat-manager tbody").remove();
                    $("#stat-manager thead").remove();
                    $("#stat-manager").append(data);
                    $("#stat-manager").show();

                    if (period == 'p-month')
                        $('.month-bonus-animation').show();
                    if (period == 'p-week')
                        $('.week-bonus-animation').show();
                },
                complete: function(){
                    proces = false;                                                     
                    WaitingBarHide();
                }
            });

        }

        $('.adSelect').chosen();
        $('.adSelect').change(function(){
            getAdStat();
        });

        $('.prSelect').chosen();
        $('.prSelect').change(function(){
            getProdStat();
        });

        $('.mngSelect').chosen();
        $('.mngSelect').change(function(){
            getManagerStat();
        });
              
        $('#select-filter-advertise').change(function(){
            if ($(this).val() == 'MarketGid')
                $('.marketGidFilers').show();
            else
                $('.marketGidFilers').hide(); 
        });

        $('.marketGidFilers').hide();

        $('.dateInp').datepicker({
            onSelect: function () {
                getAdStat();
            }
        }); 
        $('.prStatDateInp').datepicker({
            onSelect: function () {
                getProdStat();
            }
        }); 

        $('.mngStatDateInp').datepicker({
            onSelect: function () {
                getManagerStat();
            }
        }); 

        function gm(value){
            if (value.toString().length == 1)
                return '0'+value;
            else
                return value;
        }
         

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
                        // if (date('w') == 0)
                        //     $dow = 6;
                        // else
                        //     $dow = date('w')-1;
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

            if (stat == '3'){
                $('#between-start-mng').val(dateStart);
                $('#between-end-mng').val(dateEnd);
                getManagerStat();
            }
            else
            if (stat == '6'){ 
                $('#between-start-pr').val(dateStart);
                $('#between-end-pr').val(dateEnd);
                getProdStat();
            }   
            else
            if (stat == '9'){ 
                $('#between-start-ad').val(dateStart);
                $('#between-end-ad').val(dateEnd);
                getAdStat();
            }
        });

        $selected=document.location.href;
        $.each($("#tabs-statistic a"),function(){
            if(this.href==$selected){
                $(this).addClass('button-success');
            };
        }); 
    
        var dt = new Date();
        var dateStart = dateEnd = '<?=date('Y-m-d')?>';
        if(stat=='3'){
            $('#stat-mng').show();
            $('#button-operation-export-statistic').fadeIn();
            $('#between-start-mng').val(dateStart);
            $('#between-end-mng').val(dateEnd);        
            getManagerStat();
        }else{
            $('#stat-mng').hide();
            $('#button-operation-export-statistic').hide();
        }
        if(stat=='6'){
            $('#stat-pr').show();
            $('#between-start-pr').val(dateStart);
            $('#between-end-pr').val(dateEnd);        
            getProdStat();
        }else{
            $('#stat-pr').hide();
        }
        if(stat=='9'){
            $('#stat-ad').show();
            $('#between-start-ad').val(dateStart);
            $('#between-end-ad').val(dateEnd);        
            getAdStat();
        }else{
            $('#stat-ad').hide();
        }
});
</script>
<div id="tabs-statistic">
    <ul>
        <li><a href="<?=SITE_URL?>/?action=statistic&stat=3" class="button">По сотрудникам</a></li>
        <li><a id="defStatPage" href="<?=SITE_URL?>/?action=statistic&stat=6" class="button">По товару</a></li>
        <li><a href="<?=SITE_URL?>/?action=statistic&stat=9" class="button">По рекламе</a></li>
    </ul>        
</div>
    <div id="print-statistic">
        <div id="stat-mng"  class="stat-container2">
            <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px; margin: 6px 6px -10px 6px;">
                Сортировать по: 
                <select id="select-sort-mng" class="mngSelect">
                    <option value="by-rating">Рейтингу</option>
                    <option value="by-manager">Менеджеру</option>    
                    <option value="by-orderCount">Количеству заказов</option> 
                    <option value="by-cv2">CV2</option> 
                    <option value="by-effective">Допродажам</option> 
                    <!-- <option value="by-not-effective">Не эффективным</option>  -->
                    <option value="by-cv3">% ДП</option>  
                    <option value="by-asc">Количеству допроданных товаров</option> 
                    <option value="by-сsc">Количеству допроданных перекрестных товаров</option>
                    <option value="by-bonus">Бонусу</option>  
                </select><br><br>
                По дате: 
                    с <input type="text" id="between-start-mng" class="mngStatDateInp"size="10"> 
                    по <input type="text" id="between-end-mng" class="mngStatDateInp"size="10">

                &nbsp;
                <button id="p-today" class="p-today button button-period">Cегодня</button>
                <button id="p-yesterday" class="p-yesterday button button-period">Вчера</button>
                <button id="p-week" class="p-week button button-period">Текущая неделя</button>
                <button id="p-month" class="p-month button button-period">Текущий месяц</button>
                <button id="p-all" class="p-all button button-period">За всё время</button>
                <!-- <br><br> -->
                <!-- По товару: 
                <select id="select-filter-product-pr" class="prSelect">
                    <option value="">Все</option>
                    <?php 
                        $spisok_tovarov = getProducts();
                        foreach ($spisok_tovarov as $sp_tov):
                    ?>        
                    <option value="<?=$sp_tov['id']?>"><?=$sp_tov['name']?></option>
                    <?php endforeach; ?>
                </select> -->
            <br>
            </div><br>
            <div style="padding-left: 7px">
                <table class="stat-table" id="stat-manager" border="1" cellpadding="10px" style="text-align: center;">
                </table>
            </div>
        </div> 
    </div>


    <div id="stat-pr"  class="stat-container2">
        <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px; margin: 6px 6px -10px 6px; position: relative;">
            Сортировать по: 
            <select id="select-sort-pr" class="prSelect">
                <option value="by-product">Товару</option>    
                <option value="by-count">Количеству проданного товара</option> 
                <option value="by-orderCount">Количеству заказов</option> 
                <option value="by-cv2">CV2</option>
                <option value="by-nac">N СЧ</option>
                <option value="by-profit">Выручке</option>  
                <option value="by-mac">$ СЧ</option>   
            </select>
            Фильтр по товару: 
            <select id="select-filter-product-pr" class="prSelect">
                <option value="">Все</option>
                <?php 
                    $spisok_tovarov = getProducts();
                    foreach ($spisok_tovarov as $sp_tov):
                ?>        
                <option value="<?=$sp_tov['id']?>"><?=$sp_tov['name']?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            По дате: 
                с <input type="text" id="between-start-pr" class="prStatDateInp" value="<?=$_GET['d_start']?>" size="10"> 
                по <input type="text" id="between-end-pr" class="prStatDateInp" value="<?=$_GET['d_end']?>" size="10">
            &nbsp;
                <button id="p-today" class="button button-period">Cегодня</button>
                <button id="p-yesterday" class="button button-period">Вчера</button>
                <button id="p-week" class="button button-period">Текущая неделя</button>
                <button id="p-month" class="button button-period">Текущий месяц</button>
                <button id="p-all" class="p-all button button-period">За всё время</button>
        </div>
        <br>
        <div style="padding-left: 7px">
            <table id="stat-product" border="1" cellpadding="10px" style="text-align: center;">
            </table>
        </div>
    </div>  

    <div id="stat-ad"  class="stat-container2">
        <div style="background: #F6F6F6; font-family: 'magistral'; padding: 6px 8px; margin: 6px 6px -10px 6px; position: relative;">
            Сортировать по: 
            <select id="select-sort-ad" class="adSelect">
                <option value="by-source">Метке рекламы</option> 
                <option value="by-product">Товару</option>    
                <option value="by-count">Количеству заказов</option>    
            </select>
            По рекламе: 
            <select id="select-filter-advertise" class="adSelect">
                <option value="">Все</option>
                <?php 
                    $list = getAdvertise();
                    foreach ($list['utm_source'] as $value):
                ?>     
                <option value="<?=$value?>"><?=$value?></option>
                <?php endforeach; ?>
            </select>
            &nbsp;
            По товару: 
            <select id="select-filter-product" class="adSelect">
                <option value="">Все</option>
                <?php 
                    $spisok_tovarov = getProducts();
                    foreach ($spisok_tovarov as $sp_tov):
                ?>        
                <option value="<?=$sp_tov['id']?>"><?=$sp_tov['name']?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <div class="marketGidFilers">
                <?php 
                    $list = getMarketGidParam();
                ?>
                <br>
                По кампании: 
                <select id="select-filter-marketgid-term" class="adSelect">
                    <option value="">Все</option>
                    <?php 
                        foreach ($list['utm_term'] as $value):
                    ?>     
                    <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                </select>
                &nbsp;
                По объявлению: 
                <select id="select-filter-marketgid-content" class="adSelect">
                    <option value="">Все</option>
                    <?php 
                        foreach ($list['utm_content'] as $value):
                    ?>    
                     <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                </select>
                &nbsp;
                По ключу: 
                <select id="select-filter-marketgid-campaing" class="adSelect">
                    <option value="">Все</option>
                    <?php 
                        foreach ($list['utm_campaign'] as $value):
                    ?>     
                    <option value="<?=$value?>"><?=$value?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            По дате: 
                с <input type="text" id="between-start-ad" class="dateInp" value="<?=$_GET['d_start']?>" size="10"> 
                по <input type="text" id="between-end-ad" class="dateInp" value="<?=$_GET['d_end']?>" size="10">
            &nbsp;
            <button id="p-today" class="p-today button button-period">Cегодня</button>
            <button id="p-yesterday" class="p-yesterday button button-period">Вчера</button>
            <button id="p-week" class="p-week button button-period">Текущая неделя</button>
            <button id="p-month" class="p-month button button-period">Текущий месяц</button>
            <button id="p-all" class="p-all button button-period">За всё время</button>
        </div>
        <br>
        <div style="padding-left: 7px">
            <table id="stat-advertising" border="1" cellpadding="10px" style="text-align: center;">
            </table>
        </div>
    </div>

</div>
