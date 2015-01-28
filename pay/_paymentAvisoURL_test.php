<?
	$filename = "1.txt";
	$fh = fopen($filename, "w");
	
 
foreach ($_GET as $key => $value) {
	$is=fwrite($fh, $key.'->'.$value.' | ');
}
 
foreach ($_POST as $key => $value) {
	$is=fwrite($fh, $key.'->'.$value.' | ');
}
	fclose($fh); 	 
$performedDatetime=date("c");

print '<?xml version="1.0" encoding="UTF-8"?>
<paymentAvisoResponse performedDatetime ="'.$performedDatetime.'" code="0" invoiceId="'.$_POST['invoiceId'].'" shopId="'.$_POST['shopId'].'"/>';
?>