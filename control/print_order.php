<html>
<head>
	<title></title>
	<style type="text/css">
		body{
			font-family: Arial;
			font-size: 13px;
		}		
		table,th,td
		{
			border:1px solid black;
			border-collapse:collapse;
			font-size: 13px;
		}
		td,th{
			padding: 5px;
		}
		.noborder {
			border: none;
		}
	</style>
</head>
<body>
	
<?
include "config.php";

$user_id=$_GET['id'];
$order_id=$user_id;



$sql="SELECT * FROM shop_users WHERE ID=$user_id";
//print $sql;	

$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$order['name']=$row[1];
	$order['email']=$row['email'];
	$order['phone']=$row['phone'];
	$order['adress']=$row['adress'];


	$order['delivery_company']=$row['delivery_company'];
	$order['delivery_num']=$row['delivery_num'];
	$order['delivery_time']=$row['delivery_time'];
	$order['delivery_date']=$row['delivery_date'];
	$order['delivery_price']=$row['delivery_price'];
	$order['outgo']=$row['outgo'];
	$order['payment_status']=$row['payment_status'];

}

if ($user_id) {

	$xql="SELECT shop_orders.item_id, shop_subitem.item_id, shop_subitem.name, shop_subitem.value1, shop_subitem.value2, shop_subitem.value3, shop_subitem.value4, shop_orders.count, shop_catalog.name,shop_subitem.ID,shop_orders.ID,shop_orders.discount,shop_orders.price FROM shop_orders, shop_subitem, shop_catalog WHERE shop_orders.item_id=shop_subitem.ID AND shop_orders.user_id=$user_id AND shop_catalog.ID=shop_subitem.item_id";
	$xresult = mysql_query($xql) or die(mysql_error());
	while ($xrow=mysql_fetch_array($xresult)) {
		$item[$xrow[10]]['name']=$xrow[8];
		if ($xrow[2]) $item[$xrow[10]]['name'].= ' ('.$xrow[2].')';
		$item[$xrow[10]]['article']=$xrow[5];
		$item[$xrow[10]]['count']=$xrow[7];
		$item[$xrow[10]]['discount']=$xrow[11];
		$item[$xrow[10]]['subitem_id']=$xrow[9];
		$item[$xrow[10]]['item_id']=$xrow[10];
		if ($xrow['price']!=''&&$xrow['price']!=0) {
			$item[$xrow[10]]['price']=$xrow['price'];
		}
		else {
			if ($xrow['value2']) $item[$xrow[10]]['price']=$xrow['value2'];
			else $item[$xrow[10]]['price']=$xrow['value1'];
		}
		if ($xrow['price2']!='') $item[$xrow[10]]['shop_price']=$xrow['price2'];
		else $item[$xrow[10]]['shop_price']=$xrow['value4'];
	}

	$itm='<table width="100%" >';
	$itm.='<tr><th>№</th><th>Артикул</th><th>Наименование товара</th><th>Кол-во</th><th>Ед.</th><th>Цена</th><th>Сумма</th></tr>';
	$i=0;
	$TTL=0;
	foreach ($item as $id => $v) {
		$i++;
		if ($v['discount']) $v['price']=$v['price']*((100-$v['discount'])/100);
		$ttl=$v['count']*$v['price'];
		$itm.='<tr><td align="center">'.$i.'</td><td align="center">'.$v['article'].'</td><td>'.$v['name'].'</td><td align="center">'.$v['count'].'</td><td align="center">шт.</td><td align="center">'.$v['price'].' руб.</td><td align="center">'.$ttl.' руб.</td></tr>';		
		$TTL+=$ttl;
		
	}
	if ($order['delivery_price']>0) {
		$i++;
		$itm.='<tr><td align="center">'.$i.'</td><td></td><td>Доставка</td><td align="center">1</td><td align="center">шт.</td><td align="center">'.$order['delivery_price'].' руб.</td><td align="center">'.$order['delivery_price'].' руб.</td></tr>';
	}
	$TOTAL=$TTL+$order['delivery_price'];
	$itm.='<tr><td colspan="7" align="right">Итого: <strong>'.$TOTAL.' руб.</strong><br>	
	</td></tr></table>';
 

	
 
	print '<table width="100%" border="0" class="noborder"><tr><td class="noborder">
	<img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/logo_europrof.png" width="200">
	<h2>Товарный чек № '.$order_id.' от '.date("d.m.Y").'<h2>';
	
	print  '<h3> Покупатель: '.$order['name'].', +7'.$order['phone'].'<br>
	Продавец: ИП Скорода С.В.
	</h3></td>';

	print '<td align="right" class="noborder" width="30%">
		<table width="100%">
			<tr><td align="center">Заявка №'.$order['delivery_num'].'</td><td rowspan="2" align="center">'.$order['delivery_date'].'<br>'.$order['delivery_time'].'</td></tr>
			<tr><td align="center">ЕвроПрофКосметик<br>+7 (499) 322-10-17</td></tr>
			<tr><td align="center" colspan="2">'.$order['adress'].'</td></tr>
			<tr><td></td><td align="center">1/1</td></tr>
		</table>
	</td>';
	print'</tr></table>';
	// .'<br/>
	// '.$order['email'].'<br/>
	// '.$order['phone'].'<br/>		
	// '.$order['adress'].'<br />
	// '.$order['comment'].'<br />';

	print $itm;
	
	print '<p>Всего наименований: '.$i.', на сумму '.$TOTAL.' руб.</p>';
	print '<p><strong>'.my_mb_ucfirst(num2str($TOTAL)).'</strong></p>';
	
}
?>
<p><strong>Подпись покупателя: ________________________</strong></p>
<p>Товар получен, претензий по ассортименту и качеству не имею.</p>

</body>
</html>
<?
/**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */

function my_mb_ucfirst($str) {
    $fc = mb_strtoupper(mb_substr($str, 0, 1));
    return $fc.mb_substr($str, 1);
}

function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}
?>