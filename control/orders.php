<div class="row">
	<div class="col-sm-3">
		<form method="post" action="" class="form-inline">
			<div class="form-group">
			      <input type="text" name="sword" class="form-control" placeholder="Номер заказа, Имя, номер телефона">
			 </div>
			<input type="submit"  value="Поиск" class="btn btn-default" />
			
		</form>
	</div>
	<div class="col-sm-3">
		<a href="?param=orders&neworder=1" class="btn btn-success" >Новый заказ</a>
	</div>

</div>

<ul class="nav nav-tabs" role="tablist">
  
<li><a href="?param=orders">Все</a> </li>
<li><a href="?param=orders&order=3">Новые</a></li>
<li><a href="?param=orders&order=2">Отложили</a></li>
<li><a href="?param=orders&order=5">Отправлен в доставку</a></li>
<li><a href="?param=orders&order=7">Самовывоз</a></li>
<li><a href="?param=orders&order=1">Уведомить о наличии</a></li>
<li><a href="?param=orders&order=0">Закрытые успешно</a> </li>
<li><a href="?param=orders&order=4">Проваленные</a>   </li>

</ul>

<!-- <div class="filters"  > -->


<!-- <a href="?param=orders&order=1">Не закрытые</a> -->
<!-- <a href="?param=orders&order=3">Требуют отправки</a> <a href="?param=orders&order=4">Требуют доставки</a> <a href="?param=orders&order=5">Ожадают денег </a>  -->
<!-- <a href="?param=orders&order=<? print $_GET['order']; ?>&ct=1">Clear Table </a> <a href="?param=orders&order=7">Все не закрытые </a> -->
<!-- </div> -->

<?
$admin=$_SESSION['USER_AUTH'];
if ($admin=='admin'){
	$ADMIN_ID=1;
}
elseif ($admin=='nika'){
	$ADMIN_ID=2;
}
elseif ($admin=='ksenia'){
	$ADMIN_ID=3;
}
print '<input type="hidden" id="admin_id" value="'.$ADMIN_ID.'" />';

function convert_charset($item)
{
	if ($unserialize = unserialize($item))
    {
    	foreach ($unserialize as $key => $value)
        {
        	$unserialize[$key] = @iconv('utf-8', 'koi8-r', $value);
        }
        $serialize = serialize($unserialize);
        return $serialize;
    }
    else
    {
    	return @iconv('utf-8', 'koi8-r', $item);
    }
}

if ($_GET['neworder']==1){
	$time=date("c",strtotime("+4 hours"));
	if ($_GET['from']>0){
		$from=$_GET['from'];
		$sql="CREATE TEMPORARY TABLE `foo` AS SELECT * FROM `shop_users` WHERE `ID` = '$from';";
		$result = mysql_query($sql) or die(mysql_error());
		$sql="UPDATE `foo` SET `ID`=NULL, `status`=3, `date_time`='$time', `edit_time`='$time', `payment_status`=0, `payment`='',`invoiceId`='',`pay_time`='',`outgo`='',`delivery_num`='', `manager`='$ADMIN_ID';";
		$result = mysql_query($sql) or die(mysql_error());
		$sql="INSERT INTO `shop_users` SELECT * FROM `foo`;";
		$result = mysql_query($sql) or die(mysql_error());
		$open_order_id=mysql_insert_id();
		$sql="DROP TABLE `foo`;";
		$result = mysql_query($sql) or die(mysql_error());
	}
	else {
		$sql="INSERT INTO `shop_users` (`status`,`date_time`,`manager`) VALUES ('3','$time','$ADMIN_ID')";
		$result = mysql_query($sql) or die(mysql_error());
		// $_POST['ID']=mysql_insert_id();
		$open_order_id=mysql_insert_id();
	}
	// print $sql;
	
}

if ($_GET['id']) {
	// $_POST['ID']=$_GET['id'];
	$open_order_id=$_GET['id'];
}

	//ADD ITEM TO ORDER
	if ($_POST['add_subitem_id']&&$_POST['ID']){

		$ID=$_POST['ID'];
		$new_item_id=$_POST['add_subitem_id'];
		 
		$new_count=$_POST['add_count'];
		if (!$count) $count=1;
		$sql="INSERT INTO `shop_orders` (`user_id`,`item_id`,`count`) VALUES ('$ID','$new_item_id','$new_count')";
		// print $sql;
		$result = mysql_query($sql) or die(mysql_error());
	}	

