<?
include_once "../wp-config.php";
$I = simplexml_load_file('import.xml');
$K = simplexml_load_file('offers.xml');

foreach ($K->ПакетПредложений->Предложения->Предложение as $key => $val) {
	foreach ($val->Цены->Цена as $key => $val2) {
		$offers[$val->Ид.'']['prices'][$val2->ИдТипаЦены.'']=$val2->ЦенаЗаЕдиницу.'';
	}	
	$offers[$val->Ид.'']['instock']=$val->Количество.'';
}
$i=0;
foreach ($I->Каталог->Товары->Товар as $key => $val) {
	$art=$val->Артикул;

	$art=str_replace('"', '', $art);
	$_ids=explode("-",$art);
	$c1=$val->Ид;
	$c1=$c1.'';
	$ids[$i]['id']=$_ids[1];
	$ids[$i]['1c']=$c1;
	$ids[$i]['article']=$_ids[0];
	$ids[$i]['instock']=$offers[$c1]['instock'];
	$ids[$i]['prices']=$offers[$c1]['prices'];
	$i++;
}

// print_r($ids);

	
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db(DB_NAME, $db1);
	mysql_query('SET NAMES utf8');

$sql="SELECT ID, value1, value2, value4, value3 in_stock, 1C_ID FROM shop_subitem";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$base[$row['ID']]=$row;
}

foreach ($ids as $val1) {
	foreach ($base as $ID => $val2) {
		if (($val1['id']!=''&&$val1['id']==$val2['ID'])||($val1['1c']==$val2['1C_ID'])){
			if ($val1['1c']!=$val2['1C_ID']||$val1['instock']!=$val2['in_stock']||$val1['prices']['356fdde0-7ef2-11e4-a522-0050569c5934']!=$val2['value1']||$val1['prices']['4ca0ed03-7fb7-11e4-a522-0050569c5934']!=$val2['value4']){
				$C1_ID=$val1['1c'];
				$in_stock=$val1['instock'];
				$value1=$val1['prices']['356fdde0-7ef2-11e4-a522-0050569c5934'];
				$value4=$val1['prices']['4ca0ed03-7fb7-11e4-a522-0050569c5934'];
				// $sql="UPDATE shop_subitem SET 1C_ID='$C1_ID', in_stock='$in_stock', value1='$value1', value4='$value4' WHERE ID=$ID";
				$sql="UPDATE shop_subitem SET 1C_ID='$C1_ID' WHERE ID=$ID";
				// $result = mysql_query($sql) or die(mysql_error());
				
			}
		}
	}
}

?>