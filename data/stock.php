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
<form action="/data/stat.php" method="post">
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

// $needles=array(1249, 1496, 1495, 1494, 218, 997, 1234, 1235, 1236, 1238, 1239, 1240, 1471, 1474, 227, 998, 1241, 1242, 1243, 1244, 1245, 1246, 1247, 329, 1399, 1398, 1397, 999, 1252, 1253, 1254, 1400, 1401);

 

$sql="SELECT * FROM `shop_subitem` WHERE in_stock!=0 ORDER BY ID DESC";
$result = mysql_query($sql) or die(mysql_error());
$ids='';
$i=0;
$total=0;
while ($row=mysql_fetch_array($result)) {
	$stock[$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['item_id'];
	else $ids=$row['item_id'];
	$i++;
	$total+=$row['value4']*$row['in_stock'];
}

$sql="SELECT * FROM `shop_catalog` WHERE ID IN ($ids) ORDER BY ID DESC";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['ID']]=$row;
}


?>	
<html>
<head>
	<title>Склад</title>
	<link href="../wp-content/themes/EPC/css/bootstrap.css" rel="stylesheet" media="screen">
	<style type="text/css"> </style>	
	<script src="//code.jquery.com/jquery.js"></script>    
    <script src="http://europrofcosmetic.ru/wp-content/themes/EPC/js/bootstrap.min.js"></script>
</head>
<body>
	<div style="margin:20px;">		
		<h2>Склад</h2>
		<h3>Итого позиций: <? print $i; ?> На сумму: <? print $total; ?> руб.</h3>
		<table class="table table-bordered">
			<thead>
				<tr><th>#</th><th>ID позиции</th><th>ID товара</th><th>Название</th><th>Упаковка</th><th>Цена</th><th>Кол-во</th><th>Стоимость</th></tr>
			</thead>
<?
$j=count($stock);
foreach ($stock as $subitem_id => $val) {
	print '<tr><td>'.$j.'</td><td>'.$subitem_id.'</td><td>'.$val['item_id'].'</td><td width="40%">'.$items[$val['item_id']]['name'].'</td><td>'.$val['name'].'</td><td>'.$val['value4'].' руб.</td><td>'.$val['in_stock'].'</td><td>'.$val['value4']*$val['in_stock'].' руб.</td></tr>';
	$j--;
}
?>
		</table>
	</div>
</body>
</html>