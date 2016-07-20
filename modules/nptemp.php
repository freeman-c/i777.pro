<?php
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
$key_api = API2_NP;
$data = array(
 'apiKey' => $key_api,
 'modelName' => 'Counterparty',
 'methodProperties' => array(
        "CityRef" => "db5c88f0-391c-11dd-90d9-001a92567626",
        "FirstName" => "Діана",
        "MiddleName" => "Леонідівна",
        "LastName" => "Шевчук",
        "Phone" => "0937266071",
        "Email" => "",
        "CounterpartyType" => "PrivatePerson",
        "CounterpartyProperty" => "Sender"
    )
);

$json = json_encode($data);
print_r($json);

$json = "{
    \"apiKey\": \"".$key_api."\",
    \"modelName\": \"Counterparty\",
    \"calledMethod\": \"save\",
    \"methodProperties\": {
        \"CityRef\": \"db5c88f0-391c-11dd-90d9-001a92567626\",
        \"FirstName\": \"Діана\",
        \"MiddleName\": \"Леонідівна\",
        \"LastName\": \"Шевчук\",
        \"Phone\": \"0937266071\",
        \"Email\": \"\",
        \"CounterpartyType\": \"PrivatePerson\",
        \"CounterpartyProperty\": \"Sender\"
    }
}";

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
print_r($response);
?>