<?

/** Error reporting */
// error_reporting(E_ALL);

include_once "../../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

$db2 = mysql_select_db('u388041_new', $db1);
mysql_query('SET NAMES utf8');


require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();


$objPHPExcel->getProperties()->setCreator("EuroProfCosmetic.ru")
							 ->setLastModifiedBy("EuroProfCosmetic.ru")
							 ->setTitle("Price list")
							 ->setSubject("Price list")
							 ->setDescription("EuroProfCosmetic.ru price list")
							 ->setKeywords("price")
							 ->setCategory("Price");


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

if ($_POST['param']){
	foreach ($_POST['param'] as $ID => $value) {
		if ($id_sql) $id_sql.=', '.$ID;
		else $id_sql=$ID;
	}
}
else {
	print_r ($_POST);
	print 'Ouch (';
	die();
}

if ($id_sql) $sql='WHERE ID IN('.$id_sql.')';

$sql="SELECT * FROM shop_params $sql ORDER BY ID ASC";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$params[$row['ID']]=$row;
	if ($row['type']==0){
		$cats[$row['ID']]=$row;
	}
	else if ($row['type']==2) {
		$brands[$row['ID']]=$row;
	}		
}

$sql='';
if ($id_sql) $sql="AND shop_catalog.brand IN ($id_sql) AND shop_catalog.type IN ($id_sql)";

$sql="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.desc, shop_catalog.article, shop_catalog.brand, shop_catalog.type  FROM shop_catalog WHERE archive=0 $sql ORDER BY ID ASC";

$result = mysql_query($sql) or die(mysql_error());

while ($row=mysql_fetch_array($result)) {
	// $items[$row['ID']]['name']=trim(htmlspecialchars($row['name']));
	// $items[$row['ID']]['desc']=trim(htmlspecialchars(strip_tags(str_replace('  ',' ',str_replace('&nbsp;','',$row['desc'])))));
	// $items[$row['ID']]['desc']=str_replace('','',$items[$row['ID']]['desc']);
	
	$items[$row['ID']]['name']=$row['name'];
	$items[$row['ID']]['desc']=strip_tags(str_replace('  ',' ',str_replace('&nbsp;','',$row['desc'])));
	$items[$row['ID']]['desc']=str_replace('','',$items[$row['ID']]['desc']);			
	$items[$row['ID']]['article']=$row['article'];
	$items[$row['ID']]['brand']=$row['brand'];
	$items[$row['ID']]['type']=$row['type'];

	if ($sql_itms) $sql_itms.=' OR item_id='.$row['ID'];
	else $sql_itms='item_id='.$row['ID'];

}

if ($sql_itms=='') {
	print 'Нет товаров соответсвующих выбраным категориям.';
	die();
}

$sql="SELECT * FROM shop_img WHERE $sql_itms";

$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['item_id']]['image'][$row['subitem_id']]=$row['filename'];	
}

$sql="SELECT * FROM shop_subitem WHERE $sql_itms";

$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items [$row['item_id']] ['subitem'] [$row['ID']] ['name']=$row['name'];
	$items[$row['item_id']]['subitem'][$row['ID']]['price']=$row['value1'];
	$items[$row['item_id']]['subitem'][$row['ID']]['special_price']=$row['value2'];		
	$items[$row['item_id']]['subitem'][$row['ID']]['article']=$row['value3'];
}

