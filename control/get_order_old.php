<?
include "config.php";

$user_id=$_POST['id'];
$order_id=$user_id;



$sql="SELECT * FROM shop_users WHERE ID=$user_id";
//print $sql;	

$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$order['name']=$row[1];
	$order['email']=$row['email'];
	$order['phone']=$row['phone'];
	$order['adress']=$row['adress'];
	$order['comment']=$row['comment'];
	$order['status']=$row['status'];
	$order['date_time']=$row['date_time'];
	$order['edit_time']=$row['edit_time'];

	$order['delivery_company']=$row['delivery_company'];
	$order['delivery_num']=$row['delivery_num'];
	$order['delivery_price']=$row['delivery_price'];
	$order['outgo']=$row['outgo'];
	$order['payment_status']=$row['payment_status'];

	$order['pay_time']=$row['pay_time'];
	$order['manager']=$row['manager'];
	$order['source']=$row['source'];
	$order['client_type']=$row['client_type'];

	$order['epc_source']=$row['epc_source'];
	$order['epc_term']=$row['epc_term'];


	// $orders[$row[0]]['control_time']=$row['control_time'];
	// $orders[$row[0]]['items'][$row[10]]['item_id']=$row['item_id'];
	// $orders[$row[0]]['items'][$row[10]]['name']=$row[14];
	// $orders[$row[0]]['items'][$row[10]]['slug']=$row['slug'];
	// $orders[$row[0]]['items'][$row[10]]['color']=$row['color'];
	// $orders[$row[0]]['items'][$row[10]]['size']=$row['size'];
	// $orders[$row[0]]['items'][$row[10]]['price']=$row['price'];
}

