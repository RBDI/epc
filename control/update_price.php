<?
	// include_once "../wp-config.php";
	// $db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

	// $db2 = mysql_select_db('u388041_new', $db1);
	// mysql_query('SET NAMES utf8');
?>

<h3>Загрузите Excel-файл с новым прайс листом:</h3>
<form method="POST" ENCTYPE="multipart/form-data" action="">
	
	<input type="file" name="xlsfile" value="">
	<input type="submit" name="button" value="Загрузить »">
</form>

<?

if ($_POST['new_price']){
	$new_price=$_POST['new_price'];
	$i=0;
	foreach ($new_price as $id => $val) {
		$sql="UPDATE `shop_subitem` SET `value1`='$val', `value2`='' WHERE `ID`='$id'";
				
		$result = mysql_query($sql) or die(mysql_error());
		if ($result) $i++;
	}
	print '<h3>'.$i.' позиций обновлено.</h3>';
	include "yml.php";
	print '<h3>YML-файл (Яндекс.Маркет) обновлен.<h3>';
}

if (!$_FILES['xlsfile']) die ();


$tmp_file=$_FILES['xlsfile']['tmp_name'];
$isxls=0;			

if ($tmp_file){
	$isxls=1;
	$result_file=$_FILES['xlsfile']['name'];
	$type_file= $_FILES['xlsfile']['type'];
	$size_file= $_FILES['xlsfile']['size'];
	$error_file = $_FILES['xlsfile']['error'];
	$path='TMP/';
	$full_path=$path.$result_file;
	$ok=move_uploaded_file($tmp_file, $full_path);


	// $full_path='price_list_4.xls';
		
	require_once ('Excel/reader.php'); 
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');
	$data->read($full_path);     

	$k=0;
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
		$l=0;
	  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		$full_xls_item[$k][$l]=$data->sheets[0]['cells'][$i][$j];
		$l++;
	  }
	  $k++;
	}	
	unlink($full_path);
}

// print_r($full_xls_item);
print '<h3>Файл загружен!</h3>';
print 'Всего строк в файле: '.count($full_xls_item);


		

$sql="SELECT ID, value3, value1, value2, value4 FROM shop_subitem";
$result = mysql_query($sql) or die(mysql_error());
$x=0;	
$i=0;
$print='';
while ($row=mysql_fetch_array($result)) {
	foreach ($full_xls_item as $key => $row_table) {
		if ($row['value3']==$row_table[0]&&$row['value3']!='') {			
			$new_price=0;
			if ($row_table[1]!='') {
				$new_price=$row_table[1];
				$id=$row['ID'];
				
				if ($row['value1']!=$new_price){
					$np=round($new_price);
					$nsp=$row_table[2];
					$i++;
					 $difference=$row['value1']/$np;
					 if ($difference!=1){
					 	$nb='';
					 	if ($difference>1.5) $nb=' style="color:#F00;"';
						$print.= '<tr'.$nb.'><td>'.$i.'</td><td>'.$row['value3'].'</td><td>'.$row['value1'].'</td><td><input type="text" name="new_price['.$id.']" value="'.$np.'"></td><td>'.$row['value2'].'</td></tr>';
						// if ($difference<2&&$difference!=1){
							// $sql="UPDATE `shop_subitem` SET `value1`='$np', `value2`='' WHERE `ID`='$id'";					
							// $resultx = mysql_query($sql) or die(mysql_error());
						// }

					}
					// $x++;
				}
			}
		}

	}
}
// print $x;
?>
<h3>Совпадающие артикулы к обновлению:</h3>
<form method="POST" action="">
<table>
	<tr>
		<th>#</th>
		<th>Артикул</th>
		<th>Старая цена</th>
		<th>Новая цена</th>
		<th>Старая цена со скидкой</th>
	</tr>
	<? print $print; ?>
</table>
<h3> <? print $i;  ?> строк к обновлению.</h3>
<input type="submit" value="Обновить цены">
</form>	

