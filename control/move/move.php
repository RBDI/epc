<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?
	$db2 = mysql_select_db('u388041', $db1);
	mysql_query('SET NAMES utf8');


	$sql="SELECT gl_id, gl_name, gl_pictsmall, gl_pictbig, gl_parent FROM z_gallery";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		$imgs[$row['gl_id']]=$row;
	}


	$sql="SELECT * FROM z_item, z_itemtag WHERE z_item.it_id=z_itemtag.ittg_item";
	$result = mysql_query($sql) or die(mysql_error());
	$z=0;
	while ($row=mysql_fetch_array($result)) {
		$items[$row['it_id']]['name']=$row['it_name'];
		$items[$row['it_id']]['tags'][$z]=$row['ittg_tag'];
		$z++;
	}

	$sql="SELECT tg_id, tg_name, tg_group, tg_transliteration, tg_title, tg_info, tg_filter_info FROM z_tag";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		// $tags[$row['tg_id']]['name']=$row['tg_name'];
		$tags[$row['tg_id']]=$row['tg_group'];
		$tgs[$row['tg_id']]['name']=$row['tg_name'];
		$tgs[$row['tg_id']]['slug']=$row['tg_transliteration'];
		$tgs[$row['tg_id']]['parent']=$row['tg_group'];
		$tgs[$row['tg_id']]['title']=$row['tg_title'];
		$tgs[$row['tg_id']]['text']=$row['tg_info'];
		$tgs[$row['tg_id']]['info']=$row['tg_filter_info'];

	}



	foreach ($tags as $tag_id => $parent) {
		if ($parent!=0){
			if (!get_child($tag_id, $tags)) {
				$par=get_parent($parent, $tags);
				if ($par==12) {
					$cats[$tag_id]['parent']=$parent;
					$cats[$tag_id]['name']=$tgs[$tag_id]['name'];
				}
				elseif ($par==13) {					
					$brands[$tag_id]['parent']=$parent;
					$brands[$tag_id]['name']=$tgs[$tag_id]['name'];
				}
			}
		}
	}

	foreach ($tags as $tag_id => $parent) {
		$par=get_parent($parent, $tags);
		if ($par==13){
			$tgs[$tag_id]['type']=13;
			$brnds[$tag_id]['name']=$tgs[$tag_id]['name'];
			$brnds[$tag_id]['parent']=$parent;
		}
		elseif ($par==12){
			$tgs[$tag_id]['type']=12;
			$ctgs[$tag_id]['name']=$tgs[$tag_id]['name'];
			$ctgs[$tag_id]['slug']=$tgs[$tag_id]['slug'];
			$ctgs[$tag_id]['parent']=$parent;
		}
		if ($parent==13) $brnd[$tag_id]=$tgs[$tag_id]['name'];
	}	

	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');

	$sql="SELECT * FROM shop_params WHERE type=0";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		$new_cats[$row['old_id']]['ID']=$row['ID'];
	}

	foreach ($new_cats as $old_id => $id) {
		$new_cats[$old_id]['parent']=$new_cats[$ctgs[$old_id]['parent']]['ID'];
	}

	foreach ($new_cats as $old_id => $id) {
		// $title=mysql_escape_string ($tgs[$old_id]['title']);
		// $text=mysql_escape_string ($tgs[$old_id]['text']);
		// $info= mysql_escape_string ($tgs[$old_id]['info']);
		$title= mysql_escape_string($tgs[$old_id]['title']);
		$text= mysql_escape_string(str_replace(array("\r\n", "\r", "\n"), '', $tgs[$old_id]['text'] )); 
		$info= mysql_escape_string($tgs[$old_id]['info']);
		$xid=$id['ID'];
		$sql="INSERT INTO `shop_texts` (`param_id`,`title`,`text`,`info`) VALUES ('$xid','$title','$text','$info')";	
		// $resultx = mysql_query($sql) or die(mysql_error());
		// print $sql.'<br>';
	}

	// foreach ($new_cats as $old_id => $new_cat) {
	// 	$parent=$new_cat['parent'];
	// 	$id=$new_cat['ID'];
	// 	$sql="UPDATE `shop_params` SET `parent`='$parent' WHERE `ID`='$id'";
	// 	//print $sql.'<br>';
	// 	//$resultx = mysql_query($sql) or die(mysql_error());
	// }

	$sql="SELECT ID, old_id FROM shop_catalog";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		$itms[$row['old_id']]=$row['ID'];
	}

