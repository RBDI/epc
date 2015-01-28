<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');

$sql="SELECT ID, price FROM shop_catalog";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['ID']]=$row;
}
$i=0;
foreach ($items as $ID => $item) {
	$price=$item['price'];
	$sql="INSERT INTO `shop_subitem` (`item_id`,`value1`) VALUES ('$ID','$price')";	
	// $result = mysql_query($sql) or die(mysql_error());
	// print $sql.' ';
	print $i.' - '.$ID. ' / ';
	$i++;
}

?>