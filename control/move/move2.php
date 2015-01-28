<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?
	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');

// $sql="SELECT ID, name, country, text FROM shop_params WHERE type=2";
// $result1 = mysql_query($sql) or die(mysql_error());
// while ($brands=mysql_fetch_array($result1)) {

	// $brand=$brands['name'];
	// $brand=$brands['country'];
	// $brand=$brands['text'];
	$brand='Ref. ';
	if ($brand!=''){
		$sql="SELECT ID, name FROM shop_catalog WHERE name LIKE '%$brand%'";
		$result = mysql_query($sql) or die(mysql_error());
		$i=1;
		while ($row=mysql_fetch_array($result)) {

			$id=$row['ID'];
			// $name=mysql_escape_string(str_ireplace($brand, '', $row['name']));
			$n=mb_strpos($row['name'],$brand);
			$m=mb_strpos($row['name'],'(');
			// $article=mb_substr($row['name'], $n+5,mb_strlen($row['name']));
			if ($m<1) $m=$n+5+3;
			$article=mb_substr($row['name'], $n+5,$m-$n-5);
			$article=str_replace(' ', '', $article);
			$name=mysql_escape_string(mb_substr($row['name'], 0, $n-1));

			$sql="UPDATE `shop_catalog` SET `name`='$name',`article`='$article' WHERE `ID`='$id'";
			// $sql="UPDATE `shop_catalog` SET `name`='$name' WHERE `ID`='$id'";
			// $resultx = mysql_query($sql) or die(mysql_error());
			print '<p>'.$i.'. '.$brand.'<br>';
			print $row['name'].'<br>';
			print $sql.'</p>';
			$i++;
		}
	}
// }

// Арт.: 1206018
?>