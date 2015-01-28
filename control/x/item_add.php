<?
	if ($_POST['add']==1){ 
		$name= $_POST['name'];
		$desc= $_POST['desc'];
		$type= $_POST['type'];
		$sex= $_POST['sex'];
		$brand= $_POST['brand'];
		$count= $_POST['count'];
		$price= $_POST['price'];
		$size= $_POST['size'];
		$special= $_POST['special'];
	
		$vowels = array(" ", '"', "'", "%", "$", "#", "<", ">", "", "/", "|", "*", "&", "^", "_", "(", ")");
		$slug=strtolower(str_replace($vowels, "-", $name));
		
		if ($_POST['id']) {
			$img_id=$_POST['img_id'];
			$ex_color_name=$_POST['ex_color_name'];
			
			for ($k=0;$k<count($img_id);$k++){
				$sql="UPDATE `shop_img` SET `color`='$ex_color_name[$k]' WHERE `ID`='$img_id[$k]'";
				$result = mysql_query($sql) or die(mysql_error());
			}
			
			
			
			$id=$_POST['id'];
			$sql="UPDATE `shop_catalog` SET `name`='$name',`desc`='$desc',`type`='$type',`sex`='$sex',`brand`='$brand',`count`='$count',`price`='$price',`size`='$size',`special`='$special',`slug`='$slug' WHERE `ID`='$id'";
			$item_id=$id;
		}
		else {
			$sql="insert into `shop_catalog` (`name`,`desc`,`type`,`sex`,`brand`,`count`,`price`,`size`,`special`,`slug`) values ('$name','$desc','$type','$sex','$brand','$count','$price','$size','$special','$slug')";
		}

		$result = mysql_query($sql) or die(mysql_error());
		if (!$item_id) {
			$new_item_id=mysql_insert_id();
		}
		else $new_item_id=$item_id;

		
		/// FILES
		$brand_name = mysql_fetch_array(mysql_query("select name from `shop_params` WHERE id=$brand"));


		$brand_name_result = str_replace($vowels, "-", $brand_name['name']);
		$name=str_replace($vowels, "-", $name);

		include "upload.php"; //// MAKING PICTURES
				
		for ($j=0; $j<count($num);$j++){
			$filename=$num[$j];
			$color_name=$_POST['color_name'];
			$color=$color_name[$j];
			$sql="insert into `shop_img` (`item_id`,`filename`,`color`) values ('$new_item_id','$filename','$color')";		
			$result = mysql_query($sql) or die(mysql_error());					
		}
		/// EOFs		
		
		if (!$_POST['id']){
			$name='';
			$desc='';			
			$count='';
			$price='';
			$size='';
			$special='';
			$slug='';
			
			$type_select=$type;
			$sex_select=$sex;
			$brand_select=$brand;
		}
		
		print '<br />Saved!';
	}
	elseif ($_POST['del']==1){
		$delete=$_POST['delete'];
		for ($i=0; $i<count($delete);$i++){
			$query = "DELETE FROM `shop_catalog` WHERE `id`='$delete[$i]'";
			mysql_query($query) or die(mysql_error());			
		}
	}
	
	if ($_GET['del_img']){
		$img_id=$_GET['del_img'];
		$filename = mysql_fetch_array(mysql_query("select filename from `shop_img` WHERE id=$img_id"));
		$med='/home/u216092/decor4home.ru/www/products/'.$filename['filename'].'_medium.jpg';
		$sml='/home/u216092/decor4home.ru/www/products/'.$filename['filename'].'_small.jpg';
		unlink ($med);
		unlink ($sml);
		$query = "DELETE FROM `shop_img` WHERE `id`='$img_id'";
		mysql_query($query) or die(mysql_error());			
	}
		
	print '<br/>';
	
	
	if ($_GET['edit']){
		$id=$_GET['edit'];
		$item_id=$id;
		$sql="select * from `shop_catalog` WHERE id=$id";	
		$result = mysql_query($sql) or die(mysql_error());		
		$item=mysql_fetch_array($result);
		$name=$item['name'];
		$desc=$item['desc'];
		$size=$item['size'];
		$price=$item['price'];
		$count=$item['count'];
		$special=$item['special'];
		
		$type_select=$item['type'];
		$sex_select=$item['sex'];
		$brand_select=$item['brand'];				
		$id='<input type="hidden" name="id" value="'.$id.'" />';
	}

	$sql="select * from `shop_params` ORDER BY ID DESC";	
	$result = mysql_query($sql) or die(mysql_error());	
	$type='<option value=""></option>'; $brand='<option value=""></option>'; $sex='<option value=""></option>';	
	while ($row=mysql_fetch_array($result)) {
		$selected = '';	
		if ($row['type']==0){ 
			if ($row['ID']==$type_select) $selected='selected="selected"'; 
			$type.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>'; 
		}
		if ($row['type']==1){
			if ($row['ID']==$sex_select) $selected='selected="selected"';
			$sex.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>';
		}
		if ($row['type']==2){ 
			if ($row['ID']==$brand_select) $selected='selected="selected"';		
			$brand.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>'; 
		}
	}

	if ($item_id>0){
	$sql="select * from `shop_img` where `item_id`='$item_id' ";	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$imgs.='<input type="hidden" name="img_id[]" value="'.$row['ID'].'" /><input name="ex_color_name[]" type="text" value="'.$row['color'].'" /> <img src="/products/'.$row['filename'].'_small.jpg" border=0" /> <a href="?edit='.$edit.'&del_img='.$row['ID'].'">Del (X)</a> <br />';
	}
	}

	
