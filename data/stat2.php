<?
session_start();

if ($_POST['login']){
	if ($_POST['login']=='epc'&$_POST['password']=='35398752'){
		$_SESSION['loginXX']=1;
		$maf=1;
	}
}

if ($_SESSION['loginXX']!=1&&$maf!=1) {
?>
<form action="/data/stat2.php" method="post">
	<p>LOGIN <input type="text" name="login" value=""></p>
	<p>PASSWORD <input type="password" name="password" value=""></p>	
	<input type="submit" value="LOG IN">
</form>
<?

	die();
}
?>
<?
// include "../config.php";

include_once "../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
$db2 = mysql_select_db(DB_NAME, $db1);
mysql_query('SET NAMES utf8');

$needles=array(1249, 1496, 1495, 1494, 218, 997, 1234, 1235, 1236, 1238, 1239, 1240, 1471, 1474, 227, 998, 1241, 1242, 1243, 1244, 1245, 1246, 1247, 329, 1399, 1398, 1397, 999, 1252, 1253, 1254, 1400, 1401);

if ($_GET['status']=='paid') $sql='WHERE payment_status>0';
if ($_GET['status']=='paiddate') $sql='WHERE payment_status>0';
if ($_GET['status']=='paidnika') $sql='WHERE payment_status>0 AND manager=0';
if ($_GET['status']=='paidskoroda') $sql='WHERE payment_status>0 AND manager=1';

