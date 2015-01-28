<h2>Продажи по товарам</h2>
<?

$sql="SELECT * FROM shop_orders WHERE ID>2697";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$stat[$row['item_id']]+=$row['count'];
}
$subitem_ids='';
foreach ($stat as $subitem_id => $count) {
	if ($subitem_ids=='') $subitem_ids=$subitem_id;
	else $subitem_ids.=','.$subitem_id;
}
$sql="SELECT shop_subitem.ID, shop_catalog.name, shop_subitem.value1,shop_subitem.name,shop_subitem.item_id  FROM shop_catalog, shop_subitem WHERE shop_subitem.ID IN ($subitem_ids) AND shop_catalog.ID=shop_subitem.item_id  ";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row[0]]=$row;
}

?>
<table>
<?

arsort($stat);
$i=0;
foreach ($stat as $subitem_id => $count) {
	$i++;
	print '<tr><td>'.$i.'</td><td>'.$subitem_id.'</td><td>'.$items[$subitem_id][4].'</td><td>'.$items[$subitem_id][1].' '.$items[$subitem_id][3].'</td><td>'.$items[$subitem_id][2].'</td><td>'.$count.'</td></tr>';
}


?>	
</table>
<hr>


<?
$sql="SELECT ID,phone, epc_term FROM shop_users WHERE epc_source='yandex' ";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$orders[$row['ID']]=$row;
	$frz[$row['epc_term']]++;
}

foreach ($frz as $word => $count) {
	print $word.'('.$count.');<br> ';
}
?>
<table>
<?
/*
arsort($stat);
foreach ($stat as $subitem_id => $count) {
	$i++;
	print '<tr><td>'.$i.'</td><td>'.$items[$subitem_id][1].'</td><td>'.$items[$subitem_id][2].'</td><td>'.$items[$subitem_id][3].'</td><td>'.$count.'</td></tr>';
}
*/
$i=0;
foreach ($orders as $ID => $val) {
	$i++;
	print '<tr><td>'.$i.'</td><td>'.$val['ID'].'</td><td>'.$val['phone'].'</td><td>'.$val['epc_term'].'</td></tr>';
}
?>	
</table>