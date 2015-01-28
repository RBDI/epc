<? 
// include "config.php";


$data='<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date("Y-m-d").' '.date("H:i").'">
	<shop>
   		<name>EuroProfCosmetic</name>
		<company>EuroProfCosmetic</company>
		<url>http://europrofcosmetic.ru/</url>
        
		<currencies>
			<currency id="RUR" rate="1" plus="0"/>
		</currencies>
        
		<categories>';

$sql="SELECT * FROM shop_params ORDER BY ID ASC";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$params[$row['ID']]=$row;
	if ($row['type']==0) {
$data.='			<category id="'.$row['ID'].'">'.$row['name'].'</category>
';
	}
}
$data.='
		</categories>
		<local_delivery_cost>350</local_delivery_cost>
		<offers>';

$sql="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.desc, shop_catalog.article, shop_catalog.brand, shop_catalog.type  FROM shop_catalog WHERE archive=0 ORDER BY ID ASC";
$result = mysql_query($sql) or die(mysql_error());

while ($row=mysql_fetch_array($result)) {
	$items[$row['ID']]['name']=trim(htmlspecialchars($row['name']));
	$items[$row['ID']]['desc']=trim(htmlspecialchars(strip_tags(str_replace('  ',' ',str_replace('&nbsp;','',$row['desc'])))));
	$items[$row['ID']]['desc']=str_replace('','',$items[$row['ID']]['desc']);		
	$items[$row['ID']]['article']=$row['article'];
	$items[$row['ID']]['brand']=$row['brand'];
	$items[$row['ID']]['type']=$row['type'];

	if ($sql_itms) $sql_itms.=' OR item_id='.$row['ID'];
	else $sql_itms='item_id='.$row['ID'];

}

$sql="SELECT * FROM shop_img WHERE $sql_itms";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['item_id']]['image'][$row['subitem_id']]=$row['filename'];	
	// print_r($items[$row['item_id']]['image']);		
}

$sql="SELECT * FROM shop_subitem WHERE $sql_itms";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['item_id']]['subitem'][$row['ID']]['name']=$row['name'];
	$items[$row['item_id']]['subitem'][$row['ID']]['price']=$row['value1'];
	$items[$row['item_id']]['subitem'][$row['ID']]['special_price']=$row['value2'];		
	$items[$row['item_id']]['subitem'][$row['ID']]['article']=$row['value3'];
}

foreach ($items as $id => $item){

	if ( $params[ $item['brand'] ] ['parent']!=0) {
		$brand=get_parentest_($params[$item['brand']]['parent'],$params);		
	}
	else $brand=$params[$item['brand']];

	$price='';
	foreach ($item['subitem'] as $subitem_id => $value) {
		$price=$value['price'];
		if ($value['special_price']) $price=$value['special_price'];
		$size=$value['name'];		 
		$article=$value['article'];
	}

	foreach ($item['image'] as $subitem_id => $value) {
		// foreach ($value as $filename) {
			$image=$value;
		// }		
	}	
	$item['desc']='Упаковка: '.$size.' '.$item['desc'];
	$item['desc']=mb_substr($item['desc'], 0, 512);

if ($item['type']) {
$data.='
			<offer id="'.$id.'" type="vendor.model" available="false">
				<url>http://europrofcosmetic.ru/catalog/products/'.$id.'</url>
				<price>'.$price.'</price>
				<currencyId>RUR</currencyId>
				<categoryId>'.$item['type'].'</categoryId>
				<picture>http://europrofcosmetic.ru/products/'.$image.'_medium.jpg</picture>
				<delivery>true</delivery>
				<vendor>'.trim(htmlspecialchars($brand['name'])).'</vendor>
				<vendorCode>'.$article.'</vendorCode>
    			<model>'.$item['name'].'</model>
				<description>'.trim(htmlspecialchars($item['desc'])).'</description>
				<country_of_origin>'.$brand['country'].'</country_of_origin>
				<param name="Упаковка">'.$size.'</param>
			</offer>			
';
}
}

$data.='		</offers>
	</shop>
</yml_catalog>	
	';
    
	$filename = "epc.xml";
	$fh = fopen($filename, "w");
	$is=fwrite($fh, $data);
	
	fclose($fh); 
?>


<?
function get_parentest_($parent, $array){	
	foreach ($array as $id => $param) {		
		if ($id==$parent) {
			if ($param['parent']==0) {

				return $param;
			}
			else return get_parentest_ ($param['parent'],$array);
		}
	}	
}
?>