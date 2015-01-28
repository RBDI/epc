<?
include "config.php";

// print_r($_POST);
$ID=$_POST['ID'];
$status=$_POST['status'];

if ($ID) {
	$sql="SELECT `status` FROM shop_users WHERE `ID`='$ID'";
	$result = mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$current_status=$row['status'];
}

if ($_POST['shop_price']) {		
	$shop_price=$_POST['shop_price'];	
	foreach ($shop_price as $subitem_id => $value) {
		$sql="UPDATE `shop_subitem` SET `value4`='$value' WHERE `ID`='$subitem_id'";
		$result = mysql_query($sql) or die(mysql_error());			
	}
}

if ($_POST['item_count']) {		
	$item_count=$_POST['item_count'];
	$item_discount=$_POST['item_discount'];
	$item_id=$_POST['item_id'];
	$item_in_stock=$_POST['item_in_stock'];
	print_r($item_count);
	foreach ($item_count as $order_id => $count) {
		$discount=$item_discount[$order_id];
		$sql="UPDATE `shop_orders` SET `count`='$count',`discount`='$discount' WHERE `ID`='$order_id'";
		$result = mysql_query($sql) or die(mysql_error());
		
		$item_id=$item_id[$order_id];
		// print $item_id;
		// print_r ($item_in_stock);
		if ($item_in_stock[$order_id]>0&&$status==2&&$current_status!=2&&($current_status==3||$current_status==1||$current_status==8||$current_status==4||$current_status==9)){
			$sql="UPDATE `shop_subitem` SET `in_stock`=`in_stock`-$count WHERE `ID`='$item_id'";
			// print $sql;
			$result = mysql_query($sql) or die(mysql_error());			
		}
		if (($status==8||$status==4||$status==3)&&($current_status==2||$current_status==5||$current_status==7||$current_status==2||$current_status==0)&&$current_status!=8&&$current_status!=4&&$current_status!=3){
			$sql="UPDATE `shop_subitem` SET `in_stock`=`in_stock`+$count WHERE `ID`='$item_id'";
			$result = mysql_query($sql) or die(mysql_error());			
		}		
	}

}

	if (isset($status)&&$current_status!=$status){
		// $control_time=$_POST['control_time'];
		$sql="UPDATE `shop_users` SET `status`='$status' WHERE `ID`='$ID'";
		$result = mysql_query($sql) or die(mysql_error());
	}
	if ($_POST['user_edit']==1) {
		$comment=$_POST['user_comment'];
		$name=$_POST['user_name'];
		$email=$_POST['user_email'];
		$phone=$_POST['user_phone'];
		$phone=fphone($phone);
		$adress=$_POST['user_adress'];

		$delivery_company=$_POST['delivery_company'];
		$delivery_num=$_POST['delivery_num'];
		$delivery_date=$_POST['delivery_date'];
		$delivery_time=$_POST['delivery_time'];
		$delivery_price=$_POST['delivery_price'];
		$outgo=$_POST['outgo'];
		$payment_status=$_POST['payment_status'];
		$PAYTIME='';
		

		$sql="SELECT payment_status FROM shop_users WHERE `ID`='$ID'";
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_array($result);

		date_default_timezone_set('Europe/Moscow');
		if ($payment_status!=0&&$row['payment_status']==0) $PAYTIME =", `pay_time`='".date("c")."'";
		if ($payment_status==0) $PAYTIME =", `pay_time`=''";
		$manager=$_POST['manager'];
		$source=$_POST['source'];
		$client_type=$_POST['client_type'];

		$sql="UPDATE `shop_users` SET `name`='$name',`email`='$email',`phone`='$phone',`adress`='$adress',`comment`='$comment', `delivery_company`='$delivery_company', `delivery_num`='$delivery_num', `delivery_price`='$delivery_price', `payment_status`='$payment_status', `manager`='$manager', `outgo`='$outgo', `source`='$source', `client_type`='$client_type', `delivery_date`='$delivery_date', `delivery_time`='$delivery_time' $PAYTIME WHERE `ID`='$ID'";
		$result = mysql_query($sql) or die(mysql_error());
	}

?>