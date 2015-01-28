<? include "config.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Управление каталогом</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<big><a href="/" target="_blank">Сайт</a></big> &nbsp; &nbsp;
<?

if ($_GET['param']=='add'){
	print '<a href="?">&laquo; Каталог</a>';
	include "param_add.php";
}
if ($_GET['param']=='orders'){
	
	include "orders.php";
}
else
{
	print '<a href="?param=add">Добавление / изменение параметров &raquo;</a>';
	include "item_add.php";
}
?>
</body>
</html>
