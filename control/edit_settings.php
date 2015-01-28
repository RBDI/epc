<?
if ($_POST['upload_price']==1){
		
	$tmp_file=$_FILES['price']['tmp_name'];
	$result_file=$_FILES['price']['name'];
	$type_file= $_FILES['price']['type'];
	$size_file= $_FILES['price']['size'];
	$error_file = $_FILES['price']['error'];
	
	$full_path='../download/'.$result_file;
	print $full_path;
	$upload=move_uploaded_file($tmp_file, $full_path);
	
	if ($upload){	
		$sql="SELECT setting FROM `shop_settings` WHERE `setting`='pricelist' LIMIT 1";
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_array($result);
		if (is_file('../download/'.$row['value'])) $del=unlink ('../download/'.$row['value']);
	}
	$sql="UPDATE `shop_settings` SET `value`='$result_file' WHERE `setting`='pricelist'";
	$result = mysql_query($sql) or die(mysql_error());
	print ' <em>Сохранено!</em>';
}

if ($_POST['edit_settings']==1){
	$title_value=$_POST['title_value'];
	$site_phone=$_POST['site_phone'];
	$site_email=$_POST['site_email'];
	$site_icq=$_POST['site_icq'];
	$site_skype=$_POST['site_skype'];
	
	$sql="UPDATE `shop_settings` SET `value`='$title_value' WHERE `setting`='index_prod_title'";
	$result = mysql_query($sql) or die(mysql_error());
	
	$sql="UPDATE `shop_settings` SET `value`='$site_phone' WHERE `setting`='site_phone'";
	$result = mysql_query($sql) or die(mysql_error());
	
	$sql="UPDATE `shop_settings` SET `value`='$site_email' WHERE `setting`='site_email'";
	$result = mysql_query($sql) or die(mysql_error());
	
	$sql="UPDATE `shop_settings` SET `value`='$site_icq' WHERE `setting`='site_icq'";
	$result = mysql_query($sql) or die(mysql_error());
	
	$sql="UPDATE `shop_settings` SET `value`='$site_skype' WHERE `setting`='site_skype'";
	$result = mysql_query($sql) or die(mysql_error());
	
	print ' <em>Сохранено!</em>';
}
?>

<?
$sql="SELECT * FROM `shop_settings`";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$setting[$row['setting']]=$row['value'];
}
?>

<form class="add_form" id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
<h3>Прайс-лист</h3>
<p>Текущий: <? print $setting['pricelist']; ?></p>
Загрузить новый: <input name="price" type="file" value="" /> 
<input type="hidden" name="upload_price" value="1">
<input type="submit" name="button" id="button" value="Загрузить" /> <em>(2Мб максимально)</em>
</form>

<form class="add_form" id="form1" name="form1" method="post" action="">
<h3>Заголовок Новинок</h3>
<input type="text" name="title_value" value="<? print $setting['index_prod_title']; ?>">
<h3>Телефон</h3>
<input type="text" name="site_phone" value="<? print $setting['site_phone']; ?>">
<h3>Почта</h3>
<input type="text" name="site_email" value="<? print $setting['site_email']; ?>">
<h3>ICQ</h3>
<input type="text" name="site_icq" value="<? print $setting['site_icq']; ?>">
<h3>Skype</h3>
<input type="text" name="site_skype" value="<? print $setting['site_skype']; ?>">
<input type="hidden" name="edit_settings" value="1">
<p>
<input type="submit" name="button" id="button" value="Сохранить" />
</p>
</form>