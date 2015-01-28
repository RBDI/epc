<?

$sql="SELECT shop_subitem.ID, shop_catalog.name, shop_subitem.value1, shop_subitem.name, shop_subitem.item_id, shop_subitem.value4  FROM shop_catalog, shop_subitem WHERE (shop_subitem.value1-shop_subitem.value4)>1000 AND shop_catalog.ID=shop_subitem.item_id";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row[0]]=$row;
}

?>
<table>
<?


$i=0;
foreach ($items as $subitem_id => $item) {
	$i++;
	print '<tr><td>'.$i.'</td><td>'.$subitem_id.'</td><td>'.$item[4].'</td><td>'.$item[1].' '.$item[3].'</td><td>'.$item[2].'</td><td>'.$item[5].'</td></tr>';
}


?>	
</table>