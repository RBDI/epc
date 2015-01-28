<?



require_once ('Excel/reader.php'); 
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8');
$data->read('phones.xls');     

$k=0;
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	$l=0;
  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
	$full_xls_item[$k]=$data->sheets[0]['cells'][$i][$j];
	$l++;
  }
  $k++;
}	


?>
<table>
<?
foreach ($full_xls_item as $i => $value) {
	$phns=fphone($value);
	
	$sql="SELECT * FROM shop_users WHERE phone LIKE '$phns' ";
	$result = mysql_query($sql) or die(mysql_error());
	$x=0;
	$xx='';
	while ($row=mysql_fetch_array($result)) {
		$xx= $row['name'].' ';
		$x++;

	}
	if ($x==0) print '<tr style="background:#CCC;">';
	else print '<tr>';
	print '<td>'.$phns.'</td><td>';
	print $xx;
	print '</td><td>'.$x.'</td></tr>';
	
	
}
?>
</table>
