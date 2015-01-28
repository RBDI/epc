<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db(DB_NAME, $db1);
	mysql_query('SET NAMES utf8');

$sql="SELECT * FROM shop_params";
$result = mysql_query($sql) or die(mysql_error());

while ($row=mysql_fetch_array($result)) {
	$params[$row['ID']]=$row;
}

function b_name($id, $params)
{
	if ($params[$id]['parent']!=0) {
		return b_name($params[$id]['parent'],$params);
	}
	else {
		return $params[$id]['name'];
	}

}

$sql="SELECT * FROM shop_users WHERE status=3 ORDER BY id DESC";
$result = mysql_query($sql) or die(mysql_error());
if (!$result) die();

while ($row=mysql_fetch_array($result)) {
	$order[$row['ID']]['name']=$row[1];
	$order[$row['ID']]['email']=$row['email'];
	$order[$row['ID']]['phone']=$row['phone'];
	$order[$row['ID']]['adress']=$row['adress'];
	$order[$row['ID']]['comment']=$row['comment'];
	$order[$row['ID']]['status']=$row['status'];
	$order[$row['ID']]['date_time']=$row['date_time'];
	$order[$row['ID']]['edit_time']=$row['edit_time'];

	$order[$row['ID']]['delivery_company']=$row['delivery_company'];
	$order[$row['ID']]['delivery_num']=$row['delivery_num'];
	$order[$row['ID']]['delivery_date']=$row['delivery_date'];
	$order[$row['ID']]['delivery_time']=$row['delivery_time'];
	$order[$row['ID']]['delivery_price']=$row['delivery_price'];
	$order[$row['ID']]['outgo']=$row['outgo'];
	$order[$row['ID']]['payment_status']=$row['payment_status'];

	$order[$row['ID']]['pay_time']=$row['pay_time'];
	$order[$row['ID']]['manager']=$row['manager'];
	$order[$row['ID']]['source']=$row['source'];
	$order[$row['ID']]['client_type']=$row['client_type'];

	$order[$row['ID']]['epc_source']=$row['epc_source'];
	$order[$row['ID']]['epc_term']=$row['epc_term'];
}

$xml= '<?xml version="1.0" encoding="UTF-8"?>
<КоммерческаяИнформация ВерсияСхемы="2.03" ДатаФормирования="'.date('Y-m-d').'">';

