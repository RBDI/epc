<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');
 
 
		

// $sql="SELECT shop_users.ID, shop_subitem.value1, shop_subitem.value2, shop_orders.ID, shop_users.pay_time, shop_users.delivery_price, shop_catalog.name, shop_subitem.name, shop_users.date_time,shop_users.edit_time,shop_users.invoiceId,shop_users.payment, shop_orders.count, shop_users.delivery_num  FROM shop_users, shop_orders, shop_subitem, shop_catalog WHERE shop_users.payment_status>0 AND shop_users.delivery_company=1 AND shop_orders.user_id=shop_users.ID AND shop_orders.item_id=shop_subitem.ID AND shop_catalog.ID=shop_subitem.item_id  ORDER BY shop_users.ID DESC";
	$sql="SELECT shop_users.ID, shop_subitem.value1, shop_subitem.value2, shop_orders.ID, shop_users.pay_time, shop_users.delivery_price, shop_catalog.name, shop_subitem.name, shop_users.date_time,shop_users.edit_time,shop_users.invoiceId,shop_users.payment, shop_orders.count, shop_users.delivery_num  FROM shop_users, shop_orders, shop_subitem, shop_catalog WHERE shop_users.payment_status=3 AND shop_orders.user_id=shop_users.ID AND shop_orders.item_id=shop_subitem.ID AND shop_catalog.ID=shop_subitem.item_id  ORDER BY shop_users.ID DESC";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	
	$orders[$row[0]]['goods'][$row[3]]['name']=$row[6].' '.$row[7];
	$orders[$row[0]]['goods'][$row[3]]['count']=$row['count'];
	if ($row['value2']!='') $orders[$row[0]]['goods'][$row[3]]['price']=($row['value2']*$row['count']);
	else $orders[$row[0]]['goods'][$row[3]]['price']=$row['value1']*$row['count'];
	
	
	$orders[$row[0]]['pay_time']=$row['pay_time'];
	$orders[$row[0]]['date_time']=$row['date_time'];
	$orders[$row[0]]['edit_time']=$row['edit_time'];
	if ($row['value2']!='') $orders[$row[0]]['price']+=($row['value2']*$row['count']);
	else $orders[$row[0]]['price']+=($row['value1']*$row['count']);
	$orders[$row[0]]['payment']=$row['payment'];
	$orders[$row[0]]['invoiceId']=$row['invoiceId'];
	$orders[$row[0]]['delivery_price']=$row['delivery_price'];
	$orders[$row[0]]['delivery_num']=$row['delivery_num'];
}

print '<table width="100%">';
$i=1;
foreach ($orders as $key => $val) {
	print '<tr><td>'.$i.'</td><td>'.$key.'</td><td><strong>'.$val['pay_time'].'</strong><br>'.$val['edit_time'].'<br>'.$val['date_time'].'</td><td>'.$val['delivery_price'].'</td><td>'.$val['price'].'</td><td><strong>'.($val['price']+$val['delivery_price']).'</strong></td><td>'.$val['payment'].'</td>';
	print '<td>';
	foreach ($val['goods'] as $ID => $val2) {
		print $val2['name'].' - '.$val2['count'].' - '.$val2['price'].'<br>';
	}
	print '</td>';
	print '<td>'.$val['invoiceId'].'</td>';
	print '<td>'.$val['delivery_num'].'</td>';
	print '</tr>';
	$i++;
}
print '</table>';
?>