$sql="SELECT * FROM `shop_users` $sql ORDER BY ID DESC LIMIT 1000";	
$result = mysql_query($sql) or die(mysql_error());
$ids='';
while ($row=mysql_fetch_array($result)) {
	$orders[$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['ID'];
	else $ids=$row['ID'];

	$mytime=strtotime($row['date_time']);
	if ($_GET['status']=='paiddate') {
		if ($row['pay_time']=='0000-00-00 00:00:00') $mytime=strtotime($row['edit_time']);
		else $mytime=strtotime($row['pay_time']);
	}
	$time=strtotime(date("d.m.Y",$mytime));
	

	$day30=strtotime("-90 days");
	if ($time>=$day30) $order_by_date[$time][$row['ID']]=$row;
}
// print_r($order_by_date);

$sql="SELECT * FROM `shop_orders` WHERE user_id IN ($ids)";	
$result = mysql_query($sql) or die(mysql_error());
$ids='';
while ($row=mysql_fetch_array($result)) {
	$order_items[$row['user_id']][$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['item_id'];
	else $ids=$row['item_id'];	
}

$sql="SELECT * FROM `shop_subitem` WHERE ID IN ($ids)";	
$result = mysql_query($sql) or die(mysql_error());

while ($row=mysql_fetch_array($result)) {
	$subitems[$row['ID']]=$row;	
}


// $i=count($base);
$DAY30_TOTAL=0;
$TOTALX=0;
$I=0;
$D=0;
$WEEK_AVAR=0;
$av_w=0;
$xw=0;
for ($k=-90; $k <= 0 ; $k++) { 
	$date=strtotime(date("d.m.Y",strtotime($k.' days')));
	// print $date.' ';
	$orders=$order_by_date[$date];
	// print_r($orders);
	

// }
// foreach ($order_by_date as $date => $orders) {
	$i=0;
	$j=0;
	$tr='';
	$tr1=1;
	$DAY_TOTAL=0;
	if (count($orders)>0){
	foreach ($orders as $order_id => $order) {
		$i++;
		$date_time=date("d.m.Y",strtotime($order['date_time']));
		if ($tr1==1) $tr='<td>'.$i.'</td><td>'.$order_id.'</td><td>'.$date_time.'</td>';
		else $tr.='<tr><td>'.$i.'</td><td>'.$order_id.'</td><td>'.$date_time.'</td>';
		$tr.='<td>';
		$total1=0;
		$total2=0;
		foreach ($order_items[$order_id] as $suborder_id => $item) {
			if (!$_GET['needles']||($_GET['needles']==1&& in_array($item['item_id'], $needles))||($_GET['needles']=='no'&& !in_array($item['item_id'], $needles))){

				$price=$subitems[$item['item_id']]['value1'];
				if ($subitems[$item['item_id']]['value2']!='') $price=$subitems[$item['item_id']]['value2'];
				
				$tr.=$item['item_id'].' - '.$price.' руб. - '.$item['count'].' шт.<br>';
				$total1+=$price*$item['count'];
				$buy_price=$subitems[$item['item_id']]['value4'];
				if ($buy_price==0) {
					$buy_price=$price*0.65;
					$red='style="color:#F00;"';
				}
				$total2+=$buy_price*$item['count'];
			}

			
		}

		$tr.='</td>';

		$tr.='<td>+'.$total1.'</td><td '.$red.'>-'.$total2.'</td>';
		$red='';
		// if ($order['delivery_price']>0) $tr.='<td>-'.$order['delivery_price'].'</td>';
		$outgo='';
		if ($total1==0) $order['outgo']=0;
		if ($order['outgo']>0) $outgo='-'.$order['outgo'];
		$tr.='<td>'.$outgo.'</td>';
		$TOTAL=$total1-$total2-$order['outgo'];
		if ($TOTAL>0) $j++;
		$TOTALX+=$total1;
		$tr.='<td>='.$TOTAL.'</td>';
		
		if ($tr1==1) {
			$tr1=0;
			$tx=$tr;
			$tr='';

		}
		else $tr.='</tr>';
		$DAY_TOTAL+=$TOTAL;
	}
	}
	$DAY30_TOTAL+=$DAY_TOTAL;
	$N=date("N",$date);
	$blue='';
	if ($N>5) $blue='style="color:#CCF;"';
	// $print.= '<tr><td '.$blue.' rowspan="'.$i.'">'.date("d.m.Y",$date).'</td>'.$tx.'<td rowspan="'.$i.'" align="center"><strong>+'.$DAY_TOTAL.' руб.</strong></td>'.$tr;
	$dw=date("N",$date);
	if ($dw!=7) {		
		$av_w+=$DAY_TOTAL;
		$WEEK_AVAR=0;
	}
	else {
		$av_w+=$DAY_TOTAL;
		$WEEK_AVAR=$av_w/5;
		$av_w=0;
		
	}
	$print.= '<tr><td>'.date("d.m.Y",$date).'</td><td><strong>'.$DAY_TOTAL.'</strong></td><td><strong>'.$WEEK_AVAR.'</strong></td></tr>';
	$I+=$j;
	$D++;
	
}
?>	
<html>
<head>
	<title>База</title>
	<link href="../wp-content/themes/EPC/css/bootstrap.css" rel="stylesheet" media="screen">
</head>
<body>
	<div style="margin:20px;">	
		<ul class="nav nav-pills">
			
			<li><a href="?status=paiddate">Оплаченные все (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&needles=1">Оплаченные ИГЛЫ (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&needles=no">Оплаченные без игл (по дате оплаты)</a></li>
			<li><a href="?status=paid">Оплаченные (по дате заказа)</a></li>
			<li><a href="?">Все за 30 дней</a></li>
			<li><a href="?needles=no">Все за 30 дней без игл</a></li>
			<li><a href="?needles=1">Все за 30 дней только иглы</a></li>
		</ul>
		 	
		<h3>За последние 30 дней: +<? print $DAY30_TOTAL; ?> руб. <small><? print $TOTALX; ?> руб.</small></h3>
		<p>В среднем в день: <? print round($I/$D,2); ?> заказов, прибыль: <? print round($DAY30_TOTAL/$D); ?> руб./день. Средний чек: <? print round($TOTALX/$I); ?> руб./заказ, прибыль: <? print round($DAY30_TOTAL/$I); ?> руб./заказ</p>
<table class="table table-bordered">
<!-- 	<thead>
		<tr>
			<th>Дата оплаты</th>
			<th>#</th>
			<th>ID заказа</th>
			<th>Дата заказа</th>
			<th>ID - цена - кол-во</th>
			<th>Продажа</th>
			<th>Закупка</th>
			<th>Доп.расходы</th>
			<th>Итого</th>
			<th>Итого за день</th>
		</tr>
	</thead> -->
<? print $print; ?>
	</table>
</div>
</body>
</html>