<?php 
function getCountryByIp($ipAddress){
    $ipDetail=array();
    $f = file_get_contents("http://api.hostip.info/?ip=".$ipAddress);     
    //Получаем название города
    preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $f, $city);
    $ipDetail['city'] = $city[2];      
    //Получаем название страны
    preg_match("@<countryName>(.*?)</countryName>@si", $f, $country);
    $ipDetail['country'] = $country[1];     
    //Получаем код страны
    preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $f, $countryCode);
    $ipDetail['countryCode'] = $countryCode[1];     
    return $ipDetail;
}
/*
$ipDetail = getCountryByIp('195.114.140.105');
echo "Страна: ".$ipDetail['country']."<br />";
echo "Город: ".$ipDetail['city']."<br />";
echo "Код страны: ".$ipDetail['countryCode']."<br />";
*/
?>