<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?
	$db2 = mysql_select_db('u388041', $db1);
	mysql_query('SET NAMES utf8');

	$sql="SELECT it_id, it_title, it_seo_text FROM z_item";
	$result = mysql_query($sql) or die(mysql_error());

		
	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');

	while ($row=mysql_fetch_array($result)) {
		$old_id=$row['it_id'];
		$title=mysql_escape_string($row['it_title']);
		$text=mysql_escape_string($row['it_seo_text']);
		if ($title!=''||$text!='') {
			$sql="UPDATE `shop_catalog` SET `seo_title`='$title',`seo_text`='$text' WHERE `old_id`='$old_id'";
			print $old_id.'<br> ';
			// $resultx = mysql_query($sql) or die(mysql_error());
		}
	}

	
?>