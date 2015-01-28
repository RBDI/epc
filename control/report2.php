<?
 	$CT=0;
	if ($_GET['ct']==1){
		$CT=1;
	}
	
	$ORDER='WHERE shop_users.payment_status!=0'; 

	if (isset($_GET['order'])){ 
		$ORDER='WHERE shop_users.status='.$_GET['order']; 
		$get_order='&order='.$_GET['order']; 
	}

	if (!$ORDER) $LIMIT=' LIMIT 150';
		
	if ($_POST['find_item_id']!=''){
		$flt='AND shop_catalog.ID='.$_POST['find_item_id'];
		$LIMIT='';
	}
	if ($_POST['sql_string']!=''){
		$sql_string=$_POST['sql_string'];
		$LIMIT='';
	}
	// $sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.ID DESC".$LIMIT;
	// $sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.date_time DESC".$LIMIT;
	$sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.edit_time DESC".$LIMIT;
	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$orders[$row[0]]['name']=$row[1];
		$orders[$row[0]]['email']=$row['email'];
		$orders[$row[0]]['phone']=$row['phone'];
		$orders[$row[0]]['adress']=$row['adress'];
		$orders[$row[0]]['comment']=$row['comment'];
		$orders[$row[0]]['status']=$row['status'];
		$orders[$row[0]]['date_time']=date("d.m.y",strtotime($row['date_time']));
		// $orders[$row[0]]['edit_time']=$row['edit_time'];
		$orders[$row[0]]['edit_time']=date("d.m.y",strtotime($row['edit_time']));

		$orders[$row[0]]['delivery_company']=$row['delivery_company'];
		$orders[$row[0]]['delivery_num']=$row['delivery_num'];
		$orders[$row[0]]['delivery_price']=$row['delivery_price'];
		$orders[$row[0]]['payment_status']=$row['payment_status'];

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
	<table class="tbl">
<?	
	foreach ($orders as $order_id => $order) {	 
		// print '<tr>';		
		// print '<td >'.$order_id.'</td>';
 
		
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
				$item[$xrow[10]]['item_id']=$xrow[10];
				if ($xrow['value2']) $item[$xrow[10]]['price']=$xrow['value2'];
				else $item[$xrow[10]]['price']=$xrow['value1'];
				$item[$xrow[10]]['shop_price']=$xrow['value4'];
			}

			// $itm='<table class="tbl" width="100%">';
			


			$i=1;
			$TTL=0;
			$TTL2=0;
			foreach ($item as $id => $v) {
				$ttl=$v['count']*$v['price'];
				$ttl2=$v['count']*$v['shop_price'];
				// $itm.='<tr><td>'.$i.'. '.$v['name'].', '.$v['article'].'</td>';
				
				// $itm.='<td>'.$v['price'].'</td>';
				// $itm.='<td> '.$v['shop_price'].'</td>';
				// $itm.='<td> '.$v['count'].' </td>';
				// $itm.='<td>'.$ttl.'</td>';
				// $itm.='<td>'.$ttl2.'</td>';
				// $itm.='<td>'.($ttl-$ttl2).'</td>';
				
				
				// $itm.='</tr>';
				$TTL+=$ttl;
				$TTL2+=$ttl2;
				$i++;
			}
			// $dates[$order['date_time']][$order_id]['TTL']=$TTL;
			// $dates[$order['date_time']][$order_id]['TTL2']=$TTL2;
			// $dates[$order['date_time']][$order_id]['status']=$order['payment_status'];
			$dates[$order['edit_time']][$order_id]['TTL']=$TTL;
			$dates[$order['edit_time']][$order_id]['TTL2']=$TTL2;
			$dates[$order['edit_time']][$order_id]['status']=$order['payment_status'];			

			
			// $itm.='<tr><td colspan="4" align="right"></td><td>'.$TTL.'</td><td>-'.$TTL2.'</td><td><strong>'.($TTL-$TTL2).'</strong></td></tr></table>';
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


		// print '<td width="10%"><p>'.$order['date_time'].'<br>'.$xst;

		// print '<!--<br>Изменен: '.$order['edit_time'].'--></p></td>';
		// print '<td width="20%">'.$order['name'].', '.$order['email'].', '.$order['phone'].', '.$order['adress'];

		// if ($order['epc_source']) print '<p>'.$order['epc_source'].', "'.$order['epc_term'].'"</p>';

		// print '</td><td width="10%">'.$order['comment'];
		// print '<td width="10%">'.$xdl;
		// print '<p>'.$order['delivery_num'].'</p>';

		// print '</td><td width="5%">'.$xpy;



		// print '</td>';
		// print '<td>'.$itm;
		 

		// print '</td>';		
		// print '<td></td>';
		
		// print'</tr></table>		
		// </td>';

		}
		else {
			// if ($order['edit_time']!='') print '<td>'.$order['edit_time'].'</td>';
			// else print '<td>'.$order['date_time'].'</td>';
			// // print '<td>'.$items.'</td>';
			// print '<td>'.$order['comment'].'</td>';
			// print '<td>'.$order['name'].'</td>';
			// print '<td>'.$order['email'].'</td><td>'.$order['phone'].'</td>';
			// print '<td>'.$order['adress'].'</td>';
			// if ($CT!=1) print '<td><a href="?param=orders&delid='.$order_id.'"><img src="img/del.png" title="Удалить заказ" onclick="return confirm('."'Удалить?'".');" border="0"></a></td>';
		}
		// print '</tr>';	
		
	}
	// print '</table>';

	foreach ($dates as $date => $orders) {
		print '<tr><td>'.$date.'</td>';
		$i=0;
		$xTTL=0;
		$xTTL2=0;
		foreach ($orders as $id => $value) {
			$xTTL+=$value['TTL'];
			$xTTL2+=$value['TTL2'];
			$i++;			
		}
		print '<td>'.$i.'</td><td>'.$xTTL.'</td><td>'.$xTTL2.'</td><td>'.($xTTL-$xTTL2).'</td></tr>';
		$final+=($xTTL-$xTTL2);
	}
	print '<td colspan="4"> </td><td>'.$final.' </td></tr>';
	print '</table>';	
?>