<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(3);
$op = $_POST['op'];
switch ($op){
    case('get_days'): 
			function count_logs_day($date){
				db_connect();
				$query = "SELECT COUNT(*) FROM `logs` WHERE date='$date'";
				$result = mysql_query($query);
				$sum = mysql_fetch_array($result);
				return $sum[0]; 
			}	
			
            $days = cal_days_in_month(CAL_GREGORIAN, $_POST['m'], $_POST['y']);

            $week = array('Mon'=>"Пн",'Tue'=>"Вт",'Wed'=>"Ср",'Thu'=>"Чт",'Fri'=>"Пт",'Sat'=>"Сб",'Sun'=>"Вс");

            for($d=1; $d<=$days; $d++){
			
                $date = $_POST['y'].'-'.$_POST['m'].'-'.$d;
                $w = $week[strftime("%a",strtotime($date))];
				
				$count_logs_day = count_logs_day($date);
				if($count_logs_day > 0){$no_rows = '';}else{$no_rows = ' no-rows';}
				
                if($w=='Вс'){$margin='margin-right:4px;';}else{$margin='';}
                //if($d==date('d')){
            ?>
            <div class="logs-day-button<?=$no_rows?>" id="<?=$date;?>" onclick="get_history_rows('<?=$date;?>',event);" style="<?=$margin;?>">
                <?php if($w=='Сб' || $w=='Вс'){echo '<span style="color:red;">'.$w.'</span>';}else{echo '<span>'.$w.'</span>';}?><br><?=$d;?></div>    
            <?php 
            /*} else{
            ?>
            <div class="logs-day-button" onclick="get_history_by_day(event);" style="<?=$margin;?>">
                <?php if($w=='Сб' || $w=='Вс'){echo '<span style="color:red;">'.$w.'</span>';}else{echo '<span>'.$w.'</span>';}?><br><?=$d;?></div>
            <?php
            }*/
            }
    break;

    case('get_logs'):
        db_connect();
        $logs = getLogs($_POST['date'], $_POST['searchingString']);
        if($logs){
			$count_rows = 0;
            foreach($logs as $log):
                $count_rows++;
                if($log['type']=='0'){
                    $type = 'error';}
                else
                    $type = '';

                ?>    
                <div class="<?=$type?>" style="text-align:left; padding:1px 2px; line-height: normal;">            
                        <?php if($log['type']=='0'){ ?>
                        <img src="<?=SITE_URL?>/image/opera/transfer_failure.png">    
                        <?php }else{ ?>
                        <img src="<?=SITE_URL?>/image/opera/menu_info.png">
                        <?php } ?>
                        <span style="color: #ABABAB; width: 80px; display: inline-block;"><?=$log['ip']?></span>
                        <span style="color: #454545;"> 
                            [<?=date("d.m.Y", strtotime($log['datetime']))?> <?=date("H:i:s", strtotime($log['datetime']))?>] 
                        </span>
                        <span style="color: #ABABAB; border: 1px solid transparent; padding: 0px 1px;">
            <?php 
                switch ($log['os']){
                    case('iPhone'):
                        $os = '<img src="'.SITE_URL.'/image/os/iphone.ico" title="'.$log['os'].'">';
                    break;
                    case('Windows 98'):
                        $os = '<img src="'.SITE_URL.'/image/os/win.ico" title="'.$log['os'].'">';
                    break;
                    case('Windows XP'):
                        $os = '<img src="'.SITE_URL.'/image/os/xp.ico" title="'.$log['os'].'">';
                    break;
                    case('Windows 2003'):
                        $os = '<img src="'.SITE_URL.'/image/os/win.ico" title="'.$log['os'].'">';
                    break;
                    case('Windows Vista'):
                        $os = '<img src="'.SITE_URL.'/image/os/vista.ico" title="'.$log['os'].'">';
                    break;
                    case('Windows 7'):
                        $os = '<img src="'.SITE_URL.'/image/os/win7.ico" title="'.$log['os'].'">';
                    break;
                    case('Open BSD'):
                        $os = '<img src="'.SITE_URL.'/image/os/openbsd.ico" title="'.$log['os'].'">';
                    break;
                    case('Sun OS'):
                        $os = '<img src="'.SITE_URL.'/image/os/sunos.ico" title="'.$log['os'].'">';
                    break;
                    case('Linux'):
                        $os = '<img src="'.SITE_URL.'/image/os/linux.ico" title="'.$log['os'].'">';
                    break;
                    case('Safari'):
                        $os = '<img src="'.SITE_URL.'/image/os/safari.ico" title="'.$log['os'].'">';
                    break;
                    case('Macintosh'):
                        $os = '<img src="'.SITE_URL.'/image/os/mac.ico" title="'.$log['os'].'">';
                    break;
                    case('QNX'):
                        $os = '<img src="'.SITE_URL.'/image/os/qnx.ico" title="'.$log['os'].'">';
                    break;
                    case('BeOS'):
                        $os = '<img src="'.SITE_URL.'/image/os/beos.ico" title="'.$log['os'].'">';
                    break;
                    case('OS/2'):
                        $os = '<img src="'.SITE_URL.'/image/os/o2.ico" title="'.$log['os'].'">';
                    break;
                    case('Search Bot'):
                        $os = '<img src="'.SITE_URL.'/image/os/bot.ico" title="'.$log['os'].'">';
                    break;
                }
            ?>                
                        <?php echo $os; //$log['os']?>
                        </span>
                        <span style="color: #3370A6;">              
            <?php 
                if (strpos($log['browser'],'MSIE') !== false) {
                    $ie = '<img src="'.SITE_URL.'/image/browser/ie.ico" title="'.$log['browser'].'">'; echo $ie;
                }  
                if (strpos($log['browser'],'Opera') !== false) {
                    $opera = '<img src="'.SITE_URL.'/image/browser/opera.ico" title="'.$log['browser'].'">'; echo $opera;
                }
                if (strpos($log['browser'],'Firefox') !== false) {
                    $firefox = '<img src="'.SITE_URL.'/image/browser/firefox.ico" title="'.$log['browser'].'">'; echo $firefox;
                }
                if (strpos($log['browser'],'Chrome') !== false) {
                    $chrome = '<img src="'.SITE_URL.'/image/browser/chrome.ico" title="'.$log['browser'].'">'; echo $chrome;
                }
                if (strpos($log['browser'],'Safari') !== false) {
                    $safari = '<img src="'.SITE_URL.'/image/browser/safari.ico" title="'.$log['browser'].'">'; echo $safari;
                }
                if (strpos($log['browser'],'Browser based on Gecko') !== false) {
                    $gecko = '<img src="'.SITE_URL.'/image/browser/firefox.ico" title="'.$log['browser'].'">'; echo $gecko;
                }
                //echo $browser; //$log['browser']
            ?>
                        </span>
                        <img src="<?=SITE_URL?>/image/opera/panel_collapse_right.png">
                        <?php 
            $user_info = get_user_description_login($log['user']);
            $message = str_replace('{user}', '<span class="login">'.$log['user'].'</span> (<span class="imya">'.$user_info['surname'].' '.$user_info['name'].' '.$user_info['lastname'].'</span>)', $log['text']); 
                        ?>
                        <span style="color: #757575;"><?=$message?></span>
                </div>
        <?php        
            endforeach;
			echo '<input type="hidden" id="count_rows_input" value="'.$count_rows.'">';
        }else{
            echo 'Ничего не найдено';
        }
    break;
}


?>