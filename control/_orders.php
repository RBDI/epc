<div class="filters">
<a href="?param=orders">Все</a> <a href="?param=orders&order=1">Не закрытые</a> <a href="?param=orders&order=2">Новые</a> <a href="?param=orders&order=3">Требуют отправки</a> <a href="?param=orders&order=4">Требуют доставки</a> <a href="?param=orders&order=5">Ожадают денег </a>
</div>
<?

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


if ($_GET['delid']){
	$delid=$_GET['delid'];
	$query = "DELETE FROM `shop_users` WHERE `ID`='$delid'";
	mysql_query($query) or die(mysql_error());
	
	$query = "DELETE FROM `shop_orders` WHERE `user_id`='$delid'";
	mysql_query($query) or die(mysql_error());
	print 'Заказ удален.';	
}
?>

	
<form id="form1" name="form1" method="post" action="">
  <?		
		
	if ($_POST['ID']>0){
		$ID=$_POST['ID'];
		$status= $_POST['status'];
		$comment= $_POST['comment'];	

//////////////
if ($_POST['mail_sorry']||$_POST['mail_item']||$_POST['mail_city']||$_POST['mail_send']||$_POST['email_add_text']){
$email_title='Информация по заказу # '.$_POST['ID'].' @ onemoreshop.ru';

$title=convert_charset($email_title);
// To send HTML mail, the Content-type header must be set
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


$email_text='
Добрый день!<br />

'.$content.'

Спасибо за заказ!<br />
-- <br />
Всего наилучшего,<br />
Интернет-магазин ONEMORE Shop<br />
<br />
+7 (812) 939-30-25<br />
www.onemoreshop.ru<br />
http://vkontakte.ru/club11731165<br />
';
$message=convert_charset($email_text);

$adress=$_POST['email'];
$ok=mail($adress,$title,$message,$headers);			
print 'Письмо отправлено. ';

}
///////////////

			
		$sql="UPDATE `shop_users` SET `status`='$status',`comment`='$comment' WHERE `ID`='$ID'";
		$result = mysql_query($sql) or die(mysql_error());
		
//DEL ITEM FROM ORDER
	if ($_POST['del_item']){
		$del_item=$_POST['del_item'];
		print_r ($del_item);
		for ($i=0;$i<count($del_item);$i++){
			$query = "DELETE FROM `shop_orders` WHERE `item_id`='$del_item[$i]'";
			mysql_query($query) or die(mysql_error());
		}
	}
//ADD ITEM TO ORDER
	if ($_POST['add_item']){
		$item_id=$_POST['add_item'];
		$size=$_POST['add_item_size'];
		$color=$_POST['add_item_color'];
		$sql="insert into `shop_orders` (`user_id`,`item_id`,`count`,`color`,`size`,`is`) values ('$ID','$item_id','1','$color','$size','0')";
		
		
		$result = mysql_query($sql) or die(mysql_error());		
	}		
		
		print 'Заказ сохранен.';		
	}
	

///////// SELECT ITEMS FROM BASE
	
	if ($_GET['order']){ $ORDER='WHERE status'; $get_order='&order='.$_GET['order']; }
	if ($_GET['order']==1){ $ORDER.='!=0 AND status!=4'; }
	elseif ($_GET['order']==2){ $ORDER.='=3'; }
	elseif ($_GET['order']==3){ $ORDER.='=5'; }
	elseif ($_GET['order']==4){ $ORDER.='=7'; }
	elseif ($_GET['order']==5){ $ORDER.='=1'; }
	
