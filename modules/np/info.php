<?php
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
curl_close($ch);
$doc = new SimpleXMLElement($response) or die('Ошибка в строке SimpleXMLElement');
//echo '<pre>';
//print_r($doc);


//if($doc->document->document->attributes()->status){ $goback = $doc->document->document->attributes()->status; }else{$goback='';}
if(isset($doc->document->attributes()->rejectionReason)){ 
            $reason = $doc->document->attributes()->rejectionReason;     
}else{  $reason=='';}
if(isset($doc->document->attributes()->recipient_full_name)){ 
            $recipient_full_name = $doc->document->attributes()->recipient_full_name;     
}else{  $recipient_full_name=='';}
if(isset($doc->document->attributes()->delivery_date)){ 
            $delivery_date = $doc->document->attributes()->delivery_date;     
}else{  $delivery_date=='';}


echo 'Дата одержання: '.$delivery_date.' <span class="sno-np"> ● </span> 
        Статус: <b>'.$doc->document->attributes()->status.'</b> <span class="sno-np"> ● </span>  
        Причина: ('.$doc->document->attributes()->state_id.')<i> '.$reason.'</i> <span class="sno-np"> ● </span>  
            Сума оплати: <b>'.$doc->document->attributes()->EWPaidSum.'</b> грн <br>';

if(isset($doc->document->document)){
echo '<span style="font-size:18px; font-weight: bold; color:#FF392E;">→ </span>';
echo '<b>'.$doc->document->document->attributes()->status.'</b> (TTH № '.$doc->document->document->attributes()->number.')';
}

/*foreach ($doc->document->attributes as $place){
    //echo $place->number.') ';
    //echo $place->city;    
    echo $place->number.'<br>';
    echo $place->status.'<br>';
    //echo 'тел. '.$place->phone.'<br>';
    //echo 'График работы: '.$place->addressRu.'<br>';
    echo '<hr>';
}*/
?>