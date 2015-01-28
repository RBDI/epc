<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?


	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');	

	$sql="SELECT ID, parent, old_id,name FROM shop_params WHERE pos=55";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		// $params[$row['old_id']]=$row;
		$params[$row['old_id']]=$row;
	}

	$sql="SELECT ID, parent, old_id,name FROM shop_params ";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		// $params[$row['old_id']]=$row;
		$allparams[$row['old_id']]=$row;
	}

	$sql="SELECT ID, old_id,name FROM shop_catalog";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		$items[$row['old_id']]=$row;
	}	
	// $sql="SELECT * FROM shop_params_links";
	// $result = mysql_query($sql) or die(mysql_error());
	
	// while ($row=mysql_fetch_array($result)) {
	// 	$params_links[$row['item_id']][]=$row['param_id'];
	// }	

	// foreach ($params_links as $item_id => $params) {
	// 	print_r($params);
	// 	foreach ($params as $param) {
	// 		$xx=rm1($struct,$param, $params);
	// 		if ($xx) print $xx.' ';
	// 	}				
	// }

	$db2 = mysql_select_db('u388041', $db1);
	mysql_query('SET NAMES utf8');	

	foreach ($items as $old_id => $item) {
		$sql="SELECT ittg_id, ittg_tag FROM z_itemtag WHERE ittg_item=$old_id AND ittg_tag!=12 ";
		$result = mysql_query($sql) or die(mysql_error());
		if ($old_params) unset($old_params);
		while ($row=mysql_fetch_array($result)) {
			$old_params[$row['ittg_id']]=$row['ittg_tag'];
			
		}
		if ($old_params){
			print '
-
';	
			print $item['ID'].'('.$old_id.') '.$item['name'].' ';
			print_r($old_params);
			foreach ($old_params as $old_param_id) {
				print $allparams[$old_param_id]['name'].'['.$old_param_id.'] ';
				$ischild=takechild($old_param_id,$params,$old_params);
				// if ($ischild) print $params[$old_param_id]['name'].' ';
			}
		}
	}

	function takechild ($old_param_id,$params, $old_params){
		foreach ($params as $old_id => $param) {			
// 			print $param['parent'].'='.$params[$old_param_id]['ID'].'('.$old_param_id.')
// ';
			if ($param['parent']==$params[$old_param_id]['ID']){
				// /print $old_param_id.' ';
				foreach ($old_params as $value) {
					if ($value==$old_param_id) {
						return 1;
					}
				}
			}
		}
		return 0;
	}

// $sqls[]="INSERT INTO `shop_params_links` (`item_id`,`param_id`) VALUES ('$item_id','$param_id')";

	// $db2 = mysql_select_db('u388041_new', $db1);
	// mysql_query('SET NAMES utf8');	

	// foreach ($sqls as $sql) {
	// 	// print $sql.'<br>';
	// 	// $result = mysql_query($sql) or die(mysql_error());
	// }



	
?>