<?
include "wp-config.php";

if ($_POST['promocode']=='EPC20'||$_POST['promocode']=='ЕРС20'||$_POST['promocode']=='epc20'||$_POST['promocode']=='ерс20') {
	$_SESSION['promocode']=$_POST['promocode'];

	$promobrand = array(269,1118,277,285,1159,1156,260);

	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db(DB_NAME, $db1);
	mysql_query('SET NAMES utf8');

	$sql="SELECT * FROM shop_params WHERE type=2";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}
	$ids='';
	foreach ($_SESSION['ORDER'] as $key => $val) {
		if ($ids=='') $ids=$val[0];
		else $ids.=','.$val[0];
	}

	$sql="SELECT shop_catalog.ID, shop_catalog.brand FROM shop_catalog WHERE shop_catalog.ID IN ($ids)";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row=mysql_fetch_array($result)) {
		
		if (in_array(getparent($row['brand'], $params),$promobrand))
			$promo[]=$row['ID'];
	}


	$str='';
	foreach ($_SESSION['ORDER'] as $key => $val) {
		if (in_array($val[0], $promo)) {
			if ($str=='') $str='['.$key.',0.8]';
			else $str.=',['.$key.',0.8]';
			$_SESSION['ORDER'][$key][3]=0.8;
		}
		else {			
			if ($str=='') $str='['.$key.',1]';
			else $str.=',['.$key.',1]';
			$_SESSION['ORDER'][$key][3]=1;
		}
	}
$pc=1;
	
}
else {
	$str='';
	$_SESSION['promocode']=$_POST['promocode'];
	foreach ($_SESSION['ORDER'] as $key => $val) {
		if ($_SESSION['ORDER'][$key][3]){
			$_SESSION['ORDER'][$key][3]=1;
			unset($_SESSION['ORDER'][$key][3]);
		}
		if ($str=='') $str='['.$key.',1]';
		else $str.=',['.$key.',1]';
		
	}
	$pc=0;
}

print '{"a":['.$str.'],"b":'.$pc.'}';

function getparent($id, $params)
{
	if ($params[$id]['parent']!=0){
		return getparent($params[$id]['parent'],$params);
	}
	else {
		return $id;
	}
}

?>