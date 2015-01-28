<?
function create_xml_body (){

$existing_store_uuid='3201a977-d8fe-11e3-3ef5-002590a28eca';
$existing_counterparty_uuid='977382ab-da94-11e3-ec15-002590a28eca';
$existing_organization_uuid='3200d6c6-d8fe-11e3-d303-002590a28eca';
$existing_good_uuid='c00c5835-d8ff-11e3-6af1-002590a28eca';
$order_id='5555';
$now='2011-06-27T06:27:00+04:00';
return '<?xml version="1.0" encoding="UTF-8"?>
 
<customerOrder vatIncluded="true" applicable="true" sourceStoreUuid="'.$existing_store_uuid.'" 
    payerVat="true" sourceAgentUuid="'.$existing_counterparty_uuid.'" targetAgentUuid="'.$existing_organization_uuid.'" 
    moment="'.$now.'" name="'.$order_id.'">
<customerOrderPosition vat="18" goodUuid="'.$existing_good_uuid.'" quantity="4.0" discount="0.0">
<basePrice sumInCurrency="55000.0" sum="55000.0"/>
 
<reserve>0.0</reserve>
</customerOrderPosition>
</customerOrder>';
 
}


$body = create_xml_body();
 
$sock = fsockopen("ssl://online.moysklad.ru", 443, $errno, $errstr, 30);
 
if (!$sock) die("$errstr ($errno)\n");
 
fputs($sock, "PUT /exchange/rest/ms/xml/CustomerOrder HTTP/1.1\r\n");
fputs($sock, "Host: online.moysklad.ru\r\n");
fputs($sock, "Authorization: Basic " . base64_encode("admin@epc:4e65c2db1c") . "\r\n");
fputs($sock, "Content-Type: application/xml \r\n");
fputs($sock, "Accept: */*\r\n");
fputs($sock, "Content-Length: ".strlen($body)."\r\n");
fputs($sock, "Connection: close\r\n\r\n");
fputs($sock, "$body");
 
while ($str = trim(fgets($sock, 4096)));
 
$body = "";
 
while (!feof($sock))
    $body.= fgets($sock, 4096);
 
fclose($sock);
 
echo $body;
?>