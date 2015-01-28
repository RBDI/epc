<?
include "../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
$db2 = mysql_select_db(DB_NAME, $db1);
mysql_query('SET NAMES utf8');

$order_id=$_POST['orderNumber'];
$sum=$_POST['orderSumAmount'];
$invoiceId=$_POST['invoiceId'];

if ($order_id) {
	$pay_time=date("Y-m-d H:i:s"); 
	$sql="UPDATE `shop_users` SET `payment`='$sum',`invoiceId`='$invoiceId',`payment_status`='3',`pay_time`='$pay_time' WHERE `ID`='$order_id'";
	$result = mysql_query($sql) or die(mysql_error());
}
$performedDatetime=date("c");

print '<?xml version="1.0" encoding="UTF-8"?>
<paymentAvisoResponse performedDatetime ="'.$performedDatetime.'" code="0" invoiceId="'.$_POST['invoiceId'].'" shopId="'.$_POST['shopId'].'"/>';
?>