?>
<hr size="1">
<a href="?">Добавление новой позиции (+)</a>

<form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
  <p>
    Тип:
    <select name="type" id="type">
<? print $type; ?>    	
    </select>
    Бренд:
    <select name="brand" id="brand">
<? print $brand; ?>
    </select>
<!--     
    Пол:
    <select name="sex" id="sex">
<? print $sex; ?>
    </select> -->  &nbsp; Порядковый № <input type="text" name="count" size="3" value="<? echo $count; ?>" />      <br /><br />
    <input type="text" name="name" value="<? echo $name; ?>" /> название <br />
    описание<br />
    <textarea name="desc" cols="70" rows="10"><? echo $desc; ?></textarea> <br />
    Артикул: <input type="text" name="size" size="15" value="<? echo $size; ?>" /> &nbsp;
    Цена: <input type="text" name="price" size="7" value="<? echo $price; ?>" />  руб.
    &nbsp;     &nbsp;
    <em>SPECIAL:</em> <input type="text" name="special" size="4" value="<? echo $special; ?>" /> <br />
    <? echo $imgs; ?>
    <br />
	Добавить цвет/вид:<br />
	<input name="color_name[]" type="text" value="" /> <input name="userfile[]" type="file" value="" /><br />
	<input name="color_name[]" type="text" value="" /> <input name="userfile[]" type="file" value="" /><br />
	<input name="color_name[]" type="text" value="" /> <input name="userfile[]" type="file" value="" /><br />        

<!--    <input type="hidden" name="filt" value="1" /> -->
    <input type="hidden" name="add" value="1" /> 
    <? echo $id; ?>
    <input type="submit" name="button" id="button" value="Вперед &raquo;" />
   <br />
  </p>
  
</form>
 
<?
		

/*	
	if ($_POST['type']||$_POST['sex']||$_POST['brand']) {
		$filter='WHERE ';
		if ($_POST['type']) $filtered.='type='.$_POST['type'].' ';
		if ($_POST['brand']&&$filtered) $filtered.='AND brand='.$_POST['brand'].' ';
		elseif($_POST['brand']&&!$filtered) $filtered.='brand='.$_POST['brand'].' ';
		if ($_POST['sex']&&$filtered) $filtered.='AND sex='.$_POST['sex'].' ';
		elseif($_POST['sex']&&!$filtered) $filtered.='sex='.$_POST['sex'].' ';		
	}
*/	
	$sql="select * from `shop_catalog` ".$filter.$filtered." ORDER BY ID DESC";	
//	print $sql;
	$result = mysql_query($sql) or die(mysql_error());
	print '<form id="form1" name="form1" method="post" action="">';
	print '<table class="table" cellspacing="1" cellpadding="2">';
	while ($row=mysql_fetch_array($result)) {
		print '<tr><td>'.$row['ID'].'</td><td>'.$row['count'].'</td><td>'.$row['name'].'</td><td>'.$row['type'].'</td><td>'.$row['brand'].'</td><td>'.$row['desc'].'</td><td>'.$row['price'].'</td><td>'.$row['size'].'</td><td><a href="?edit='.$row['ID'].'">Изменить</a> / Удалить:<input type="checkbox" name="delete[]" value="'.$row['ID'].'" /> <a href="#DELETE">&rarr;</a> </td></tr>';
	}
	print '<tr><td colspan="8"></td><td bgcolor="#444444"><a name="DELETE"></a><input type="submit" name="button2" id="button2" value="Удалить!" /></td></tr></table>';
	print '<input type="hidden" name="del" value="1" /></form>';

?>

<!--
<hr/>
<form id="form1" name="form1" method="post" action="">
  <p>
  	Splitter: <input type="text" name="splitter" value=""/> | Default: Tab 
  	<input type="checkbox" name="def_split" id="def_split" checked="checked" />
  	<br />
	<textarea name="item" cols="80" rows="30"><? print $_POST['item']; ?></textarea>
    <input type="hidden" name="add" value="1" /><br />
    <input type="submit" name="button" id="button" value="Add &raquo;" />
  </p>
</form>
-->
<?
/*
	if($_POST['item']){
		print '<hr/>';
		if ($_POST['def_split']) $split='
		'; //$split='	';
		else $split=$_POST['splitter'];
		$tok = strtok($_POST['item'], $split);

		while ($tok !== false) {
		$n++;
			echo "$n. $tok<br />";
			$tok = strtok($split);
		}
	}
*/	
?>
