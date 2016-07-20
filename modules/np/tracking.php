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
$doc = new SimpleXMLElement($response);
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


echo '<table width="100%" id="ajax-result-np-table">
        <tr>
            <td class="td-grey">Експрес накладна:</td>
            <td>'.$doc->document->attributes()->number.'</td>
        </tr>
        <tr>
            <td class="td-grey">Ім’я Одержувача відправлення:</td>
            <td>'.$recipient_full_name.'</td>
        </tr>
        <tr>
            <td class="td-grey">Дата та час одержання відправлення:</td>
            <td>'.$delivery_date.'</td>
        </tr>
        <tr>
            <td class="td-grey">Статус:</td>
            <td><b>'.$doc->document->attributes()->status.'</b></td>
        </tr>
        <tr>
            <td class="td-grey">Причина:</td>
            <td>('.$doc->document->attributes()->state_id.')<i> '.$reason.'</i></td>
        </tr>
        <tr>
            <td class="td-grey">Початкова вага відправлення:</td>
            <td>'.$doc->document->attributes()->Weight.' кг</td>
        </tr>
        <tr>
            <td class="td-grey">Початкова вартість доставки відправлення:</td>
            <td>'.$doc->document->attributes()->Sum.' грн</td>
        </tr>
        <tr>
            <td class="td-grey">Сума оплати по ЕН:</td>
            <td>'.$doc->document->attributes()->isEWPaid.' грн</td>
        </tr>
        <tr>
            <td class="td-grey">Сума оплати готівкою:</td>
            <td><b>'.$doc->document->attributes()->EWPaidSum.'</b> грн</td>
        </tr>
      </table>';
if(isset($doc->document->document)){
echo '<div style="font-size:18px; font-weight: bold; padding:2px 10px; color:#FF392E; text-align: center;">↓</div>';
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