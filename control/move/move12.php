<?
	include_once "../../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');		


	$sql="SELECT ID FROM `shop_catalog` WHERE type=313 OR type=320 OR type=326 OR type=348";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		$ids[]=$row['ID'];
	}

	$param_id=1135;

	foreach ($ids as $item_id) {
		$sql="INSERT INTO `shop_params_links` (`item_id`,`param_id`) VALUES ('$item_id','$param_id')";
		print $sql.'<br>';
		// $result = mysql_query($sql) or die(mysql_error());	
	}	
?>