if ($_GET['delorderid']){
	$delid=$_GET['delorderid'];	
	$query = "DELETE FROM `shop_orders` WHERE `ID`='$delid'";
	mysql_query($query) or die(mysql_error());	
}


if ($_GET['delid']){
	
	// $delid=$_GET['delid'];
	// $query = "DELETE FROM `shop_users` WHERE `ID`='$delid'";
	// mysql_query($query) or die(mysql_error());
	
	// $query = "DELETE FROM `shop_orders` WHERE `user_id`='$delid'";
	// mysql_query($query) or die(mysql_error());
	// print 'Заказ удален.';	
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
	foreach ($item_count as $order_id => $count) {
		$sql="UPDATE `shop_orders` SET `count`='$count' WHERE `ID`='$order_id'";
		$result = mysql_query($sql) or die(mysql_error());			
	}
}
 		
if ($_POST['ID']>0){
	$ID=$_POST['ID'];
	$status= $_POST['status'];		

	//////////////
	if ($_POST['mail_sorry']||$_POST['mail_item']||$_POST['mail_city']||$_POST['mail_send']||$_POST['email_add_text']){
		$email_title='Информация по заказу # '.$_POST['ID'].' @ onemoreshop.ru';

		$title=convert_charset($email_title);

		$headers  = 'MIME-Version: 1.0
		Content-type: text/html; charset=koi8-r
		From: ONEMORE Shop <onemoreshop@gmail.com>
		';

		if ($_POST['mail_sorry']==1) $content.='Приносим свои извинения за задержку с ответом.<br />';

		if ($_POST['mail_item']==2)$content.='Данный товар есть в наличии и может быть доставлен. <br />';
		if ($_POST['mail_item']==1)$content.='К сожалению в данный момент этого товара нет на складе. Возможно Вас заинтересуют какие-то альтернативные варианты или мы можем что-то посоветывать.<br />';
		if ($_POST['mail_item']==3)$content.='В данный момент этого товара нет на складе, но его поступление ожидается в ближайшее время. Мы можем сообщить о его появлении дополнительно.<br />';

		if ($_POST['mail_city']==1)$content.='Дождитесь звонка оператора или свяжитесь с нами, чтобы уточнить удобнове время и способ доставки (по Петербургу). <br />';
		if ($_POST['mail_city']==2)$content.='Пришлите пожалуйста точный почтовый адрес: Индекс, Город, Улица, Дом, Корпус(если есть), Квартира; а также Фамилию, Имя, Отчество получателя. <br />
		Обращаем Ваше внимание, что к стоимости заказа будет прибавлена стоимость почтовых сборов за доставку (200-400 руб в зависимости от веса и дальности пересылки).<br />';

		if ($status!=1) $status=2;

		if ($_POST['mail_send']){ $content.='Товар был успешно отправлен. <br />
		Текущее состояние посылки можно отслеживать на сайте Почты России  http://www.russianpost.ru/rp/servise/ru/home/postuslug/trackingpo <br />
		Ваш Почтовый идентификатор: '.$_POST['mail_send'].'<br />';
		 $status=1;
	}

	if ($_POST['email_add_text']){ $content.='<br />'.$_POST['email_add_text'].'<br />';}

	$message=convert_charset($email_text);

	$adress=$_POST['user-email'];
	$ok=mail($adress,$title,$message,$headers);			
	print 'Письмо отправлено. ';
	}
	///////////////
	// $edit_time=date("d.m.y H:i");
		
	if (isset($status)){
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

		$sql="UPDATE `shop_users` SET `name`='$name',`email`='$email',`phone`='$phone',`adress`='$adress',`comment`='$comment', `delivery_company`='$delivery_company', `delivery_num`='$delivery_num', `delivery_price`='$delivery_price', `payment_status`='$payment_status', `manager`='$manager', `outgo`='$outgo', `source`='$source', `client_type`='$client_type' $PAYTIME WHERE `ID`='$ID'";
		$result = mysql_query($sql) or die(mysql_error());
	}
 

 

	//DEL ITEM FROM ORDER
	if ($_POST['del_item']){
		$del_item=$_POST['del_item'];
		// print_r ($del_item);
		for ($i=0;$i<count($del_item);$i++){
			$query = "DELETE FROM `shop_orders` WHERE `item_id`='$del_item[$i]'";
			mysql_query($query) or die(mysql_error());
		}
	}
	
		
		// print 'Заказ сохранен.';		
}
	