foreach ($imgs as $img_id => $img) {

	$item_id=$itms[$img['gl_parent']];
	$filename=$img['gl_pictbig'];
	$filename=str_replace('/files/', '', $filename);
	//$filename=str_replace('/files/', '', $filename);
	$title=mysql_escape_string($img['gl_name']);
	$sql="INSERT INTO `shop_img` (`item_id`,`filename`,`color`) VALUES ('$item_id','$filename','$title')";	
	//$resultx = mysql_query($sql) or die(mysql_error());
	//print $sql.'<br>';
	
}


	foreach ($items as $item_id => $item) {		 
		foreach ($item['tags'] as $idd => $tg) {
			if (!$cats[$tg]&&!$brnd[$tg]) {
				unset($items[$item_id]['tags'][$idd]);
			}
			if ($cats[$tg]){
				//$items[$item_id]['tags'][$idd]['type']==1;
			}
			elseif ($brands[$tg]){
				// $items[$item_id]['tags'][$idd]['type']==1;
			}
		}		 
	}

	foreach ($items as $item_id => $item) {
		// print '<p>'.$item['name'].'<ul>';
		foreach ($item['tags'] as $idd => $tg) {
			$param_id=$new_cats[$tg]['ID'];
			if ($tgs[$tg]['type']==12) $sql="UPDATE `shop_catalog` SET `type`='$param_id' WHERE `old_id`='$item_id'";
			if ($tgs[$tg]['type']==13) $sql="UPDATE `shop_catalog` SET `brand`='$param_id' WHERE `old_id`='$item_id'";
			// print $sql.'<br>';
			//$resultx = mysql_query($sql) or die(mysql_error());
			// print '<li>'.$tgs[$tg]['name'];
			// print ' - '.$tg;
			// print ' - '.$tgs[$tg]['type'];
			// print '</li>';
		}
		// print '</ul></p>';
	}



function get_child($id, $tags){
	$x=0;
	foreach ($tags as $tag_id => $parent) {
		if ($id==$parent) $x=1;
	}
	return $x;
}

function get_parent($parent, $tags){
	 
	foreach ($tags as $tag_id => $tag_parent) {
		if ($parent==$tag_id){
			if ($tag_parent==0){
				return $tag_id;
			}
			else {
				return get_parent($tag_parent,$tags);
			}			
		}
	}	 
}



?>


<?
	// $db2 = mysql_select_db('u388041', $db1);
	// mysql_query('SET NAMES utf8');

	// $sql="SELECT * FROM z_tag WHERE tg_group=13";
	// $result = mysql_query($sql) or die(mysql_error());
	

	// $db2 = mysql_select_db('u388041_new', $db1);
	// mysql_query('SET NAMES utf8');

	// while ($row=mysql_fetch_array($result)) {

	// 	$name=$row['tg_name'];
	// 	$slug=$row['tg_transliteration'];
	// 	$old_id=$row['tg_id'];
	// 	$title=$row['tg_title'];
	// 	$text=$row['tg_info'];
	// 	$info=$row['tg_filter_info'];

	// 	$sql="INSERT INTO `shop_params` (`type`,`name`,`slug`,`old_id`) VALUES ('2','$name','$slug','$old_id')";	
	// 	$resultx = mysql_query($sql) or die(mysql_error());
	// 	$new_item_id=mysql_insert_id();

	// 	$sql="INSERT INTO `shop_texts` (`param_id`,`title`,`text`,`info`) VALUES ('$new_item_id','$title','$text','$info')";	
	// 	$resultx = mysql_query($sql) or die(mysql_error());

	// 	print $name.' '.$new_item_id.'<br>';

	// }

?>

<?
	// $db2 = mysql_select_db('u388041', $db1);
	// mysql_query('SET NAMES utf8');

	// $sql="SELECT * FROM z_tag WHERE tg_group=13";
	// $result = mysql_query($sql) or die(mysql_error());
	

	// $db2 = mysql_select_db('u388041_new', $db1);
	// mysql_query('SET NAMES utf8');

	// while ($row=mysql_fetch_array($result)) {

	// 	$name=$row['tg_name'];
	// 	$slug=$row['tg_transliteration'];
	// 	$old_id=$row['tg_id'];
	// 	$title=$row['tg_title'];
	// 	$text=$row['tg_info'];
	// 	$info=$row['tg_filter_info'];

	// 	$sql="INSERT INTO `shop_params` (`type`,`name`,`slug`,`old_id`) VALUES ('2','$name','$slug','$old_id')";	
	// 	$resultx = mysql_query($sql) or die(mysql_error());
	// 	$new_item_id=mysql_insert_id();

	// 	$sql="INSERT INTO `shop_texts` (`param_id`,`title`,`text`,`info`) VALUES ('$new_item_id','$title','$text','$info')";	
	// 	$resultx = mysql_query($sql) or die(mysql_error());

	// 	print $name.' '.$new_item_id.'<br>';

	// }

?>

<?

	// $db2 = mysql_select_db('u388041', $db1);
	// mysql_query('SET NAMES utf8');

	// $sql="SELECT * FROM z_item";
	// $result = mysql_query($sql) or die(mysql_error());
	

	// $db2 = mysql_select_db('u388041_new', $db1);
	// mysql_query('SET NAMES utf8');

	// while ($row=mysql_fetch_array($result)) {

	// 	$name=mysql_escape_string ($row['it_name']);
	// 	$desc=mysql_escape_string ($row['it_info']);
	// 	$price=$row['it_price'];
	// 	$count=$row['it_available'];
	// 	$old_id=$row['it_id'];
		

	// 	$sql="INSERT INTO `shop_catalog` (`name`,`desc`,`count`,`price`,`old_id`) VALUES ('$name','$desc','$count','$price','$old_id')";	
	// 	$resultx = mysql_query($sql) or die(mysql_error());


	// }

?>