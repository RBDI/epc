<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?


	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');	


	$sql="SELECT ID, article FROM shop_catalog WHERE article!=''";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		$article=$row['article'];
		$ID=$row['ID'];
		$sql="UPDATE `shop_subitem` SET `value3`='$article' WHERE `item_id`='$ID'";
		print $sql.'<br> ';
		// $resultx = mysql_query($sql) or die(mysql_error());
	}

	

	 

?>