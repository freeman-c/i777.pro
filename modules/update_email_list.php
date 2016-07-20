<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/controller.php';
//sleep(1);
function getEmailsInEmptyGroupsClients(){
    db_connect();    
    $query = "SELECT * FROM `clients`";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
function getEmailsInGroupsClients($type){
    db_connect();    
    $query = "SELECT * FROM `clients` WHERE type='$type' ORDER BY id DESC";
    $result = mysql_query($query);
    $result = db_result_to_array($result);
    return $result;
}
//***********************************************************************
if(empty($_POST['group_id'])){
    $clients = getEmailsInEmptyGroupsClients();
}else{
    $clients = getEmailsInGroupsClients($_POST['group_id']);
}
//***********************************************************************
$i=0;
foreach ($clients as $client):    
    //if(strlen($client['email']) > 0 && preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $client['email'])){
    if(strlen($client['email']) > 0 && preg_match("/[-a-zA-Z0-9_\.]{3,20}@[-a-zA-Z0-9]{2,64}\.[a-zA-Z\.]{2,9}/", $client['email'])){
        $i++;
?>
<div>
<input type="checkbox" class="sel-mail" name="<?=$client['email']?>" id="<?=$client['id']?>">
<?=$client['email']?>
<span style="color:#ABABAB;"> - <?=$client['name']?></span>
<span style="color:#ABABAB; float: right; margin-right: 4px; font-weight: 100;"> <?=$client['site']?></span>
</div>
<?php } endforeach; ?>