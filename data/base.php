<?
session_start();

if ($_POST['login']){
	if ($_POST['login']=='epc'&$_POST['password']=='35398752'){
		$_SESSION['loginXX']=1;
		$maf=1;
	}
}

if ($_SESSION['loginXX']!=1&&$maf!=1) {
?>
<form action="/data/base.php" method="post">
	<p>LOGIN <input type="text" name="login" value=""></p>
	<p>PASSWORD <input type="password" name="password" value=""></p>	
	<input type="submit" value="LOG IN">
</form>
<?

	die();
}
?>
<?
// include "../config.php";

include_once "../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
$db2 = mysql_select_db(DB_NAME, $db1);
mysql_query('SET NAMES utf8');

$sql="SELECT ID,phone, name, email FROM `shop_users` ORDER BY ID DESC";	
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	if ($row['phone']!=''){
		$basex[$row['phone']][]=$row;
		$base[$row['phone']]['ID'].=$row['ID'].'; ';
		$base[$row['phone']]['name'].=$row['name'].'; ';
		$base[$row['phone']]['email'].=$row['email'].'; ';
	}
}

?>
<html>
<head>
	<title>База</title>
	<link href="../wp-content/themes/EPC/css/bootstrap.css" rel="stylesheet" media="screen">
</head>
<body>
	<div class="container">		
<table class="table table-striped">
	<thead>
        <tr>
	        <th>#</th>
	        <th>Телефон</th>	        
	        <th>Имя</th>	        
	        <th>Email</th>
	        <th>ID</th>
        </tr>
      </thead>
<?
$i=count($base);
foreach ($base as $phone => $user) {
	print '<tr>';
	print '<td>'.$i.'</td><td>7'.$phone.'</td><td>'.$user['name'].'</td><td>'.$user['email'].'</td><td>'.$user['ID'].'</td>';
	
	print '</tr>';
	$i--;
}
?>	
	</table>
</div>
</body>
</html>