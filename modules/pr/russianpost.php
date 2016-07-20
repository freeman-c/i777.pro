<?php
$russianpostRequiredExtensions = array('SimpleXML', 'curl', 'pcre');
foreach($russianpostRequiredExtensions as $russianpostExt) {
  if (!extension_loaded($russianpostExt)) {
    throw new RussianPostSystemException('Требуемое расширение ' . $russianpostExt . ' отсутствует');
  }
}
class RussianPostAPI {
  /* SOAP service URL */
  const SOAPEndpoint = 'http://voh.russianpost.ru:8080/niips-operationhistory-web/OperationHistory?wsdl';
  protected $proxyHost;
  protected $proxyPort;
  protected $proxyAuthUser;
  protected $proxyAuthPassword;
  
  public function __construct($proxyHost = "", $proxyPort = "", $proxyAuthUser = "Chepurnoy533", $proxyAuthPassword = "cfQsbzXZSX") {
    $this->proxyHost         = $proxyHost;
    $this->proxyPort         = $proxyPort;
    $this->proxyAuthUser     = $proxyAuthUser;
    $this->proxyAuthPassword = $proxyAuthPassword;
  }
  public function getOperationHistory($trackingNumber) {
    $trackingNumber = trim($trackingNumber);
    if (!preg_match('/^[0-9]{14}|[A-Z]{2}[0-9]{9}[A-Z]{2}$/', $trackingNumber)) {
      throw new RussianPostArgumentException('Неверный формат отслеживая номера: ' . $trackingNumber);
    }
    $data = $this->makeRequest($trackingNumber);
    $data = $this->parseResponse($data);
    return $data;
  }
  protected function parseResponse($raw) {
    $xml = @simplexml_load_string($raw);    
    if (!is_object($xml))
      throw new RussianPostDataException("Не удалось разобрать ответ XML");
    $ns = $xml->getNamespaces(true);
    foreach($ns as $key => $dummy) {
      if (strpos($key, 'ns') === 0) {
        $nsKey = $key;
        break;
      }
    }
    if (empty($nsKey)) {
      throw new RussianPostDataException("Не удалось обнаружить правильные имена в ответе XML");
    }
    if (!(
                 $xml->children($ns['S'])->Body &&
      $records = $xml->children($ns['S'])->Body->children($ns[$nsKey])->OperationHistoryData->historyRecord
    ))
      throw new RussianPostDataException("Нет отслеживания данных в ответе XML");
    $out = array();
    foreach($records as $rec) {
      $outRecord = new RussianPostTrackingRecord();
      $outRecord->operationType            = (string) $rec->OperationParameters->OperType->Name;
      $outRecord->operationTypeId          = (int) $rec->OperationParameters->OperType->Id;      
      $outRecord->operationAttribute       = (string) $rec->OperationParameters->OperAttr->Name;
      $outRecord->operationAttributeId     = (int) $rec->OperationParameters->OperAttr->Id;      
      $outRecord->operationPlacePostalCode = (string) $rec->AddressParameters->OperationAddress->Index;
      $outRecord->operationPlaceName       = (string) $rec->AddressParameters->OperationAddress->Description;
      $outRecord->destinationPostalCode    = (string) $rec->AddressParameters->DestinationAddress->Index;
      $outRecord->destinationAddress       = (string) $rec->AddressParameters->DestinationAddress->Description;
      $outRecord->operationDate            = (string) $rec->OperationParameters->OperDate;
      //$outRecord->itemWeight               = round(floatval($rec->ItemParameters->Mass) / 1000, 3);
      //$outRecord->declaredValue            = round(floatval($rec->FinanceParameters->Value) / 100, 2);
      //$outRecord->collectOnDeliveryPrice   = round(floatval($rec->FinanceParameters->Payment) / 100, 2);
      $outRecord->itemWeight               = round(floatval($rec->ItemParameters->Mass) / 1000, 3);
      $outRecord->declaredValue            = floatval($rec->FinanceParameters->Value);
      $outRecord->collectOnDeliveryPrice   = floatval($rec->FinanceParameters->Payment);
      
      $out[] = $outRecord;
    }
    return $out;
  }
  protected function makeRequest($trackingNumber) {
    $channel = curl_init(self::SOAPEndpoint);
    $data = <<<EOD
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
   <s:Header/>
   <s:Body xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
       <OperationHistoryRequest xmlns="http://russianpost.org/operationhistory/data">
           <Barcode>$trackingNumber</Barcode>
           <MessageType>0</MessageType>
       </OperationHistoryRequest>
   </s:Body>
</s:Envelope>
EOD;

    curl_setopt_array($channel, array(
      CURLOPT_POST           => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_TIMEOUT        => 10,
      CURLOPT_POSTFIELDS     => $data,
      CURLOPT_HTTPHEADER     => array(
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: ""',
      ),
    ));
    if (!empty($this->proxyHost) && !empty($this->proxyPort)) {
      curl_setopt($channel, CURLOPT_PROXY, $this->proxyHost . ':' . $this->proxyPort);
    }
    if (!empty($this->proxyAuthUser)) {
      curl_setopt($channel, CURLOPT_PROXYUSERPWD, $this->proxyAuthUser . ':' . $this->proxyAuthPassword);
    }
    $result = curl_exec($channel);
    if ($errorCode = curl_errno($channel)) {
      throw new RussianPostChannelException(curl_error($channel), $errorCode);
    }
    return $result;    
  }
}
class RussianPostTrackingRecord {
  public $operationType;
  public $operationTypeId;
  public $operationAttribute;
  public $operationAttributeId;
  public $operationPlacePostalCode;
  public $operationPlaceName;
  public $operationDate;
  public $itemWeight;
  public $declaredValue;
  public $collectOnDeliveryPrice;
  public $destinationPostalCode;
  public $destinationAddress;
}
class RussianPostException         extends Exception { }
class RussianPostArgumentException extends RussianPostException { }
class RussianPostSystemException   extends RussianPostException { }
class RussianPostChannelException  extends RussianPostException { }
class RussianPostDataException     extends RussianPostException { }