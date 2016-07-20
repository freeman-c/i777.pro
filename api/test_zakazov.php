<?php
// admin.gh1tyuo5.in.ua/api/test_zakazov.php/?name=Test&phone=0638383006
// http://admin.gh1tyuo5.in.ua/api/test_zakazov.php/?name=Test&phone=0638383006&serverName=test&productId=10&price=1000&quantity=1

function sendToCRM($orderId){
    if (!$_REQUEST['quantity'])
        $_REQUEST['quantity'] = 1;

    session_start();
    $data = array(
        'order_id' => $orderId, //(авто)код заказа
        'site' => 'test', //(авто)сайт отправитель заказа
                'product_id' => 1, //код товара
        'price' => 1000, //цена товара
        'count' => 1, //количество товара
        'bayer_name' => $_REQUEST['name'], // покупатель
        'phone' => $_REQUEST['phone'], // телефон
        'email' => $_REQUEST['email'], //электронка
        'comment' => $_REQUEST['comment'], // комментарий
        'total' => $_REQUEST['price'], //сумма цены товара
        'ip' => $_SERVER['REMOTE_ADDR'], //(авто)IP-адрес клиента
        'utm_source' => $_REQUEST['utm_source'],
        'utm_medium' => $_REQUEST['utm_medium'],
        'utm_term' => $_REQUEST['utm_term'],
        'utm_content' => $_REQUEST['utm_content'],
        'utm_campaign' => $_REQUEST['utm_campaign'],
        'subject' => $_REQUEST['subject']
    );

    $send = urlencode(serialize($data));
    echo '<img width="1" src="/api/?data='.$send.'">';
}

$orderId = date('dmy0Gis');
$_REQUEST['phone'] = preg_replace('/[^0-9]/', '', $_REQUEST['phone']);
$_REQUEST['phone'] = '0'.substr($_REQUEST['phone'], -9, 9);

if(empty($_REQUEST['phone'])){
    echo '<h1 style="color:red;">Phone Error</h1>';
    ?>
    <meta http-equiv="refresh" content="2; url=http://<?=$_REQUEST['serverName']?>/utm_source=<?=$_REQUEST['utm_source']?>&utm_medium=<?=$_REQUEST['utm_medium']?>&utm_campaign=<?=$_REQUEST['utm_campaign']?>$?utm_term=<?=$_REQUEST['utm_term']?>">
    <?php
}
else{
	sendToCRM($orderId);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Поздравляем! Ваш заказ принят!</title>
    <style type="text/css">
        body {
            line-height: 1;
            height: 100%;
            font-family: Arial;
            font-size: 15px;
            color: #313e47;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            background: url('bg-ok.png');
        }
        h2 {
            margin: 0;
            padding: 0;
            font-size: 36px;
            line-height: 44px;
            color: #313e47;
            text-align: center;
            font-weight: bold;
        }
        a {
            color: #69B9FF;
        }
        .list_info li span {
            width: 150px;
            display: inline-block;
            font-weight: bold;
            font-style: normal;
        }
        .list_info {
         text-align: left;
         display: inline-block;
         list-style: none;
         margin-top: -10px;
         margin-bottom: -11px;
     }
     .list_info li {
        margin: 11px 0px;
    }
    .fail {
        margin: 10px 0 20px 0px;
        text-align: center;
    }
    .email {
        position: relative;
        text-align: center;
        margin-top: 40px;
    }
    .email input {
        height: 30px;
        width: 200px;
        font-size: 14px;
        padding-right: 10px;
        padding-left: 10px;
        outline: none;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 1px solid #B6B6B6;
        margin-bottom: 10px;
    }
    .block_success {
        max-width: 960px;
        padding: 70px 30px 70px 30px;
        margin: -50px auto;
    }
    .success {
        text-align: center;
    }
    .ng{
        margin-left: 25%;
    }
</style>
</head>
<body>
    <!-- <div class="block_success">
        <h2 style="text-transform: uppercase;">Поздравляем! Ваш заказ принят!</h2>
        <p class="success">
            В ближайшее время с вами свяжется оператор для подтверждения заказа. Пожалуйста, включите ваш контактный телефон.
        </p>
        <h3 class="success">
            Пожалуйста, проверьте правильность введенной Вами информации.
        </h3>
        <div class="success">
            <ul class="list_info">
                <li><span>Ф.И.O.:  </span><span>
                    <?=$_REQUEST['name']?>
                    </span></li>
                    <li><span>Телефон: </span><span>
                        <?=$_REQUEST['phone']?>
                        </span></li>
                    </ul>
                    <br/><span id="submit"></span>
                </div>
                <p class="fail success">Если вы ошиблись при заполнени формы, то, пожалуйста, <a href="javascript: history.back(-1);">заполните заявку еще раз</a></p>
                <p class = "ng"><a href="http://2016.biz.ua/"><img src="vd__.jpg" width="500"
                  alt="Пример"></a></p>

              </div> -->
</body>
</html>

<?php 
}

?>

