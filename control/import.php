<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db('u216092_2', $db1);  
	mysql_query('SET NAMES utf8');
		$item_ids=array(3236,2099,2096,2102,1643,3233,3234,2005);	
	$i=0;
	foreach ($item_ids as $item_id){
		if ($i==0) $sql="shop_catalog.ID=$item_id";
		else $sql.=" OR shop_catalog.ID=$item_id";
		$i++;
	}
	
	$sql="SELECT * FROM `shop_catalog`,`shop_img` WHERE ($sql) AND shop_img.item_id=shop_catalog.ID;";

	$result = mysql_query($sql) or die(mysql_error());
	$j=1;
	$row=mysql_fetch_array($result);
	while ($row=mysql_fetch_array($result)) {	
		$items[$row[0]]=$row;
		$j++;
	}
	
	$db2 = mysql_select_db('u216092', $db1);  
	
	foreach ($items as $item_id => $item){
		$sql="INSERT INTO `shop_catalog` (`name`,`desc`,`type`,`brand`,`count`,`price`,`special_price`,`article`) values ('".$item['name']."', '".$item['desc']."','60','121','1','".$item['price']."','".$item['special_price']."','".$item['article']."')";
		
		$result = mysql_query($sql) or die(mysql_error());
		
		$new_item_id=mysql_insert_id();
					
		$sql="INSERT INTO `shop_img` (`item_id`,`filename`,`color`) values ('$new_item_id','".$item['filename']."','".$item['color']."')";
		$result = mysql_query($sql) or die(mysql_error());
		copy('/home/u216092/lenebjerre.ru/www/products/'.$item['filename'].'_small.jpg','/home/u216092/decor4home.ru/www/products/'.$item['filename'].'_small.jpg');
		copy('/home/u216092/lenebjerre.ru/www/products/'.$item['filename'].'_medium.jpg','/home/u216092/decor4home.ru/www/products/'.$item['filename'].'_medium.jpg');		
	}	
	
?>