foreach ($order as $user_id => $order_val) {



	
	$xql="SELECT shop_orders.item_id, shop_subitem.item_id, shop_subitem.name, shop_subitem.value1, shop_subitem.value2,shop_subitem.value3, shop_subitem.value4, shop_orders.count, shop_catalog.name,shop_subitem.ID,shop_orders.ID,shop_orders.discount,shop_subitem.in_stock,shop_orders.price, shop_catalog.brand, shop_catalog.type, shop_subitem.1C_ID  FROM  shop_orders, shop_subitem, shop_catalog WHERE shop_orders.item_id=shop_subitem.ID AND shop_orders.user_id=$user_id AND shop_catalog.ID=shop_subitem.item_id";
	$xresult = mysql_query($xql) or die(mysql_error());
	while ($xrow=mysql_fetch_array($xresult)) {
		$item[$xrow[10]]['name']=b_name($xrow['brand'], $params).' '.$xrow[8];
		// if ($xrow[2]) $item[$xrow[10]]['name'].= ' ('.$xrow[2].')';
		$item[$xrow[10]]['article']=$xrow[5];
		$item[$xrow[10]]['count']=$xrow[7];
		$item[$xrow[10]]['subitem_id']=$xrow[9];
		$item[$xrow[10]]['item_id']=$xrow[10];
		$item[$xrow[10]]['1C_ID']=$xrow['1C_ID'];
		$item[$xrow[10]]['in_stock']=$xrow['in_stock'];
		$item[$xrow[10]]['discount']=$xrow[11];
		if ($xrow['value2']) $item[$xrow[10]]['price']=$xrow['value2'];
		else $item[$xrow[10]]['price']=$xrow['value1'];
		if ($xrow['price']>0) $item[$xrow[10]]['price']=$xrow['price'];

		$item[$xrow[10]]['shop_price']=$xrow['value4'];
	}

	
	$i=1;
	$TTL=0;
$xml3='';
if (count($item)>0)	{
$xml3= '		<Товары>';
	foreach ($item as $id => $v) {
		if ($v['discount']) $discount=(100-$v['discount'])/100;
		else $discount=1;
		$ttl=$v['count']*$v['price']*$discount;
		$stock='';
$xml3.= '
			<Товар>
				<Ид>'.$v['1C_ID'].'</Ид>
				<ИдКаталога>'.$v['subitem_id'].'</ИдКаталога>
				<Наименование>'.$v['name'].'</Наименование>
				<БазоваяЕдиница Код="796" НаименованиеПолное="Штука" МеждународноеСокращение="PCE">шт</БазоваяЕдиница>
				<ЦенаЗаЕдиницу>'.$v['price'].'.00</ЦенаЗаЕдиницу>
				<Количество>'.$v['count'].'.00</Количество>
				<Сумма>'.$ttl.'.00</Сумма>
				<ЗначенияРеквизитов>
					<ЗначениеРеквизита>
						<Наименование>ВидНоменклатуры</Наименование>
						<Значение>Товар</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>ТипНоменклатуры</Наименование>
						<Значение>Товар</Значение>
					</ЗначениеРеквизита>
				</ЗначенияРеквизитов>
			</Товар>
';
		$TTL+=$ttl;
		$i++;
	}
}
if ($order['delivery_price']>0)	{
	$TTL+=$order['delivery_price'];
$xml3.= '
			<Товар>
				<Ид>ORDER_DELIVERY</Ид>
				<Наименование>Доставка заказа</Наименование>
				<БазоваяЕдиница Код="796" НаименованиеПолное="Штука" МеждународноеСокращение="PCE">шт</БазоваяЕдиница>
				<ЦенаЗаЕдиницу>'.$order['delivery_price'].'.00</ЦенаЗаЕдиницу>
				<Количество>1</Количество>
				<Сумма>'.$order['delivery_price'].'.00</Сумма>
				<ЗначенияРеквизитов>
					<ЗначениеРеквизита>
						<Наименование>ВидНоменклатуры</Наименование>
						<Значение>Услуга</Значение>
					</ЗначениеРеквизита>
					<ЗначениеРеквизита>
						<Наименование>ТипНоменклатуры</Наименование>
						<Значение>Услуга</Значение>
					</ЗначениеРеквизита>
				</ЗначенияРеквизитов>
			</Товар>';
}

$xml.= '
	<Документ>
		<Ид>'.$user_id.'</Ид>
		<Номер>'.$user_id.'</Номер>
		<Дата>'.date("Y-m-d",strtotime($order_val['date_time'])).'</Дата>
		<ХозОперация>Заказ товара</ХозОперация>
		<Роль>Продавец</Роль>
		<Валюта>руб</Валюта>
		<Курс>1</Курс>
		<Сумма>'.$TTL.'.00</Сумма>
		<Контрагенты>
			<Контрагент>
				<Ид></Ид>
				<Наименование>'.$order_val['name'].'</Наименование>
				<Роль>Покупатель</Роль>
				<ПолноеНаименование>'.$order_val['name'].'</ПолноеНаименование>
				<Фамилия></Фамилия>
				<Имя>'.$order_val['name'].'</Имя>
				<АдресРегистрации>
					<Представление>'.$order_val['adress'].'</Представление>
					<АдресноеПоле>
						<Тип>Почтовый индекс</Тип>
						<Значение></Значение>
					</АдресноеПоле>
					<АдресноеПоле>
						<Тип>Улица</Тип>
						<Значение></Значение>
					</АдресноеПоле>
				</АдресРегистрации>
				<Контакты>
					<Контакт>
						<Тип>Телефон</Тип>
						<Значение>+7'.$order_val['phone'].'</Значение>
					</Контакт>
					<Контакт>
						<Тип>Почта</Тип>
						<Значение>'.$order_val['email'].'</Значение>
					</Контакт>					
				</Контакты>
				<Представители>
					<Представитель>
						<Контрагент>
							<Отношение>Контактное лицо</Отношение>
							<Ид>'.$user_id.'</Ид>
							<Наименование>'.$order_val['name'].'</Наименование>
						</Контрагент>
					</Представитель>
				</Представители>
			</Контрагент>
		</Контрагенты>
		<Время>'.date("H:i:s",strtotime($order_val['date_time'])).'</Время>
		<Комментарий>'.$order_val['comment'].'</Комментарий>
';
if ($xml3!='') $xml.=$xml3.'
		</Товары>';

$xml.= '		<ЗначенияРеквизитов>
			<ЗначениеРеквизита>
				<Наименование>Метод оплаты</Наименование>
				<Значение>Наличный расчет</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Заказ оплачен</Наименование>
				<Значение>false</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Доставка разрешена</Наименование>
				<Значение>false</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Отменен</Наименование>
				<Значение>false</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Финальный статус</Наименование>
				<Значение>false</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Статус заказа</Наименование>
				<Значение>[N] Принят</Значение>
			</ЗначениеРеквизита>
			<ЗначениеРеквизита>
				<Наименование>Дата изменения статуса</Наименование>
				<Значение>'.$order_val['edit_time'].'</Значение>
			</ЗначениеРеквизита>
		</ЗначенияРеквизитов>
	</Документ>		
';	
}
$xml.='</КоммерческаяИнформация>';
header ( "Content-type: text/xml; charset=utf-8" );
print "\xEF\xBB\xBF";
print $xml;
	// $filename='orders.xml';
	// $f = fopen($filename, 'w');
	// fwrite($f, $xml);
	// fclose($f);
?>