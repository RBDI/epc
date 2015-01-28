<? session_start ();?>
<? include "config.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Управление каталогом</title>
<!-- <link rel="icon" type="image/png" href="favicon.png" />  -->
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />    

<link href="http://europrofcosmetic.ru/wp-content/themes/EPC/css/bootstrap.css" rel="stylesheet" media="screen">

<link href="style2.css" rel="stylesheet" type="text/css" />

<script src="//code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<!-- <script type="text/javascript" src="/wp-includes/js/jquery/jquery.js?ver=1.7.2"></script> -->
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body>

<?


if ($_POST['exit']=='yes') {
	$_SESSION['USER_AUTH']=0;
	$_SESSION['USER_ID']=0;
	
	unset($_SESSION['USER_AUTH']);// $_SESSION["USER"]='no';
	unset($_SESSION['USER_ID']);
	
	//$auth->show_failed_login();
	//exit;
}

	include "login.php";


?>
<?
$TOTAL=0;
$mngx=$_SESSION['USER_ID'];
$sql="SELECT * FROM `shop_users` WHERE payment_status>0 AND manager=$mngx ORDER BY ID DESC";	

$result = mysql_query($sql) or die(mysql_error());
$ids='';
$start_time=strtotime('01.'.date("m.Y"));

while ($row=mysql_fetch_array($result)) {	 
	if ($row['pay_time']=='0000-00-00 00:00:00') $mytime=strtotime($row['edit_time']);
	else $mytime=strtotime($row['pay_time']);
	if ($mytime>=$start_time) {
		if ($ids!='') $ids.=', '.$row['ID'];
		else $ids=$row['ID'];
		// $TOTAL+=$row['outgo'];
	}
}
 
if ($ids) {
	$sql="SELECT * FROM `shop_orders` WHERE user_id IN ($ids)";
	$result = mysql_query($sql) or die(mysql_error());
}
$ids='';
while ($row=mysql_fetch_array($result)) {	
	$order_items[$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['item_id'];
	else $ids=$row['item_id'];	
}
if ($ids) {
	$sql="SELECT * FROM `shop_subitem` WHERE ID IN ($ids)";	
	$result = mysql_query($sql) or die(mysql_error());
}

while ($row=mysql_fetch_array($result)) {
	if ($row['value2']>0) $sub[$row['ID']]+=$row['value2'];
	else $sub[$row['ID']]+=$row['value1'];
}

foreach ($order_items as $ID => $val) {
	 
	$discount=1;
	if ($val['discount']) {
		$discount=(100-$val['discount'])/100;		 
	}
	if ($val['price']>0) $TOTAL+=$val['price']*$val['count']*$discount;
	else $TOTAL+=$sub[$val['item_id']]*$val['count']*$discount;
}
?>
<nav class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container-fluid">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="?param=orders">Заказы</a></li>
		<li><a href="?products=1">Товары</a></li>
		<li><a href="?param=update_price">Обновить цены</a></li>
		<li><a href="?param=getpricelist">Прайс-листы</a></li>
		<li><a href="?param=add">Структура каталога</a></li>
		<li><? if ($mngx==1) print '<a target="_blank" href="../data/stat.php">Статистика</a>' ?></li>
		<li><a href="?param=settings">Настройки магазина</a></li>
		<li><a href="/" target="_blank">Посмотреть сайт &raquo;</a></li>
      </ul>
      <form action="" method="post" class="navbar-form navbar-right">
      	<? // print_r($_SESSION); ?>

      	<? print '<strong>'.$_SESSION['USER_AUTH'].'</strong>, с начала месяца: <strong>'.$TOTAL*0.02.' руб.</strong>'; ?>
      	 
      	
      	<input name="exit" type="hidden" value="yes" />
      	<input name="" class="btn btn-default" value="Exit" type="submit" />
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



 
<?




//if ($_SESSION["USER"]=='ctrl'){
//	print '<div class="menu"><big><a href="?products=0">Клумбы</a> &nbsp; &nbsp; ';
	// print '<div class="menu"><big><a href="?param=orders">Заказы</a> &nbsp; &nbsp; <a href="?products=1">Товары</a> &nbsp; &nbsp;<a href="?param=add">Структура каталога</a> &nbsp; &nbsp;<a href="?param=settings">Настройки магазина</a>  &nbsp; &nbsp;  &nbsp; &nbsp;</big>';	
	// print ' <a href="/" target="_blank">Посмотреть сайт &raquo;</a>  &nbsp; &nbsp; <form action="" method="post" style="float:right;"><input name="exit" type="hidden" value="yes" /><input name="" value="Exit" type="submit" /></form></div>';
?>
<div class="containter">
<?
	$sql="select * from `shop_params` ORDER BY ID DESC";	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$param[$row['ID']]=$row['name'];		
	}
?>

<?
if (isset($_GET['products'])) {
	$_SESSION['products']=$_GET['products'];
	$_GET['showadd']=0;
}
if (isset($_SESSION['products'])) $products=$_SESSION['products'];
else $products=1;

?>