$i=1;

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
	// }

	// foreach ($item['image'] as $subitem_id => $value) {
		// foreach ($value as $filename) {
			// $image=$value;
		// }		
	// }	
	// $item['desc']='Упаковка: '.$size.' '.$item['desc'];
	// $item['desc']=mb_substr($item['desc'], 0, 512);

		if ($item['type']) {
		// $data.='
		// 			<offer id="'.$id.'" type="vendor.model" available="false">
		// 				<url>http://europrofcosmetic.ru/catalog/products/'.$id.'</url>
		// 				<price>'.$price.'</price>
		// 				<currencyId>RUR</currencyId>
		// 				<categoryId>'.$item['type'].'</categoryId>
		// 				<picture>http://europrofcosmetic.ru/products/'.$image.'_medium.jpg</picture>
		// 				<delivery>true</delivery>
		// 				<vendor>'.trim(htmlspecialchars($brand['name'])).'</vendor>
		// 				<vendorCode>'.$article.'</vendorCode>
		//     			<model>'.$item['name'].'</model>
		// 				<description>'.trim(htmlspecialchars($item['desc'])).'</description>
		// 				<country_of_origin>'.$brand['country'].'</country_of_origin>
		// 				<param name="Упаковка">'.$size.'</param>
		// 			</offer>			
		// ';
			$itm_ctg[$item['type']][$subitem_id][0]=$subitem_id;
			$itm_ctg[$item['type']][$subitem_id][1]=$article;
			$itm_ctg[$item['type']][$subitem_id][2]=$brand['ID'];
			$itm_ctg[$item['type']][$subitem_id][3]=$item['name'];
			$itm_ctg[$item['type']][$subitem_id][4]=$item['desc'];
			$itm_ctg[$item['type']][$subitem_id][5]=$brand['country'];
			$itm_ctg[$item['type']][$subitem_id][6]=$size;
			$itm_ctg[$item['type']][$subitem_id][7]=$price;		

			$itm_brand[$brand['ID']][0]=$subitem_id;
			$itm_brand[$brand['ID']][1]=$article;
			$itm_brand[$brand['ID']][2]=$item['type'];
			$itm_brand[$brand['ID']][3]=$item['name'];
			$itm_brand[$brand['ID']][4]=$item['desc'];
			$itm_brand[$brand['ID']][5]=$brand['country'];
			$itm_brand[$brand['ID']][6]=$size;
			$itm_brand[$brand['ID']][7]=$price;		

			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $id);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $article);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, );
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, );
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $item['name']);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $item['desc']);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, $brand['country']);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, $size);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, $price);

			$i++;
		}
	}
}

$list=get_child(0,$cats);

function get_child($parent,$cats) {

	foreach ($cats as $id => $cat) {

		if ($cat['parent']==$parent){
			$list[]=array($cat['name'],$id);
			$child=get_child($id, $cats);
			if ($child) {
				foreach ($child as $val) {
					
					$v[0]=$cat['name'].' > '.$val[0];
					$v[1]=$val[1];
					array_push($list, $v);
				}
				
			}

		}	
	}
	return $list;
}

// print_r($list);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Прайс-лист EuroProfCosmetic.ru');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(25);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', date('d.m.Y'));
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '(499) 322-10-17, (812) 426-14-61, 8 (800) 500-05-79');
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setSize(13);

// $objDrawing = new PHPExcel_Worksheet_Drawing();
// $objDrawing->setName('EuroProfCosmetic.ru');
// $objDrawing->setPath('../../wp-content/themes/EPC/img/logo_europrof.png');
// $objDrawing->setCoordinates('E1');
// $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', 'Сумма');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'Скидка');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', 'Итого');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', '=SUM(I8:I'.$i.')');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '=I2*F3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', '=I2-I3');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'Скидка:');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '0%');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'Организация');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'Телефон');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', 'Комментарий');

$objPHPExcel->getActiveSheet()->getStyle('C2:C4')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			)
		)
);


$objPHPExcel->getActiveSheet()->getStyle('H4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H4')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('I4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I4')->getFont()->setSize(15);

$objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E3')->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F3')->getFont()->setSize(15);


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', 'Артикул');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', 'Бренд');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', 'Название');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', 'Описание');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6', 'Страна');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6', 'Упаковка');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G6', 'Цена');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H6', 'Количество');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I6', 'Итого');
// $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('C6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('E6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('F6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('H6')->getFont()->setBold(true);
// $objPHPExcel->getActiveSheet()->getStyle('I6')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A6:I6')->applyFromArray(array('font' => array('bold' => true),'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'CCCCCC'))));

$i=7	;
foreach ($list as $key => $cat) {
	if ($itm_ctg[$cat[1]]){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $cat[0]);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFont()->setSize(20);
		// $objPHPExcel->getActiveSheet()->getCellDimension('A'.$i)->setHeight(5);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray(array('font' => array('bold' => true,'color' => array('rgb' => 'FFFFFF'),),'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN)),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => '666666'))));
		$i++;

		foreach ($itm_ctg[$cat[1]] as $itm) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $itm[1]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $brands[$itm[2]]['name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $itm[3]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $itm[4]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $itm[5]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $itm[6]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, $itm[7]);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, '0');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i, '=G'.$i.'*H'.$i);
			// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, $itm[0]);
			// $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray(array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			$i++;
		}
	}
	
}



$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);

$objPHPExcel->getActiveSheet()->setTitle('Pricelist');
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->freezePane('A7');

header('Content-Type: application/vnd.ms-excel');
$filename='epc_price_'.time().'.xls';
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
exit;


?>
