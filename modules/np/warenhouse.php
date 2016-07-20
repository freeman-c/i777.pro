<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/npapi2/npapi2.php';
$key_api = API2_NP;

$np = new LisDev\Delivery\NovaPoshtaApi2(API2_NP);
$city = $np->getCity($_POST['city']);

$data = array(
 'apiKey' => $key_api,
 'modelName' => 'Address',
 'calledMethod' => 'getWarehouses',
 'language' => 'ru',
 'methodProperties' => array(
        'CityRef' => $city['data'][0]['Ref']
    )
);
$json = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response, true);
?>
<table width="100%" id="table-warenhouse-list" cellspacing="0">
    <thead>
        <tr>
            <td>Адрес</td>
            <td>Телефон</td>
            <td>Тип отделения</td>
        </tr>    
    </thead>
    <tbody>
    <?php
    foreach ($response['data'] as $key => $value){
    ?>  
        <tr>
            <td><?=$value['DescriptionRu']?></td>
            <td><?=$value['Phone']?></td>
            <?php
            if($value['TotalMaxWeightAllowed'] != 0){?>
                <td style="color:#900;">● До <?=$value['TotalMaxWeightAllowed']?> кг</td>
            <?php } else { ?>
                <td style="color:green;">● Без ограничений</td>
            <?php } ?>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>