<?
function slug($text) {
	$text=mb_strtolower($text);
 	$text = mb_ereg_replace ("[^a-zабвгдеёжзийклмнопрстуфхцчшщьыъэюя«»0-9\-\s]","",$text);
	//$text= preg_replace('![^\w\d\s]*!','',$text);
	
	//print $text;
	while ($text) {
		$single =  mb_substr($text, 0, 1);
		//print $single.'-';
		$single_new='';
		switch ($single) {
			case "а": $single_new ="a"; break;	
			case "б": $single_new ="b"; 	break;
			case "в": $single_new ="v"; break;	
			case "г": $single_new ="g"; 	break;
			case "д": $single_new ="d"; 	break;	
			case "е": $single_new ="e"; 	break;
			case "ё": $single_new ="yo"; break;	
			case "ж": $single_new ="j"; 	break;
			case "з": $single_new ="z"; break;
			case "й": $single_new ="i"; 	break;
			case "и": $single_new ="i"; 	break;
			case "к": $single_new ="k"; 	break;	
			case "л": $single_new ="l"; 	break;
			case "м": $single_new ="m"; 	break;	
			case "н": $single_new ="n"; 	break;
			case "о": $single_new ="o"; 	break;	
			case "п": $single_new ="p"; 	break;
			case "р": $single_new ="r"; break;	
			case "с": $single_new ="s"; 	break;
			case "т": $single_new ="t"; break;	
			case "у": $single_new ="u"; 	break;
			case "ф": $single_new ="f"; break;	
			case "х": $single_new ="h"; 	break;
			case "ц": $single_new ="c"; break;	
			case "ч": $single_new ="ch";	break;
			case "ш": $single_new ="sh";	break;	
			case "щ": $single_new ="sch";	break;
			case "ь": $single_new ="?";	break;	
			case "ы": $single_new ="i";	break;
			case "ъ": $single_new ="?";	break;	
			case "э": $single_new ="e";	break;
			case "ю": $single_new ="u";	break;	
			case "я": $single_new ="ya"; break;	
			case "«": $single_new ="?"; break;	
			case "»": $single_new ="?"; break;			
		}
		if ($single_new) {
			if ($single_new=='?') $single_new='';
			$word=$word.$single_new;
			$text = substr_replace ($text, "", 0, 2);			
		}
		else {
			$word=$word.$single;
			$text = substr_replace ($text, "", 0, 1);			
		}


 	}
	
	//$vowels = array('"', "'", "%", "$", "#", "<", ">", "|", "*", "&", "^", "(", ")", ",", ".", "+", "+", "=", "?", ";", ":", "~", "[", "]","«","»");
	//$slug=str_replace($vowels, "", $word);
	
	
	
	$vowels2 = array(" ", "/", "_");				
	$slug=str_replace($vowels2, "-", $word);
	
	return $slug;
}
?>

<?
	if ($_GET['param']=='add'){
		//print '<a href="?">&laquo; Товары</a>';
		?> <h2>Управление структурой каталога</h2> <?
		include "param_add.php";

	}
	else if ($_GET['param']=='YML'){
		//print '<big><!--<a href="?param=sofa">Софа</a></big>--> &nbsp; &nbsp;<a href="?">&laquo; Каталог</a>';
		include "yml.php";
	}
	else if ($_GET['param']=='update_price'){
		
		include "update_price.php";
	}	
	else if ($_GET['param']=='orders'){
		//print '<big><!--<a href="?param=sofa">Софа</a></big>--> &nbsp; &nbsp;<a href="?">&laquo; Каталог</a>';
		include "orders.php";
	}
	else if ($_GET['param']=='getpricelist'){	
		include "getprice.php";
	}	
	else if ($_GET['param']=='report'){	
		include "report.php";
	}	
	else if ($_GET['param']=='report2'){ include "report2.php"; }
	else if ($_GET['param']=='report3'){ include "report3.php";	}
	else if ($_GET['param']=='report4'){ include "report4.php"; }
	else if ($_GET['param']=='report5'){ include "report5.php"; }
	else if ($_GET['param']=='settings'){
		
		?> <h2>Настройки магазина</h2> <?
		include "edit_settings.php";
	}
	else
	{
		//print '<big><a href="?param=orders">Заказы</a></big> <!--&nbsp; &nbsp;<big><a href="?param=sofa">Софа</a></big>--> &nbsp; &nbsp;<a href="?param=add">Управление структурой &raquo;</a>';
		//if ($products==1) 
		print '<h2>Товары</h2>';
		//else print '<h2>Клумбы</h2>';
		include "item_add.php";
	}
	
	
	
//}
/*
if ($_SESSION["USER"]!='sofa'&&$_SESSION["USER"]!='ctrl'){
print'
<form id="form1" name="form1" method="post" action="">
  <p>
<input type="text" name="namez" id="name" /> <br />
<input type="password" name="passz" id="slug" />    <br />
<input value="Babam!" name="" type="submit" />
    <br />
    
  </p>
  </form>
';
*/
//}
?>
</div>

</body>
</html>
