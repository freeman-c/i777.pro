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

foreach ($response['data'] as $key => $value) {
	echo '<option value="'.str_replace('"', '&quot;', $value['DescriptionRu']).'">'.$value['DescriptionRu'].'</option>';
}
?>