$pay_status[0]="<span class='glyphicon glyphicon-warning-sign' style='color:#900;'></span> Не оплачен";
$pay_status[1]="<span class='glyphicon glyphicon-thumbs-up' style='color:#090;'></span> Оплачен наличными";
$pay_status[2]="<span class='glyphicon glyphicon-thumbs-up' style='color:#090;'></span> Оплачен безнал";
$pay_status[3]="<span class='glyphicon glyphicon-thumbs-up' style='color:#090;'></span> Оплачен картой";
$pay_status[4]="<span class='glyphicon glyphicon-thumbs-up' style='color:#090;'></span> Оплачен по квитанции";

///////// SELECT ITEMS FROM BASE

	$CT=0;
	if ($_GET['ct']==1){
		$CT=1;
	}
	
	if (isset($_GET['order'])){ 
		$ORDER='WHERE shop_users.status='.$_GET['order']; 
		$get_order='&order='.$_GET['order']; 
	}

	if ($_POST['sword']) $ORDER="WHERE (shop_users.name LIKE '%".$_POST['sword']."%') OR (shop_users.delivery_num LIKE '%".$_POST['sword']."%') OR (shop_users.phone LIKE '%".$_POST['sword']."%')  OR (shop_users.email LIKE '%".$_POST['sword']."%') OR (shop_users.ID LIKE '%".$_POST['sword']."%') OR (shop_users.delivery_num LIKE '%".$_POST['sword']."%')";


	if (!$ORDER) {
		if ($ADMIN_ID!=1) $ORDER="WHERE (shop_users.manager=$ADMIN_ID OR shop_users.manager=0)";
		$LIMIT=' LIMIT 50';
	}
	elseif ($ORDER&&!$_POST['sword']) {
		if ($ADMIN_ID!=1) $ORDER.=" AND (shop_users.manager=$ADMIN_ID OR shop_users.manager=0)";
	}
	
	//$sql="select * from `shop_users` ".$ORDER." ORDER BY ID DESC".$LIMIT;
	if ($_POST['find_item_id']!=''){
		$flt='AND shop_catalog.ID='.$_POST['find_item_id'];
		$LIMIT='';
	}
	if ($_POST['sql_string']!=''){
		$sql_string=$_POST['sql_string'];
		$LIMIT='';
	}


	// else $sql="SELECT * FROM `shop_users` WHERE status!=111 AND status!=555 ORDER BY ID DESC".$LIMIT;
	
	// AND shop_users.status!=111 AND shop_users.status!=555 
	// $sql="SELECT shop_users.ID, shop_users.name, shop_users.email, shop_users.phone, shop_users.adress, shop_users.comment, shop_users.status, shop_users.date_time, shop_users.edit_time, shop_users.control_time, shop_orders.ID, shop_orders.item_id, shop_orders.color, shop_orders.size, shop_catalog.name, shop_catalog.slug, shop_catalog.price FROM shop_users, shop_orders,shop_catalog WHERE shop_orders.user_id=shop_users.ID AND shop_catalog.ID=shop_orders.item_id $sql_string $flt $ORDER ORDER BY shop_users.ID DESC".$LIMIT;
	$sql="SELECT * FROM shop_users $ORDER ORDER BY shop_users.ID DESC".$LIMIT;
	// print $sql;	
	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$orders[$row[0]]['name']=$row[1];
		$orders[$row[0]]['email']=$row['email'];
		$orders[$row[0]]['phone']=$row['phone'];
		$orders[$row[0]]['adress']=$row['adress'];
		$orders[$row[0]]['comment']=$row['comment'];
		$orders[$row[0]]['status']=$row['status'];
		$orders[$row[0]]['date_time']=$row['date_time'];
		$orders[$row[0]]['edit_time']=$row['edit_time'];

		$orders[$row[0]]['delivery_company']=$row['delivery_company'];
		$orders[$row[0]]['delivery_num']=$row['delivery_num'];
		$orders[$row[0]]['delivery_price']=$row['delivery_price'];
		$orders[$row[0]]['outgo']=$row['outgo'];
		$orders[$row[0]]['payment_status']=$row['payment_status'];

		$orders[$row[0]]['pay_time']=$row['pay_time'];
		$orders[$row[0]]['manager']=$row['manager'];
		$orders[$row[0]]['source']=$row['source'];
		$orders[$row[0]]['client_type']=$row['client_type'];

		$orders[$row[0]]['epc_source']=$row['epc_source'];
		$orders[$row[0]]['epc_term']=$row['epc_term'];


		// $orders[$row[0]]['control_time']=$row['control_time'];
		// $orders[$row[0]]['items'][$row[10]]['item_id']=$row['item_id'];
		// $orders[$row[0]]['items'][$row[10]]['name']=$row[14];
		// $orders[$row[0]]['items'][$row[10]]['slug']=$row['slug'];
		// $orders[$row[0]]['items'][$row[10]]['color']=$row['color'];
		// $orders[$row[0]]['items'][$row[10]]['size']=$row['size'];
		// $orders[$row[0]]['items'][$row[10]]['price']=$row['price'];
	}
	// print_r($orders);
