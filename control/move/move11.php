<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?


	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');	


	$sql="SELECT * FROM  `shop_catalog` WHERE  `desc` LIKE  '%htm%'";
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {
		$desc=$row['desc'];
		$IDx=$row['ID'];
		// $sql="UPDATE `shop_subitem` SET `value3`='$article' WHERE `item_id`='$ID'";
		// print $ID;
		$desc=str_ireplace('http://www.europrofcosmetic.ru', '', $desc);
		$desc=str_ireplace('../item', '/item', $desc);
		$desc=str_ireplace('?phpMyAdmin=YAzB3li1vcm%2C6XoPNGXyDaizs51', '', $desc);
		
		preg_match_all('/\/item_+[0-9]+_[0-9]+.html/',$desc,$found);

		foreach ($found[0] as $value) {

			preg_match_all('/_+[0-9]+_/',$value,$i_d);
			$id=str_ireplace('_','',$i_d[0][0]);
			
			$sql="SELECT ID FROM  `shop_catalog` WHERE  `old_id`=$id";
			$result1 = mysql_query($sql) or die(mysql_error());
			$new_id=mysql_fetch_array($result1);

			$desc=str_ireplace($value,'/catalog/products/'.$new_id['ID'],$desc);


		}
			$desc=mysql_escape_string($desc);
			$sql="UPDATE `shop_catalog` SET `desc`='$desc' WHERE `ID`='$IDx'";
			// print $sql;
			// $resultx = mysql_query($sql) or die(mysql_error());
		// print_r($found);

		// print $desc;

		// print '-';
		
	}

	

	 

?>