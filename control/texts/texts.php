<?
	include_once "../../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db(DB_NAME, $db1);
	mysql_query('SET NAMES utf8');


$sql="SELECT ID, phone FROM shop_users";
// $result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$phones[$row['ID']]['phone']=$row['phone'];
}

foreach ($phones as $ID => $phone) {
	$phone2=preg_replace('/[^0-9]/', '', $phone);
	$phone3='';
	if (strlen($phone2['phone'])==11) {

		$phone3=mb_substr($phone2['phone'], 1, strlen($phone2['phone']));
	}
	else {
		$phone3=$phone2['phone'];
	}
	$phones[$ID]['phone2']=$phone3;

	$sql="UPDATE `shop_users` SET `phone2`='$phone3' WHERE `ID`='$ID'";
	print $sql;
	// $result = mysql_query($sql) or die(mysql_error());
}
 
 print_r($phones);

?>