if ($user_id) {

	$xql="SELECT shop_orders.item_id, shop_subitem.item_id, shop_subitem.name, shop_subitem.value1, shop_subitem.value2,shop_subitem.value3, shop_subitem.value4, shop_orders.count, shop_catalog.name,shop_subitem.ID,shop_orders.ID  FROM  shop_orders, shop_subitem, shop_catalog WHERE shop_orders.item_id=shop_subitem.ID AND shop_orders.user_id=$user_id AND shop_catalog.ID=shop_subitem.item_id";
	$xresult = mysql_query($xql) or die(mysql_error());
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

	// $itm='<form id="form1" name="form1" method="post" action="">';
	$itm='<table width="100%" id="items_table" >';
	// $itm.='<tr><th>Название</th><th>Артикул</th><th>Цена</th><th>Кол-во</th><th>Итого</th><th>Закупка</th></tr>';
	$i=1;
	$TTL=0;
	foreach ($item as $id => $v) {
		$ttl=$v['count']*$v['price'];
		$itm.='<tr><td>'.$i.'</td><td>'.$v['subitem_id'].'</td><td>'.$v['name'].' '.$v['article'].'</td><td>'.$v['price'].' руб.</td>';
		$itm.='<td><input class="form-control" name="item_count['.$id.']" type="text" size="3" value="'.$v['count'].'" /></td>';
		$itm.='<td>'.$ttl.' руб.</td>';
		$itm.='<td><input class="form-control" name="shop_price['.$v['subitem_id'].']" type="text" size="7" value="'.$v['shop_price'].'" /></td>';
		$itm.='<td><a href="?param=orders&id='.$user_id.'&delorderid='.$id.'" onclick="return confirm('."'Удалить?'".');"><span class="glyphicon glyphicon-remove"></span></a></td>';
		$itm.='</tr>';
		$TTL+=$ttl;
		$i++;
	}
	$itm.='<tr>
	<td colspan="5" align="right">Итого: <strong>'.$TTL.' руб.</strong></td>
	<td><input type="hidden" name="ID" value="'.$order_id.'" />
	<!--<input type="submit" value="Save" />-->
	</td>
	</tr></table>';
	// $itm.='</form>';
 

	$TOTAL=$TTL+$order['delivery_price'];
 
	print '<tr id="showrow'.$order_id.'"><td valign="top" class="status"><a href="javascript:{}" onclick="hide_order('.$order_id.')">'.$order_id.' -</a></td>';
	print '<td colspan="7" class="selected">
<form id="form1" name="form1" method="post" action="?param=orders&id='.$order_id.'">
	<table class="sl" width="100%"><tr>';
	$st[$order['status']]='selected="selected"';

	$dl[$order['delivery_company']]='selected="selected"';
	$py[$order['payment_status']]='selected="selected"';

	$mg[$order['manager']]='selected="selected"';
	$sc[$order['source']]='selected="selected"';
	$ct[$order['client_type']]='selected="selected"';

	print '<td valign="top"><a name="'.$order_id.'"></a><p>';
	// print 'Контроль через: <input type="text" name="control_time" value="'.$order['control_time'].'" size="2" /> дней<br/>';
	// if ($row['edit_time']!='') print 'Изменен: <big><strong>'.$order['edit_time'].'</strong></big><br/>';
	print 'Создан: '.$order['date_time'].'<br>Изменен: '.$order['edit_time'];
	if ($order['pay_time']) print '<br>Оплачен: '.$order['pay_time'];
	print '
	</p>
	
	<select class="form-control" name="status">
		<option value="3" '.$st[3].'>Новый заказ</option>
		<option value="2" '.$st[2].'>Отложили</option>
		<option value="5" '.$st[5].'>Отправлен в доставку</option>
		<option value="7" '.$st[7].'>Самовывоз</option>
		<option value="1" '.$st[1].'>Уведомить о наличии</option>
		<option value="0" '.$st[0].'>Закрыт успешно</option>
		<option value="8" '.$st[8].'>Возврат</option>
		<option value="4" '.$st[4].'>Не выполнен</option>
		<option value="9" '.$st[9].'>Спам</option>			
	</select>
	<!--
	<br>
	<input type="submit" name="button" id="button" value="Сохранить" />
	<input type="hidden" name="ID" value="'.$order_id.'" />
	</form>
	</td>-->';
	print '
	<!--<td valign="top">-->
	<form id="form1" name="form1" method="post" action="?param=orders&id='.$order_id.'">
	<input class="form-control" name="user_name" type="text" size="50" value="'.$order['name'].'" placeholder="Имя" /><br>
	<input class="form-control" name="user_email" type="text" size="50" value="'.$order['email'].'" placeholder="Email" /><br>
	<input class="form-control" name="user_phone" type="text" size="50" value="'.$order['phone'].'" placeholder="Телефон" /><br>
	<textarea class="form-control" cols="45" rows="4" name="user_adress">'.$order['adress'].'</textarea><br>
	<textarea class="form-control" cols="45" rows="4" name="user_comment">'.$order['comment'].'</textarea>';

	print ' Курьер: 
	<select class="form-control" name="delivery_company">
		<option value="0" '.$dl[0].'></option>
		<option value="1" '.$dl[1].'>СДЭК</option>
		<option value="2" '.$dl[2].'>ЭкспрессМ24</option>
		<option value="3" '.$dl[3].'>EMS</option>
		<option value="4" '.$dl[4].'>Почта России</option>
		<option value="5" '.$dl[5].'>СПСР</option>
	</select>
	<br>';
	print '<input class="form-control" name="delivery_num" type="text" value="'.$order['delivery_num'].'" placeholder="Номер доставки" /><br/>';
	print '<input class="form-control" name="delivery_price" type="text" value="'.$order['delivery_price'].'" placeholder="Цена доставки" /><br/>';
	print '<input class="form-control" name="outgo" type="text" value="'.$order['outgo'].'" placeholder="Доп. расходы" /><br/>';
	print '
	<select class="form-control" name="payment_status">
		<option value="0" '.$py[0].'>Не оплачен</option>
		<option value="1" '.$py[1].'>Оплачен наличными</option>
		<option value="2" '.$py[2].'>Оплачен безнал.</option>
		<option value="3" '.$py[3].'>Оплачен картой</option>
		<option value="4" '.$py[4].'>Оплачен по квитанции</option>
	</select>
	<br>';
	print '
	<select class="form-control" name="manager">
		<option value="0" '.$mg[0].'>Вероника</option>
		<option value="1" '.$mg[1].'>Скорода</option>			
	</select>
	<br>';	

	print '
	<select class="form-control" name="source">
		<option value="4" '.$sc[4].'>Нашли на Яндексе</option>
		<option value="5" '.$sc[5].'>Нашли в Гугле</option>
		<option value="6" '.$sc[6].'>На Яндекс.Маркет</option>
		<option value="7" '.$sc[7].'>От знакомых</option>
		<option value="8" '.$sc[8].'>Из соц.сети (VK, FB)</option>	
		<option value="0" '.$sc[0].'>Сайт</option>
		<option value="1" '.$sc[1].'>Звонок</option>
		<option value="2" '.$sc[2].'>Почта</option>
		<option value="3" '.$sc[3].'>Чат</option>
	</select>
	<br>';	
	print '
	<select class="form-control" name="client_type">
		<option value="0" '.$ct[0].'>Розница</option>
		<option value="1" '.$ct[1].'>Салон</option>
		<option value="2" '.$ct[2].'>Иглы</option>
		<option value="3" '.$ct[3].'>Мед. центр</option>
		<option value="4" '.$ct[4].'>Косметолог</option>	
		<option value="5" '.$ct[5].'>Опт</option>
		<option value="6" '.$ct[6].'>Черный список</option>
	</select>
	<br>';		



	print '<!--<input type="submit" name="button" id="button" value="Сохранить" /> 

	
	<input type="hidden" name="ID" value="'.$order_id.'" />-->

	<input type="hidden" name="user_edit" value="1" />
	
	<!--</form>-->

	</td>';
	print '<td valign="top">'.$itm;
	print 'Добавить <input placeholder="Название / ID / Артикул" class="form-control" type="text" name="add_subitem_name" value="" id="add_subitem_name" onkeyup="get_items_list('.$order_id.');" size="40">
	<!--<input type="button" onclick="get_items_list('.$order_id.');" value="Search">-->
	<div id="items_list"></div>';
	// print '<form method="post" action="">Добавить в заказ<br>
	// <!--ID: <input type="text" name="add_subitem_id" value="" id="add_subitem_id" onchange="find_item();" size="8">-->
	// ID: <input type="text" name="add_subitem_id" value="" id="add_subitem_id"  size="8">

	// Кол-во: <input type="text" name="add_count" value="1"  size="4">
	// <input type="submit" name="" value="Добавить">
	// <input type="hidden" name="ID" value="'.$order_id.'" />
	// </form>';
	print '<table id="add_items_table"></table>';
	$link='http://europrofcosmetic.ru/payment/?ordid='.$order_id.'&hash='.md5($order_id.$TOTAL);
	print '<p style="padding-top:20px; margin-top:20px; border-top:1px dashed #666;">Ссылка на оплату: <a href="'.$link.'" target="_blank">'.$link.'</a></p>';
	print '<a href="/control/print_order.php?id='.$order_id.'" target="_blank" class="btn btn-default"><span class="glyphicon glyphicon-print"></span> Распечатать накладную</a>';
	// print '<p align="right"><a href="?param=orders&delid='.$order_id.'" onclick="return confirm('."'Удалить?'".');">Удалить весь заказ</a></p>';
	// print '<td></td>';
	if ($order['epc_source']) print '<p>Откуда: <strong>'.$order['epc_source'].'</strong><br> Запрос: <strong>'.$order['epc_term'].'</strong></p>';
	print'</td></tr></table>
	<input type="submit" name="button" class="btn btn-success" id="button" value="Сохранить" /> <a href="?param=orders&neworder=1&from='.$order_id.'" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Создать новый на основе этого</a>
	<input type="hidden" name="ID" value="'.$order_id.'" />
	</form>
	</td></tr>';
}

?>