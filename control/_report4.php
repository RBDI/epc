<?
$sql="SELECT * FROM shop_orders WHERE ID>2994 AND ID<3735  ";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$stat[$row['item_id']]+=$row['count'];
}
$subitem_ids='';
foreach ($stat as $subitem_id => $count) {
	if ($subitem_ids=='') $subitem_ids=$subitem_id;
	else $subitem_ids.=','.$subitem_id;
}
$sql="SELECT shop_subitem.ID,shop_catalog.ID,shop_catalog.name, shop_subitem.name, shop_subitem.value1, shop_subitem.value2, shop_subitem.value3, shop_subitem.value4 FROM shop_subitem, shop_catalog WHERE shop_subitem.ID IN ($subitem_ids) AND shop_subitem.item_id=shop_catalog.ID ";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row[0]]=$row;
}
?>
<table>
<?
arsort($stat);
foreach ($stat as $subitem_id => $count) {
	$i++;
	print '<tr><td>'.$i.'</td><td>'.$items[$subitem_id][1].'</td><td>'.$items[$subitem_id][2].'</td><td>'.$items[$subitem_id][3].'</td><td>'.$count.'</td></tr>';
}
?>	
</table>