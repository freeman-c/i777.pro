<?php
if($_POST['submit']){    
//echo $_POST['name'].'<br>';
//echo $_POST['phone'].'<br>';
//echo $_POST['site'].'<br>';
////echo $_POST['email'].'<br>';
//************************** SEND DATA to CRM **************************
$data = array(
        'order_id' => ''.date('dmy0Gis').'', //(авто)код заказа
	'site' => $_POST['site'], //(авто)сайт отправитель заказа
        'product_id' => $_POST['product_id'], //код товара
	//'product' => $_POST['pokupka_product_name'], //название товара
	'price' => $_POST['product_price'], //цена товара
	'count' => $_POST['pokupka_count'], //количество товара
	'total' => $_POST['pokupka_price'], //сумма цены товара
	'bayer_name' => $_POST['name'], // покупатель
	'phone' => $_POST['phone'], // телефон
	'email' => $_POST['email'], //электронка
	'comment' => $_POST['comment'], // комментарий
	'ip' => $_SERVER['REMOTE_ADDR'] //(авто)IP-адрес клиента	
);
$send = urlencode(serialize($data));
echo '<img width="1" src="http://lp-crm.biz/api/?data='.$send.'">';
//************************** SEND DATA to CRM ***************************    
}
?>
<style>
    input{
        margin: 4px;
    }
</style>
<?php 
$site_n = rand(1,8);
if($site_n==1){$site = 'zerosmoke-original.com';}
elseif ($site_n==2) {$site = 'riddex.org.ua';}
elseif ($site_n==3) {$site = 'anti-hrap.fishka1.com';}
elseif ($site_n==4) {$site = 'powerbalance.fishka1.com';}
elseif ($site_n==5) {$site = 'videoregistrator.fishka1.com';}
elseif ($site_n==6) {$site = 'multivarka.fishka1.com';}
elseif ($site_n==7) {$site = 'opt-centr.com';}
elseif ($site_n==8) {$site = 'bigsell.com.ua';}
else{$site = '';}

$rand_cod = rand(1,7);
if($rand_cod==1){$cod = '050';}
elseif ($rand_cod==2) {$cod = '066';}
elseif ($rand_cod==3) {$cod = '095';}
elseif ($rand_cod==4) {$cod = '099';}
elseif ($rand_cod==5) {$cod = '067';}
elseif ($rand_cod==6) {$cod = '096';}
elseif ($rand_cod==7) {$cod = '098';}
else{$cod = '';}
$phone = rand(0000001,99999998);

$t = "abcdefghijklmnopqrstuxyvwz";
$validCharNumber = strlen($t);
$symb = mt_rand(0, $validCharNumber-1);
$randomCharacter = $t[$symb].$t[$symb-7].$t[$symb-15].$t[$symb-21].$t[$symb-3].$t[$symb+$rand_cod].$t[$symb-$rand_cod];

//echo $randomCharacter.$site_n.$rand_cod.'';
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
    Имя:<input type="text" name="name" size="24"><br>
    Телефон:<input type="text" size="10" name="phone" value="<?=$cod?><?=$phone?>"><br>
    Сайт:<input type="text" name="site" value="<?=$site;?>"><br>
    Email:<input type="text" name="email" value="<?=$randomCharacter.$site_n.$rand_cod;?>@mail.com"><br>
    <input type="submit" name="submit" value="Отправить">
</form>