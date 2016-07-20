<?php
error_reporting(0);
require $_SERVER['DOCUMENT_ROOT'].'/config.php';
$key_api = API_NP;
$xml = '<?xml version="1.0" encoding="UTF-8"?>
        <file>
            <auth>'.$key_api.'</auth>
            <tracking>
               <barcode>'.$_POST['ttn'].'</barcode>
            </tracking>
        </file>';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://orders.novaposhta.ua/xml.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
if($response){
    curl_close($ch);
    $doc = new SimpleXMLElement($response);

    echo $doc->document->attributes()->status;
}else{
    echo '<span style="color:#900; padding:0px 3px 1px; border:1px dashed #900; border-radius:3px; background:#FFE0E0;">error connection!</span>';
}


?>