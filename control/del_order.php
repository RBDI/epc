<?
include "config.php";

$delid=$_POST['delorderid'];

$query = "DELETE FROM `shop_orders` WHERE `ID`='$delid'";
mysql_query($query) or die(mysql_error());	  
print $delid;
?>