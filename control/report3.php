<?
 	$CT=0;
	if ($_GET['ct']==1){
		$CT=1;
	}
	
	$ORDER='WHERE shop_users.payment_status!=0 '; 
	// $ORDER='WHERE shop_users.manager=1'; 
	

	if (isset($_GET['order'])){ 
		$ORDER='WHERE shop_users.status='.$_GET['order']; 
		$get_order='&order='.$_GET['order']; 
	}

	if (!$ORDER) $LIMIT=' LIMIT 350';
		
	if ($_POST['find_item_id']!=''){
		$flt='AND shop_catalog.ID='.$_POST['find_item_id'];
		$LIMIT='';
	}
	if ($_POST['sql_string']!=''){
		$sql_string=$_POST['sql_string'];
		$LIMIT='';
	}

	// $sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.ID DESC".$LIMIT;
	$sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.ID DESC".$LIMIT;
	print $sql;
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$orders[$row[0]]['name']=$row[1];
		$orders[$row[0]]['email']=$row['email'];
		$orders[$row[0]]['phone']=$row['phone'];
		$orders[$row[0]]['adress']=$row['adress'];
		$orders[$row[0]]['comment']=$row['comment'];
		$orders[$row[0]]['status']=$row['status'];
		$orders[$row[0]]['manager']=$row['manager'];

		
		// $orders[$row[0]]['date_time']=date("d.m.y",strtotime($row['date_time']));
		$orders[$row[0]]['date_time']=$row['date_time'];
		
		$orders[$row[0]]['edit_time']=$row['edit_time'];

		$orders[$row[0]]['delivery_company']=$row['delivery_company'];
		$orders[$row[0]]['delivery_num']=$row['delivery_num'];
		$orders[$row[0]]['delivery_price']=$row['delivery_price'];
		$orders[$row[0]]['outgo']=$row['outgo'];
		$orders[$row[0]]['payment_status']=$row['payment_status'];
		$orders[$row[0]]['pay_time']=$row['pay_time'];

		$orders[$row[0]]['epc_source']=$row['epc_source'];
		$orders[$row[0]]['epc_term']=$row['epc_term'];

	}

?>	
<style>
table {
	border-spacing: 1px;
}
.tbl{
	background: #CCC;
}
.tbl td{
	background: #FFF;
	padding: 5px;
}
</style> 
	