//	if (!$ORDER) $LIMIT=' LIMIT 50';
	
	$sql="select * from `shop_users` ".$ORDER." ORDER BY ID DESC".$LIMIT;	
	$result = mysql_query($sql) or die(mysql_error());
	
	print '<table class="ord_table">';
	while ($row=mysql_fetch_array($result)) {
		print '<tr>';
		if ($row['status']=='0') $s_class='s0';
		else if ($row['status']==1) $s_class= 's1';
		else if ($row['status']==2) $s_class= 's2';
		else if ($row['status']==3) $s_class= 's3';
		else if ($row['status']==4) $s_class= 's4';
		else if ($row['status']==5) $s_class= 's5';
		else if ($row['status']==6) $s_class= 's6';
		else if ($row['status']==7) $s_class= 's7';
		
		print '<td class="status '.$s_class.'"><a href="?param=orders'.$get_order.'&id='.$row['ID'].'">'.$row['ID'].'</a></td>';
		
		
		$user_id=$row['ID'];
		//print $user_id;

		$sql2="select * from `shop_orders` WHERE `user_id`=$user_id";	
		$result2 = mysql_query($sql2) or die(mysql_error());	
		$items='';
		while ($row2=mysql_fetch_array($result2)) {			
			if ($_GET['id']==$user_id){
	
				$items.='<tr>';
				$item_id=$row2['item_id'];
				$catalog_item=mysql_fetch_array(mysql_query("select * from `shop_catalog` WHERE `ID`='$item_id' LIMIT 1"));
	
				if ($row2['color']>0){
					$color=$row2['color'];
					$sql3="select * from `shop_img` WHERE `id`=$color";	
				}
				else{				
					
					$sql3="select * from `shop_img` WHERE `item_id`=$item_id LIMIT 1";	
				}
				
				$result3 = mysql_query($sql3) or die(mysql_error());	

				while ($row3=mysql_fetch_array($result3)) {
					$pic='<td><a href="/shop/products/'.$catalog_item['slug'].'" target="_blank"><img border="0" src="/products/'.$row3['filename'].'_small.jpg" align="middle"></a></td>';
				}
/*				
				$sqlz="select * from `shop_size_count` WHERE `item_id`=$item_id";	
				$resultz = mysql_query($sqlz) or die(mysql_error());	
				$size_count='';
				while ($rowz=mysql_fetch_array($resultz)) {
					$size_count.='<br /><b>'.$rowz['size'].'</b> - '.$rowz['count'];
				}
*/				
	

				
				
				if ($row2['is']=='') $is='<span class="is0">Не проверено</span>';
				if ($row2['is']==false) $is='<span class="is1">Нет в наличии</span>';
				if ($row2['is']==true) $is='<span class="is2">Есть на складе</span>';								
				$price=$catalog_item['price'];
				if ($catalog_item['special']==3) $price=round($catalog_item['price']*0.70,-1);
				$items.= $pic.'<td width="120">'.$catalog_item['size'].' <strong>'.$row2['size'].'</strong><br />'.$price.' руб.<br />'.$is.$size_count.'<br />Удалить вещь<input name="del_item[]" type="checkbox" value="'.$row2['item_id'].'" /></td>';		
				$items.='</tr>';							
			}
			else{
				$items.= $row2['item_id'].' '.$row2['size'].'<br/>';
			}			
		}
		
		if ($_GET['id']==$user_id){
		
		print '<td colspan="7" class="selected"><table class="sl"><tr>';
		$st[$row['status']]='checked="checked"';
		print '<td>
		<input name="status" type="radio" value="3" '.$st[3].'/>Новый<br />
		<input name="status" type="radio" value="2" '.$st[2].'/>Написано письмо<br />
		<input name="status" type="radio" value="5" '.$st[5].'/>Требует отправки<br />	
		<input name="status" type="radio" value="7" '.$st[7].'/>Требует доставки<br />		
		<input name="status" type="radio" value="1" '.$st[1].'/>Отправлен<br />
		<input name="status" type="radio" value="0" '.$st[0].'/>Закрыт успешно<br />
		<input name="status" type="radio" value="6" '.$st[6].'/>Ожидает товара<br />		
		<input name="status" type="radio" value="4" '.$st[4].'/>Не выполнен<br />				
		</td>';
		print '<td><table border="0" cellspacing="0" cellpadding="0">'.$items.'</table>';
		print 'Добавить: <em>ID</em><input name="add_item" type="text" size="5" /> <em>Цвет</em><input name="add_item_color" type="text" size="3" /> <em>Размер</em><input name="add_item_size" type="text" size="2" />';
		print'</td>';
		
		print '<td>';
		print '<strong>Отправить письмо:</strong> <br />
&bull; Извините за задержку<input name="mail_sorry" type="checkbox" value="1" /><br />
&bull; Товар есть<input name="mail_item" type="checkbox" value="2" /> / Товара нет<input name="mail_item" type="checkbox" value="1" /> / Товара нет, но будет<input name="mail_item" type="checkbox" value="3" /><br />
&bull; Петербург<input name="mail_city" type="checkbox" value="1" /> / Регионы<input name="mail_city" type="checkbox" value="2" /><br />
&bull; Товар отправлен. Почтовый идентификатор: <input name="mail_send" type="text" value="" size="12" /><br />
<textarea cols="50" rows="8" name="email_add_text"></textarea>
';
		print '</td>';

		print '<td><big>'.$row['date_time'].'</big><br /><textarea cols="45" rows="6" name="comment">'.$row['comment'].'</textarea><br />
		'.$row['name'].'<br><input type="hidden" name="email" value="'.$row['email'].'" />'.$row['email'].'<br>'.$row['phone'].'<br>'.$row['adress'];
		print '</td><td>[ <a href="?param=orders&delid='.$row['ID'].'">Удалить заказ</a> ]</td></tr></table><input type="submit" name="button" id="button" value="Сохранить" />
		<input type="hidden" name="ID" value="'.$row['ID'].'" />
		</td>';
		}
		else{
		print '<td>'.$items.'</td><td>'.$row['comment'].'</td>';
		print '<td>'.$row['date_time'].'</td>';
		print '<td>'.$row['name'].'</td>';
		print '<td>'.$row['email'].'</td>';
		print '<td>'.$row['phone'].'</td>';
		print '<td>'.$row['adress'].'</td>';
		}
		print '</tr>';
		
	}
	print '</table>';
?>

</form>
