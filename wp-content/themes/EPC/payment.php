<?
get_header();
$order_id=$_GET['ordid'];
$result= mysql_query("SELECT shop_orders.ID, shop_subitem.value1, shop_subitem.value2, shop_users.payment, shop_orders.count, shop_users.delivery_price, shop_subitem.name, shop_catalog.name, shop_users.name, shop_users.email, shop_users.phone, shop_users.adress,shop_orders.discount,shop_orders.price FROM shop_orders, shop_subitem, shop_users, shop_catalog WHERE shop_orders.user_id=$order_id AND shop_subitem.ID=shop_orders.item_id AND shop_users.ID=$order_id AND shop_catalog.ID=shop_subitem.item_id");
while ($row=mysql_fetch_array($result)){
	$user['name']=$row[8];
	$user['email']=$row[9];
	$user['phone']=$row[10];
	$user['adress']=$row[11];
	if ($row['price']>0){
			$items[$row['ID']]['price']=$row['price'];
			$itms[$row['ID']]['price']=$row['price'];
			// print 'x';
	}
	else {
		if ($row['value2']!='') {
			$items[$row['ID']]['price']=$row['value2'];
			$itms[$row['ID']]['price']=$row['value2'];
		}
		else {
			$items[$row['ID']]['price']=$row['value1'];
			$itms[$row['ID']]['price']=$row['value1'];
		}
	}

	if ($row['payment']) $money=$row['payment'];
	if ($row['delivery_price']) $delivery_price=$row['delivery_price'];

	$items[$row['ID']]['count']=$row['count'];
	

	if ($row['discount']) {
		$items[$row['ID']]['price']=$items[$row['ID']]['price']*((100-$row['discount'])/100);
		$itms[$row['ID']]['price']=$itms[$row['ID']]['price']*((100-$row['discount'])/100); 
	}

	$itms[$row['ID']]['count']=$row['count'];
	$itms[$row['ID']]['name']=$row[6].' '.$row[7];
}

foreach ($items as $price) {	
	$sum+=$price['price']*$price['count'];
}
$sum+=$delivery_price;
$success_link='http://europrofcosmetic.ru/payment/?ordid='.$order_id.'&hash='.md5($order_id).'&status=1';
$fail_link='http://europrofcosmetic.ru/payment/?ordid='.$order_id.'&hash='.md5($order_id).'&status=2';

if ($_GET['hash']==md5($order_id)){
?>
<div class="container" style="margin-bottom:100px;">
	<h1>Оплата заказа #<? print $order_id; ?></h1>
	<dl class="dl-horizontal">
<?
if ($user['name']!='') print '<dt>Ваше имя:</dt><dd>'.$user['name'].'</dd>';
if ($user['phone']!='') print '<dt>Телефон:</dt><dd>+7'.$user['phone'].'</dd>';
if ($user['email']!='') print '<dt>Email:</dt><dd>'.$user['email'].'</dd>';
if ($user['adress']!='') print '<dt>Адрес:</dt><dd>'.$user['adress'].'</dd>';
?>
	</dl>
	
	<div class="row">
		<div class="col-md-12">

<?
if ($money==$sum) {
	print '<h2>Заказ полностью оплачен.</h2>';
}
else {
	if ($_GET['status']==2){
		print '<h3>Ошибка оплаты, попробуйте еще раз.</h3>';
	}

?>
<form method="POST" action="https://money.yandex.ru/eshop.xml">
	<input type="hidden" name="scid" value="8644">
	<input type="hidden" name="ShopID" value="17487">
	<input type="hidden" name="shopSuccessURL" value="<? print $success_link; ?>">
	<input type="hidden" name="shopFailURL" value="<? print $fail_link; ?>">

	
	<input type="hidden" name="sum" value="<? print $sum; ?>">
	<input type="hidden" name="customerNumber" value="X<? print $order_id; ?>">
	<input type="hidden" name="orderNumber" value="<? print $order_id; ?>">
	<div class="well">
<table class="table">
	<thead>
<tr>
          <th>#</th>
          <th>Название</th>
          <th>Цена</th>
          <th>Количество</th>
          <th>Стоимость</th>
        </tr>
</thead>        
<?
$i=1;
foreach ($itms as $ID => $item) {
	print '<tr><td>'.$i.'.</td><td>'.$item['name'].'</td><td>'.$item['price'].' руб.</td><td>'.$item['count'].' шт.</td><td>'.$item['price']*$item['count'].' руб.</td></tr>';
	$i++;
}
?>
 
<?
if ($delivery_price) print '<tr><td>'.$i.'.</td><td>Доставка</td><td></td><td></td><td>'.$delivery_price.' руб.</td></tr>';
?>

         
      </tbody>
    </table>
 
		<h2 style="margin:10px 0px 30px;">Сумма заказа: <? print $sum; ?> руб.</h2>
		<div class="row" >
			<div class="col-md-4">
		<div class="radio">
			<label>
				<input name="paymentType" value="AC" type="radio" checked="checked" > С банковской карты				 
			</label>
			<img src="/wp-content/themes/EPC/img/cards.png">
		</div>
			</div>
			<div class="col-md-4">
		<div class="radio">
			<label>
				<input name="paymentType" value="" type="radio"> Со счета в Яндекс.Деньгах
			</label>
			<img src="/wp-content/themes/EPC/img/yad.png">
		</div>
			</div>
			<div class="col-md-4">		
		<div class="radio">
			<label>
				<input name="paymentType" value="GP" type="radio"> По коду через терминал
			</label>
			<img src="/wp-content/themes/EPC/img/cash.png">
		</div>		
			</div>
		</div>		
		<input type="submit" class="btn btn-lg btn-success" value = "Оплатить">
</div>
	
</form>
<? } ?>
</div>
	</div>
</div>
<?
}
else{
	print '<div class="container"><h1>Ошибка!</h1></div>';
}
?>
<?php  get_footer(); ?>