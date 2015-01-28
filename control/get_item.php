<?
include "config.php";

if ($_POST['string']!=''){
	$text=$_POST['string'];
	$order_id=$_POST['order_id'];

	$sql="SELECT shop_catalog.ID, shop_catalog.name, shop_subitem.name, shop_subitem.value1, shop_subitem.value3, shop_subitem.ID, shop_subitem.value2, shop_subitem.value4 FROM shop_catalog, shop_subitem WHERE (shop_subitem.ID LIKE '%$text%' OR shop_subitem.item_ID LIKE '%$text%' OR shop_catalog.name LIKE '%$text%' OR shop_subitem.name LIKE '%$text%' OR shop_subitem.value3 LIKE '%$text%') AND shop_subitem.item_id=shop_catalog.ID;";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$price=$row[3];
		if ($row[6]>0) $price=$row[6];
		$td.= '<tr><td>'.$row[1].' '.$row[2].'</td><td><input id="item_count_'.$row[5].'" class="form-control" type="text" size="3" value="1"></td><td><a href="javascript:{}" onclick="add_item('.$row[5].','.$order_id.');"><span class="glyphicon glyphicon-plus"></span></a></td><td><span id="item_price_'.$row[5].'">'.$price.'</span> руб.</td><td> '.$row[4].'</td><td>'.$row[0].' '.$row[5].'<input type="hidden" id="item_price2_'.$row[5].'" value="'.$row[7].'"></td></tr>';
	}
	print '<table>'.$td.'</table>';
}
elseif ($_POST['subitem_id']!=0&&$_POST['order_id']!=0){
	$order_id=$_POST['order_id'];
	$subitem_id=$_POST['subitem_id'];
	$count=$_POST['item_count'];
	$price=$_POST['item_price'];
	$price2=$_POST['item_price2'];
	$sql="INSERT INTO `shop_orders` (`user_id`,`item_id`,`count`,`price`,`price2`) VALUES ('$order_id','$subitem_id','$count','$price','$price2')";
	$result = mysql_query($sql) or die(mysql_error());
	$new_order_item_id=mysql_insert_id();

	$sql="SELECT shop_catalog.ID, shop_subitem.ID, shop_catalog.name, shop_subitem.name, shop_subitem.value1, shop_subitem.value2, shop_subitem.value3,shop_subitem.value4 FROM shop_catalog, shop_subitem WHERE shop_subitem.ID=$subitem_id AND shop_subitem.item_id=shop_catalog.ID;";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$price=$row[4];
		if ($row[5]>0) $price=$row[5];
		$t_price=$price*$count;
		print '<tr class="item_tr"><td></td><td>'.$row[1].'</td><td>'.$row[2].' '.$row[3].' '.$row[6].'</td><td><span class="item_price">'.$price.'</span> руб.</td>';
		print '<td style="width:60px;"><input class="form-control item_discount" name="item_discount['.$new_order_item_id.']" type="text" size="3" value="" placeholder="%" onchange="refresh_price();" /></td>';
		print '<td><input class="form-control item_count" name="item_count['.$new_order_item_id.']" type="text" size="3" value="'.$count.'" onchange="refresh_price();" ></td><td><span class="item_total_price">'.$t_price.'</span> руб.</td> <td><input class="form-control" name="shop_price['.$row[1].']" type="text" size="7" value="'.$row[7].'"></td><td><a href="?param=orders&id='.$order_id.'&delorderid='.$new_order_item_id.'" onclick="return confirm('."'Удалить?'".');"><span class="glyphicon glyphicon-remove"></span></a></td></tr>';
	}
}
?>