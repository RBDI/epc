<?php
	$filename = "2.txt";
	$fh = fopen($filename, "w");
	
$is=fwrite($fh, 'GET ');
foreach ($_GET as $key => $value) {
	$is=fwrite($fh, $key.'->'.$value.' | ');
}
$is=fwrite($fh, 'POST ');
foreach ($_POST as $key => $value) {
	$is=fwrite($fh, $key.'->'.$value.' | ');
}
	fclose($fh); 	 

$performedDatetime=date("c");
print '<?xml version="1.0" encoding="UTF-8"?>
<checkOrderResponse performedDatetime="'.$performedDatetime.'" code="0" invoiceId="'.$_POST['invoiceId'].'" shopId="'.$_POST['shopId'].'"/>';
?>