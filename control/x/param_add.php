<?

	if ($_GET['del_img']){
		$img_id=$_GET['del_img'];
		$filename = mysql_fetch_array(mysql_query("select filename from `shop_img` WHERE id=$img_id"));
		$med='/home/u216092/decor4home.ru/www/products/'.$filename['filename'].'_.jpg';
		unlink ($med);
		$query = "DELETE FROM `shop_img` WHERE `id`='$img_id'";
		mysql_query($query) or die(mysql_error());			
	}
	
	if ($_POST['add']==1){
		$type=$_POST['type'];
		$name= $_POST['name'];
		$slug= $_POST['slug'];		
		$ID=$_GET['id'];
		if ($ID) $sql="UPDATE `shop_params` SET `type`='$type',`name`='$name',`slug`='$slug' WHERE `ID`='$ID'";
		else $sql="insert into `shop_params` (`type`,`name`,`slug`) values ('$type','$name','$slug')";

		$result = mysql_query($sql) or die(mysql_error());
		
		/// FILES
		
		
		
		$vowels = array(" ", '"', "'", "%", "$", "#", "<", ">", "", "/", "|", "*", "&", "^", "_", "(", ")");
		$brand_name_result = 'cat';
		$name=$slug;
		$C_AA=1;
		
		include "upload.php"; //// MAKING PICTURES
		
		if ($num[0]){
			$filename=$num[0];
			$img_cat_ID='CAT'.$ID;
			$sql="insert into `shop_img` (`item_id`,`filename`,`color`) values ('','$filename','$img_cat_ID')";		
			$result = mysql_query($sql) or die(mysql_error());					
		}
		/// EOFs
		
		print ' <em>Сохранено!</em>';
	}

$all= '<table class="table" cellspacing="1" cellpadding="2">';
	$sql="select * from `shop_params` ORDER BY ID DESC";	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$all.='<tr>';
		$all.= '<td>'.$row['ID'].'</td><td>'.$row['type'].'</td><td>'.$row['name'].'</td><td>'.$row['slug'].'</td><td><a href="?param=add&id='.$row['ID'].'">Изменить</a></td>';
		$all.= '</tr>';
		
		if ($_GET['id']==$row['ID']){
			$type[$row['type']]=' selected="selected"';
			$name=$row['name'];
			$slug=$row['slug'];
			
			$img_cat_ID='CAT'.$row['ID'];
			$sql2="select * from `shop_img` where `color`='$img_cat_ID' ";	
			$result2 = mysql_query($sql2) or die(mysql_error());
			while ($row2=mysql_fetch_array($result2)) {
				$imgs.='<input type="hidden" name="img_id[]" value="'.$row2['ID'].'" /><img src="/products/'.$row2['filename'].'_.jpg" border=0" /> <a href="?param=add&id='.$row['ID'].'&del_img='.$row2['ID'].'">Del (X)</a> <br />';
	}
		}
	}
	$all.= '</table>';
?>

<form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
  <p>
    Название <input type="text" name="name" id="name" value="<? echo $name; ?>" /> 
Slug <input type="text" name="slug" id="slug" value="<? echo $slug; ?>" />    
    <select name="type" id="type">
    	<option value="2" <? echo $type[2]; ?>>Бренд</option>    
    	<option value="0" <? echo $type[0]; ?>>Тип</option>
    	<!-- <option value="1">Пол</option> -->
    	<option value="3" <? echo $type[3]; ?>>Фильтр</option>
    </select><br />
    
<? echo $imgs; ?>
    
	Добавить картинку: <input name="userfile[]" type="file" value="" />    
    <input type="hidden" name="add" value="1" />
    <br />
    <input type="submit" name="button" id="button" value="Сохранить" />
  </p>
  </form>
  <? print $all; ?>