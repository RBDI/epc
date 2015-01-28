<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?
	$db2 = mysql_select_db('u388041', $db1);
	mysql_query('SET NAMES utf8');



	$sql="SELECT * FROM z_tag";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		if ($row['tg_group']==13) $brands[$row['tg_id']]=$row;
		else $other[$row['tg_id']]=$row;

	}


	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');	


	$sql="SELECT ID,old_id FROM shop_params WHERE type=2";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {		
		getsubtag($other,$row['old_id'],$row['ID']);
	}

	

	

function getsubtag ($struct, $parent, $new_parent){
	foreach ($struct as $id => $row) {
			if ($row['tg_group']==$parent) {
				$name=$row['tg_name'];
				$slug=$row['tg_transliteration'];
				// $old_id=$parent;
				$old_id=$id;
				$sql="INSERT INTO `shop_params` (`type`,`name`,`slug`,`parent`,`old_id`,`pos`) VALUES ('2','$name','$slug','$new_parent','$old_id',55)";
				// $resultx = mysql_query($sql) or die(mysql_error());
				// $new_item_id=mysql_insert_id();				
				
				$title= mysql_escape_string($row['tg_title']);
				print $name.'<br>';
				$text= mysql_escape_string(str_replace(array("\r\n", "\r", "\n"), '', $row['tg_info'])); 
				$info= mysql_escape_string($row['tg_title']);

				$sql="INSERT INTO `shop_texts` (`param_id`,`title`,`text`,`info`) VALUES ('$new_item_id','$title','$text','$info')";	
				// $resultx = mysql_query($sql) or die(mysql_error());

				getsubtag($struct,$id,$new_item_id);
			}
		}

}



?>