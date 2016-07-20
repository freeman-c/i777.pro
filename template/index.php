<?php 
require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/highcharts_theme.php';
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat1.php';

//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat2.php'; 
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat3.php';
//require $_SERVER['DOCUMENT_ROOT'].'/modules/highcharts/stat4.php'; 
?>
<script>
$(document).ready(function(){
	setTimeout(function(){
		$('#stat1').load('/modules/highcharts/stat-index.php');
	},500);
});
</script>
<style>
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
</style>
<table id="table-start" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="2"> 		
            <div style="display:block; height: 400px;">			
                <div id="stat1"><img src="<?=SITE_URL?>/image/loader_big.gif"></div>
            </div>
			
        </td>
    </tr>    
    <!--<tr>
        <td width="60%" valign="top">             
            <div id="stat2-1"></div> <hr>
            <div id="stat3"></div>            
        </td>
        <td width="40%" valign="top"> 
            <div id="stat2-2"></div> <hr>
            <div id="stat4"></div>
        </td>
    </tr>-->    
</table>
<?php 
        $statistika1_statusy = getStatusy();        
        echo '<div id="stat-color-line" style="text-align:center;">';   
        //echo '<h2 style="text-align:center; font-size:19px; color:#900; margin-bottom: 14px;">Все заказы по всем офисам</h2>';
        foreach ($statistika1_statusy as $s1s):    
            echo '<span class="all-stat-zoom" style="background:'.$s1s['color'].';"></span> '.$s1s['name'].' ('.CountStatus($s1s['id']).')';    
        endforeach; 
        echo '</div>';
    ?>
<!--Главная страница Админнистративного блока.
<?php 
    /*session_start();
    echo '<pre>';
    print_r($_SESSION['user']);
    
    echo '<pre>';
    print_r($_SESSION['user']['order']);*/
    
    /*echo ini_get("memory_limit")."\n";
    ini_set("memory_limit","128M");
    echo ini_get("memory_limit")."\n";*/
?>-->
</p>