?>	
<!-- <form id="form1" name="form1" method="post" action="">
		Item ID: <input type="text" name="find_item_id" value="<? print $_POST['find_item_id']; ?>" id="" size="10">
		SQL: <input type="text" name="sql_string" value="<? print $_POST['sql_string']; ?>" id="" size="30">
		<input type="submit" name="" value="Find"> Результатов: 
<? print count($orders); ?>		
	</form> -->
	<input type="hidden" id="order_id" value="<? print $_POST['ID']; ?>">
	<table class="table table-bordered ">	

<?	
$mng[0]='Нет менеджера';
$mng[1]='Света';
$mng[2]='Вероника';
$mng[3]='Ксения';
	foreach ($orders as $order_id => $order) {	 
		if ($_POST['ID']==$order_id) {
			print '<tr id="showrow'.$order_id.'">';
			$hidden='<tr id="order'.$order_id.'" style="display:none;">';
		}
		else print '<tr id="order'.$order_id.'">';
		$s_class='';
		if ($order['status']=='0') $s_class='st s0';
		else if ($order['status']==1) $s_class= 'st s1';
		else if ($order['status']==2) $s_class= 'st s2';
		else if ($order['status']==3) $s_class= 'st s3';
		else if ($order['status']==4) $s_class= 'st s4';
		else if ($order['status']==5) $s_class= 'st s5';
		else if ($order['status']==6) $s_class= 'st s6';
		else if ($order['status']==7) $s_class= 'st s7';
		
		if ($CT!=1) {
			if ($_POST['ID']==$order_id) {
				print '<td valign="top" class="status"><a href="javascript:{}" onclick="hide_order('.$order_id.')">'.$order_id.' <span class="glyphicon glyphicon-chevron-up"></span></a></td>';
				$hidden.= '<td class="status '.$s_class.'" valign="top"><a href="javascript:{};" onclick="get_order('.$order_id.');">'.$order_id.' <span class="glyphicon glyphicon-resize-full"></span></a> </td>';
				
			}
			else {
				print '<td class="status '.$s_class.'" valign="top"><a href="javascript:{};" onclick="get_order('.$order_id.');">'.$order_id.' <span class="glyphicon glyphicon-resize-full"></span></a> </td>';
			}

			
		}
		else print '<td >'.$order_id.'</td>';
		
		
		//$user_id=$order_id;
		//print $user_id;

		// $sql2="SELECT shop_orders.ID, shop_orders.user_id, shop_orders.item_id, shop_orders.color, shop_orders.size, shop_catalog.name FROM shop_orders,shop_catalog WHERE shop_orders.user_id=$user_id AND shop_catalog.ID=shop_orders.item_id";
		// $result2 = mysql_query($sql2) or die(mysql_error());	
		
		$items='';

		// $user_id=$_POST['ID'];
		$user_id=$order_id;
		

		if ($user_id) {
			unset($item);
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

			$itm='<form id="form1" name="form1" method="post" action=""><table width="100%">';
			$itm_='<table width="100%" style="font-size:12px;">';
			// $itm.='<tr><th>Название</th><th>Артикул</th><th>Цена</th><th>Кол-во</th><th>Итого</th><th>Закупка</th></tr>';
			$i=1;
			$TTL=0;
			foreach ($item as $id => $v) {
				$ttl=$v['count']*$v['price'];
				$itm.='<tr><td>'.$i.'</td><td>'.$v['subitem_id'].'</td><td> '.$v['name'].' '.$v['article'].'</td><td>'.$v['price'].' руб.</td>';
				$itm.='<td><input name="item_count['.$id.']" type="text" size="3" value="'.$v['count'].'" /></td>';
				$itm.='<td>'.$ttl.' руб.</td>';
				$itm.='<td><input name="shop_price['.$v['subitem_id'].']" type="text" size="7" value="'.$v['shop_price'].'" /></td>';
				$itm.='<td><a href="?param=orders&id='.$order_id.'&delorderid='.$id.'" onclick="return confirm('."'Удалить?'".');"><span class="glyphicon glyphicon-remove"></span></a></td>';
				$itm.='</tr>';
				$itm_.='<tr><td>'.$i.'</td><td>'.$v['subitem_id'].'</td><td> '.$v['name'].' '.$v['article'].'</td></tr>';
				$TTL+=$ttl;
				$i++;
			}
			$itm_.='</table>';
			$itm.='<tr><td colspan="5" align="right">Итого: <strong>'.$TTL.' руб.</strong></td><td><input type="hidden" name="ID" value="'.$order_id.'" /><input type="submit" value="Save" /></td></tr></table></form>';
		}

		$TOTAL=$TTL+$order['delivery_price'];
		
		if ($_POST['ID']==$order_id){

			print '<td colspan="7" class="selected"><table class="sl" width="100%"><tr>';
			$st[$order['status']]='selected="selected"';

			$dl[$order['delivery_company']]='selected="selected"';
			$py[$order['payment_status']]='selected="selected"';

			$mg[$order['manager']]='selected="selected"';
			$sc[$order['source']]='selected="selected"';
			$ct[$order['client_type']]='selected="selected"';

			print '<td valign="top"><a name="'.$order_id.'"></a><p>';
			// print 'Контроль через: <input type="text" name="control_time" value="'.$order['control_time'].'" size="2" /> дней<br/>';
			// if ($row['edit_time']!='') print 'Изменен: <big><strong>'.$order['edit_time'].'</strong></big><br/>';
			print 'Сознан: '.$order['date_time'].'<br>Изменен: '.$order['edit_time'];
			if ($order['pay_time']) print '<br>Оплачен: '.$order['pay_time'];
			print '
			</p>
			<form id="form1" name="form1" method="post" action="">
			<select name="status">
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
			<br>
			<input type="submit" name="button" id="button" value="Сохранить" />
			<input type="hidden" name="ID" value="'.$order_id.'" />
			</form>
			</td>';
			print '<td valign="top">
			<form id="form1" name="form1" method="post" action="">
			<input name="user_name" type="text" size="50" value="'.$order['name'].'" /> имя<br/>
			<input name="user_email" type="text" size="50" value="'.$order['email'].'" /> email<br/>
			<input name="user_phone" type="text" size="50" value="'.$order['phone'].'" /> телефон<br/>		
			<textarea cols="45" rows="4" name="user_adress">'.$order['adress'].'</textarea><br />
			<textarea cols="45" rows="4" name="user_comment">'.$order['comment'].'</textarea><br />';

			print ' Курьер: 
			<select name="delivery_company">
				<option value="0" '.$dl[0].'></option>
				<option value="1" '.$dl[1].'>СДЭК</option>
				<option value="2" '.$dl[2].'>ЭкспрессМ24</option>
				<option value="3" '.$dl[3].'>EMS</option>
				<option value="4" '.$dl[4].'>Почта России</option>
				<option value="5" '.$dl[5].'>СПСР</option>
			</select>
			<br>';
			print 'Номер доставки:<input name="delivery_num" type="text" value="'.$order['delivery_num'].'" /><br/>';
			print 'Цена доставки: <input name="delivery_price" type="text" value="'.$order['delivery_price'].'" /><br/>';
			print 'Доп.расходы: <input name="outgo" type="text" value="'.$order['outgo'].'" /><br/>';
			print 'Статус оплаты: 
			<select name="payment_status">
				<option value="0" '.$py[0].'>Не оплачен</option>
				<option value="1" '.$py[1].'>Оплачен наличными</option>
				<option value="2" '.$py[2].'>Оплачен безнал.</option>
				<option value="3" '.$py[3].'>Оплачен картой</option>
				<option value="4" '.$py[4].'>Оплачен по квитанции</option>
			</select>
			<br>';
			print 'Менеджер: 
			<select name="manager">
				<option value="0" '.$mg[0].'>Вероника</option>
				<option value="1" '.$mg[1].'>Света</option>			
			</select>
			<br>';	
			print 'Источник: 
			<select name="source">
				<option value="4" '.$sc[4].'>Нашли на Яндексе</option>
				<option value="5" '.$sc[5].'>Нашли в Гугле</option>
				<option value="6" '.$sc[6].'>На Яндекс.Маркет</option>
				<option value="7" '.$sc[7].'>От знакомых</option>
				<option value="0" '.$sc[0].'>Сайт</option>
				<option value="1" '.$sc[1].'>Звонок</option>
				<option value="2" '.$sc[2].'>Почта</option>
				<option value="3" '.$sc[3].'>Чат</option>
			</select>
			<br>';	

			print 'Статус клиента: 
			<select name="client_type">
				<option value="0" '.$ct[0].'>Розница</option>
				<option value="1" '.$ct[1].'>Салон</option>
				<option value="2" '.$ct[2].'>Иглы</option>
				<option value="3" '.$ct[3].'>Мед. центр</option>
				<option value="4" '.$ct[4].'>Косметолог</option>	
				<option value="5" '.$ct[5].'>Опт</option>
				<option value="6" '.$ct[6].'>Черный список</option>
			</select>
			<br>';	


			print '<input type="submit" name="button" id="button" value="Сохранить" />
			<input type="hidden" name="user_edit" value="1" />
			<input type="hidden" name="ID" value="'.$order_id.'" />
			</form></td>';
			print '<td>'.$itm;
			print '<form method="post" action="">Добавить в заказ.
			ID: <input type="text" name="add_subitem_id" value=""  size="8">
			Кол-во: <input type="text" name="add_count" value="1"  size="4">
			<input type="submit" name="" value="Добавить">
			<input type="hidden" name="ID" value="'.$order_id.'" />
			</form>';
			$link='http://europrofcosmetic.ru/payment/?ordid='.$order_id.'&hash='.md5($order_id.$TOTAL);
			print '<p>Ссылка на оплату: <a href="'.$link.'" target="_blank">'.$link.'</a></p>';
			// print '<p align="right"><a href="?param=orders&delid='.$order_id.'" onclick="return confirm('."'Удалить?'".');">Удалить весь заказ</a></p>';
			// print '<td></td>';
			if ($order['epc_source']) print '<p>Откуда: <strong>'.$order['epc_source'].'</strong><br> Запрос: <strong>'.$order['epc_term'].'</strong></p>';
			print'</td></tr></table>
			</td>';
			
			if ($order['edit_time']!='') $hidden.= '<td>'.date("d.m.y H:i",strtotime($order['date_time'])).'</td>';
			else $hidden.= '<td>'.$order['date_time'].'</td>';
			// $hidden.= '<td>'.$items.'</td>';
			$hidden.= '<td>'.$order['comment'].'</td>';
			$hidden.= '<td>'.$order['name'].'</td>';
			$hidden.= '<td>'.$order['email'].'</td><td>'.$order['phone'].'</td>';
			$hidden.= '<td>'.$order['adress'].'</td>';
			// if ($CT!=1) $hidden.= '<td><a href="?param=orders&delid='.$order_id.'" onclick="return confirm('."'Удалить?'".');"><span class="glyphicon glyphicon-remove"></span></a></td>';			

		}
		else {
			if ($order['edit_time']!='') print '<td>'.date("d.m.y H:i",strtotime($order['date_time'])).'</td>';
			else print '<td>'.$order['date_time'].'</td>';
			// print '<td>'.$items.'</td>';
			print '<td><p style="margin-bottom:5px; padding-bottom:5px; border-bottom:1px dashed #CCC;">'.$order['adress'].'</p>'.$order['comment'].'</td>';
			print '<td>'.$pay_status[$order['payment_status']].'</td>';
			if (strlen($order['phone'])==10) $call='+7'.$order['phone'];
			else $call=$order['phone'];
			print '<td align="center">'.$call.' <a href="tel:'.$call.'"><span class="glyphicon glyphicon-phone"></span></a> <a href="callto:'.$call.'"><span class="glyphicon glyphicon-headphones"></span></a><p>'.$order['name'].'</p></td>';
			print '<td>'.$mng[$order['manager']].'</td>';
			// print '<td>'.$order['email'].'</td>';

			// print '<td>'.$order['adress'].'</td>';
			print '<td>'.$itm_.'</td>';
			// if ($CT!=1) print '<td><a href="?param=orders&delid='.$order_id.'" onclick="return confirm('."'Удалить?'".');"><span class="glyphicon glyphicon-remove"></span></a></td>';
		}
		print '</tr>';
		$hidden.='</tr>';
		print $hidden;
		
	}
	print '</table>';
	if ($open_order_id>0) print '<script>get_order('.$open_order_id.');</script>';
?>