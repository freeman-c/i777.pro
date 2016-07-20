<?php 

require $_SERVER['DOCUMENT_ROOT'].'/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/system/db.php';
require $_SERVER['DOCUMENT_ROOT'].'/modules/npapi2/npapi2.php';
$key_api = API2_NP;

$np = new LisDev\Delivery\NovaPoshtaApi2(API2_NP);

$data = array(
 'apiKey' => $key_api,
 'modelName' => 'Address',
 'calledMethod' => 'getCities',
 'language' => 'ru',
 'methodProperties' => array(
		'Page' => 0,
		'FindByString' => '',
		'Ref' => '',
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

db_connect();

$response = json_decode($response, true);
$cc = 0;
$wc = 0;
mysql_query("TRUNCATE TABLE np_city") or die(mysql_error());
mysql_query("TRUNCATE TABLE np_warehouse") or die(mysql_error());
?>
<table border="1" style="border-collapse: collapse;">
<tbody>
<?php
foreach ($response['data'] as $key => $value) {
	$cc++;
	mysql_query("INSERT INTO np_city SET ref = '{$value["Ref"]}', name = '{$value["DescriptionRu"]}', area = '{$value["Area"]}'") or die(mysql_error());

	$data2 = array(
	 'apiKey' => $key_api,
	 'modelName' => 'Address',
	 'calledMethod' => 'getWarehouses',
	 'language' => 'ru',
	 'methodProperties' => array(
	        'CityRef' => $value["Ref"]
	    )
	);
	$json2 = json_encode($data2);
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_HTTPHEADER, Array("Content-Type: application/json"));
	curl_setopt($ch2, CURLOPT_HEADER, 0);
	curl_setopt($ch2, CURLOPT_POSTFIELDS, $json2);
	curl_setopt($ch2, CURLOPT_POST, 1);
	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
	$response2 = curl_exec($ch2);
	curl_close($ch2);
	$response2 = json_decode($response2, true);

	foreach ($response2['data'] as $key2 => $value2) {
		$wc++;
		mysql_query("INSERT INTO np_warehouse SET ref = '{$value2["Ref"]}', name = '{$value2["DescriptionRu"]}', np_city_ref = '{$value["Ref"]}'") or die(mysql_error());
		// echo $value2["Ref"].$value2['DescriptionRu'].'<br>';
		?>
		<tr>
			<td>
				<?=$value["DescriptionRu"]?>
			</td>
			<td>
				<?=$value["Ref"]?>
			</td>
			<td>
				<?=$value2["DescriptionRu"]?>
			</td>
			<td>
				<?=$value2["Ref"]?>
			</td>
		</tr>
		<?php

	}
	/*if ($cc == 100)
		break;*/
}

?>
</tbody>
</table>
<?php
echo "По состоянию на {date('H:i:s d-m-Y')} насчитываеться {$wc} отделений в {$cc} городов";

?>