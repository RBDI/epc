<?
include "config.php";

$ID=$_POST['subitem_id'];
$price=$_POST['price'];

$sql="UPDATE `shop_subitem` SET `value1`='$price' WHERE `ID`='$ID'";
$result = mysql_query($sql) or die(mysql_error());

print '({"subitem_id":"'.$ID.'","price":"'.$price.'"})';
?>