<?	
	$needles=array(218, 997, 1234, 1235, 1236, 1238, 1239, 1240, 1471, 1474, 227, 998, 1241, 1242, 1243, 1244, 1245, 1246, 1247, 329, 1399, 1398, 1397, 999, 1252, 1253, 1254, 1400, 1401);
	foreach ($orders as $order_id => $order) {	 
		$itm[$order['manager']].= '<tr>';		
		$itm[$order['manager']].= '<td >'.$order_id.'</td>';
 
		
		$items='';

		// $user_id=$_GET['id'];
		$user_id=$order_id;


		if ($user_id) {

			$xql="SELECT shop_orders.item_id, shop_subitem.item_id, shop_subitem.name, shop_subitem.value1, shop_subitem.value2,shop_subitem.value3, shop_subitem.value4, shop_orders.count, shop_catalog.name,shop_subitem.ID,shop_orders.ID  FROM  shop_orders, shop_subitem, shop_catalog WHERE shop_orders.item_id=shop_subitem.ID AND shop_orders.user_id=$user_id AND shop_catalog.ID=shop_subitem.item_id";
			$xresult = mysql_query($xql) or die(mysql_error());
			unset($item);
			while ($xrow=mysql_fetch_array($xresult)) {
				$item[$xrow[10]]['name']=$xrow[8];
				if ($xrow[2]) $item[$xrow[10]]['name'].= ' ('.$xrow[2].')';
				$item[$xrow[10]]['article']=$xrow[5];
				$item[$xrow[10]]['count']=$xrow[7];
				$item[$xrow[10]]['subitem_id']=$xrow[9];
				$item[$xrow[10]]['item_id']=$xrow[1];
				if ($xrow['value2']) $item[$xrow[10]]['price']=$xrow['value2'];
				else $item[$xrow[10]]['price']=$xrow['value1'];
				$item[$xrow[10]]['shop_price']=$xrow['value4'];
			}
			// $itm[$order['manager']]='';
			// $itm='<table class="tbl" width="100%">';
			
			$i=1;
			$TTL=0;
			$TTL2=0;
			$N_TTL=0;
			$N_TTL2=0;			
			$itms='';
			foreach ($item as $id => $v) {
				$needle_ttl=0;
				$needle_ttl2=0;

				$ttl=$v['count']*$v['price'];
				$ttl2=$v['count']*$v['shop_price'];
				if (in_array($v['subitem_id'], $needles)){
					$itms.='<span style="color:#F00;">'.$v['item_id'].' ('.$v['subitem_id'].');</span> ';
					$needle_ttl=$v['count']*$v['price'];
					$needle_ttl2=$v['count']*$v['shop_price'];
				}
				else $itms.=$v['item_id'].' ('.$v['subitem_id'].'); ';
				/*
				$itm.='<tr><td>'.$i.'. '.$v['name'].', '.$v['article'].'</td>';
				
				$itm.='<td>'.$v['price'].'</td>';
				$itm.='<td> '.$v['shop_price'].'</td>';
				$itm.='<td> '.$v['count'].' </td>';
				$itm.='<td>'.$ttl.'</td>';
				$itm.='<td>'.$ttl2.'</td>';
				$itm.='<td>'.($ttl-$ttl2).'</td>';
				
				
				$itm.='</tr>';
				*/
				$TTL+=$ttl;
				$TTL2+=$ttl2;
				$N_TTL+=$needle_ttl;
				$N_TTL2+=$needle_ttl2;
				$i++;
			}
			// $d_p=$order['delivery_price'];
			$d_p=$order['outgo'];
			$itm[$order['manager']].='<td>'.$itms.'</td><td>'.$TTL.'</td><td>'.$TTL2.'</td><td>'.$d_p.'</td><td><strong>'.($TTL-$TTL2-$d_p).'</strong></td><td><strong>'.($N_TTL-$N_TTL2).'</strong></td>';
		}

		if (1==1){
		
		// print '<td><table class="tbl" width="100%"><tr>';

			$st[3]='Новый заказ';
			$st[2]='Отложили';
			$st[5]='Отправлен в доставку';
			$st[7]='Самовывоз';
			$st[1]='Уведомить о наличии';
			$st[0]='Закрыт успешно';
			$st[4]='Не выполнен';	

		$xst=$st[$order['status']];

			$dl[0]='';
			$dl[1]='СДЭК';
			$dl[2]='ЭкспрессМ24';
			$dl[3]='EMS';
			$dl[4]='Почта России';

		$xdl=$dl[$order['delivery_company']];

			$py[0]='Не оплачен';
			$py[1]='Оплачен наличными';
			$py[2]='Оплачен безнал.';
			$py[3]='Оплачен картой';
			$py[4]='Оплачен по квитанции';

		$xpy=$py[$order['payment_status']];


		// print '<td width="10%">'.date("H:i, d.m.y",strtotime($order['edit_time'])).'<br>'.$xst.'<br>'.$order['date_time'];
		// print '<td >'.$xst.'<br>'.$order['date_time'];
		$itm[$order['manager']].='<td >'.$order['date_time'].'</td>';
		$itm[$order['manager']].='<td >'.$order['pay_time'].'</td>';

		// $itm[$order['manager']].= '</td>';
		// print '<td width="20%">'.$order['name'].', '.$order['email'].', '.$order['phone'].', '.$order['adress'];

		// if ($order['epc_source']) print '<p>'.$order['epc_source'].', "'.$order['epc_term'].'"</p>';

		// print '</td><td width="10%">'.$order['comment'];
		// print '<td width="10%">'.$xdl;
		// print '<p>'.$order['delivery_num'].'</p>';

		// print '</td>';
		// print '<td >'.$xpy;



		// print '</td>';
		// $itm[$order['manager']].= $itm;
		 

		
		// print '<td></td>';
		
		// print'</tr></table>		
		// </td>';

		}
		else {
			if ($order['edit_time']!='') print '<td>'.$order['edit_time'].'</td>';
			else print '<td>'.$order['date_time'].'</td>';
			// print '<td>'.$items.'</td>';
			// print '<td>'.$order['comment'].'</td>';
			// print '<td>'.$order['name'].'</td>';
			// print '<td>'.$order['email'].'</td><td>'.$order['phone'].'</td>';
			// print '<td>'.$order['adress'].'</td>';
			if ($CT!=1) print '<td><a href="?param=orders&delid='.$order_id.'"><img src="img/del.png" title="Удалить заказ" onclick="return confirm('."'Удалить?'".');" border="0"></a></td>';
		}
		$itm[$order['manager']].= '</tr>';	
		
	}

	// $itm[$order['manager']].= '';
?>
Скорода
<table class="tbl">	
	<? print $itm[1]; ?>
</table>
Ника
<table class="tbl">	
	<? print $itm[0]; ?>
</table>