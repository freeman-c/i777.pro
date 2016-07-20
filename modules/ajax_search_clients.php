<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(2);
function AjaxSearchClients(){
    db_connect();
    $sort = ""; 
    $between = "";
    //if($_GET['typ']){ $sort = "AND type='".$_GET['typ']."'";}
    if($_GET['type']){ 
        $type = $_GET['type'];        
    }else{
        $type = 1;        
    } 
    if($_GET['complete']){ $sort = "AND date_complete > 0";}
    if($_GET['between']){ $between = "AND date_update >= '".$_GET['d_start']."' AND date_update <= '".$_GET['d_end']."'";} // AND date_update >= '2014-06-13' AND date_update <= '2014-06-15'
    
    $search = $_GET['search'];
    $query = "SELECT * FROM `clients` 
                    WHERE type='".$type."'
                AND `name` LIKE '%$search%'
                OR `phone` LIKE '%$search%'
                OR `email` LIKE '%$search%'
                OR `ip` LIKE '%$search%'
                OR `site` LIKE '%$search%'
                OR `description` LIKE '%$search%'    
                AND status='1' $sort $between ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
$clients = AjaxSearchClients(); 

if($clients){

foreach ($clients as $client):
?>
    <tr style="color:<?=$bad;?>">
        <td> <input type="checkbox" class="selected" name="need_delete[<?=$client['id']?>]" id="checkbox<?=$client['id']?>" id="<?=$client['id']?>"> </td>
        <td>
            <span class="client-icon"></span>
            <?=$client['name']?>
        </td>
        <td>
            <?php if(strlen($client['phone']) < 10 or strlen($client['phone']) > 13){ ?>
            <img src="<?=SITE_URL?>/image/error.ico" style="margin: 0px 2px -4px 0px;"><span style="color:red;"><?=$client['phone']?></span>
            <?php }else{?>
                <img src="<?=SITE_URL?>/image/mobile_phone_arrow.ico" onclick="SendSMS('<?=trim($client['phone'])?>');" class="send-sms-icon"><?=$client['phone']?>
            <?php }?>
        </td>
        <td>
        <?php $group = GetClientsGroup($client['type']); ?>
            <?php if($client['type']==2){ ?>
                <img src="<?=SITE_URL?>/image/arrow_large_right.ico" style="margin: 0px 0px -3px 0px;">
                <b style="color:green;"><?=$group['name'];?></b>
            <?php }elseif($client['type']==3){ ?>
                <b style="color:#C60;"><?=$group['name'];?></b>  
			<?php }elseif($client['type']==4){ ?>
                * <b><?=$group['name'];?></b> *			
            <?php }else{ ?>
                + <?=$group['name'];?> +
            <?php } ?>
        </td>
        <td>
            <?php if($client['email']){ ?>            
            <span style="color:#757575; font-size: 11px;">
                <?php //if(preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $client['email'])){ ?>
                <?php if(preg_match("/[-a-zA-Z0-9_\.]{3,20}@[-a-zA-Z0-9]{2,64}\.[a-zA-Z\.]{2,9}/", $client['email'])){ ?>
                <img src="<?=SITE_URL?>/image/email_go.ico" onclick="SendMail('<?=$client['name']?>','<?=$client['email']?>');" class="send-email-icon">
                <?=$client['email']?>
                <?php }else{?>
                <img src="<?=SITE_URL?>/image/error.ico" style="margin: 0px 0px -4px 0px;"> <span style="color:red;"><?=$client['email']?></span>
                <?php }?>
            </span>
            <?php }else{ ?>
            <div style="color:#ABABAB; text-align:center;">- нет данных -</div>
            <?php } ?>
        </td>
        <td>
            <?php if($client['description']){ ?>
            <span style="color:#757575; font-size: 12px;"><?=$client['description']?></span>
            <?php }else{ ?>
            <span style="color:#ABABAB;">-</span>
            <?php } ?>
        </td>
        <td style="color:#ABABAB; font-size: 11px;"><?=$client['ip']?></td>
        <td style="color:#ABABAB; font-size: 11px;">
                    <?php if(strlen($client['site'])>0){
                        echo '<span style="color:#757575;">'.$client['site'].'</span>';
                    }else{echo '- добавлен вручную -';}?>
        </td>
        <td>
            <img src="<?=SITE_URL?>/image/edit.png" class="option-button" onclick="edit_client('<?=$client['id']?>');">
            <!--<img src="<?=SITE_URL?>/image/del.png" class="option-button">-->
        </td>
    </tr>
    
<?php endforeach; } else{echo '<tr><td colspan="19" align="center"><h3>По запросу "'.$_GET['search'].'" не найдено данных!</h3></td></tr>';}
?>