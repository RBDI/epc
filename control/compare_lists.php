<?
include_once "../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
?>

<?
$db2 = mysql_select_db('u388041_new', $db1);
mysql_query('SET NAMES utf8');

$full_path='price_list_compare.xls';
	
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


		
print '<table>';
$sql="SELECT ID, value3, value1, value2, value4 FROM shop_subitem";
$result = mysql_query($sql) or die(mysql_error());
$x=0;	
while ($row=mysql_fetch_array($result)) {
	$items_list[$row['ID']]=$row;
}

foreach ($full_xls_item as $key => $row_table) {
	$ab=0;
	foreach ($items_list as $ID => $item) {
		if ($item['value3']==$row_table[0]&&$row_table[0]!=''){
			$ab=1;
			
		}
	}
	if ($ab!=1){
		print '<tr><td>'.$row_table[0].'</td><td>'.$row_table[1].'</td><td>'.$row_table[2].'</td><td>'.$row_table[3].'</td></tr>';			
		$x++;	
	}

}

print '</table>';
print $x;


?>	