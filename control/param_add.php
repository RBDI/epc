<?
	if ($_POST['add']==1){
		$type=$_POST['type'];
		$name= $_POST['name'];
		$slug= $_POST['slug'];
		$pos= $_POST['pos'];
		if ($pos=='')$pos=0;
		if ($slug=='') $slug=slug($name);
		$logo= $_POST['logo'];
		$archive= $_POST['archive'];
		
		$parent=$_POST['parent'];
		$ID=$_GET['id'];


		
		$tmp_file=$_FILES['logofile']['tmp_name'];
		$result_file=$_FILES['logofile']['name'];
		$type_file= $_FILES['logofile']['type'];
		$size_file= $_FILES['logofile']['size'];
		$error_file = $_FILES['logofile']['error'];			
		
		

		if ($ID){
		 	$sql="UPDATE `shop_params` SET `type`='$type',`name`='$name',`slug`='$slug',`parent`='$parent',`pos`='$pos',`archive`='$archive' WHERE `ID`='$ID'";
		 	if ($result_file!='') $sql="UPDATE `shop_params` SET `type`='$type',`name`='$name',`slug`='$slug',`logo`='$result_file',`parent`='$parent',`pos`='$pos',`archive`='$archive' WHERE `ID`='$ID'";

		}
		else {
			if ($result_file!='') $sql="INSERT INTO `shop_params` (`type`,`name`,`slug`,`parent`,`pos`,`logo`,`archive`) values ('$type','$name','$slug','$parent', '$pos', '$result_file', '$archive')";
			else $sql="INSERT INTO `shop_params` (`type`,`name`,`slug`,`parent`,`pos`,`archive`) values ('$type','$name','$slug','$parent', '$pos', '$archive')";
	
		}		
		// print $sql;
		$result = mysql_query($sql) or die(mysql_error());
		
		if ($ID) $param_id=$ID;
		else $param_id=mysql_insert_id();

		//print getcwd();
		if ($result_file!='') {
			$full_path='../logos/'.$result_file;
			move_uploaded_file($tmp_file, $full_path);
		}

		$title= $_POST['title'];
		$text=$_POST['text'];
		$info=$_POST['info'];
		$param_text_id=$_POST['param_text_id'];

		$page_title=$_POST['page_title'];
		$page_keywords=$_POST['page_keywords'];
		$page_description=$_POST['page_description'];		

		if ($param_text_id!='') $sql="UPDATE `shop_texts` SET `title`='$title',`text`='$text',`info`='$info',`page_title`='$page_title',`page_description`='$page_description',`page_keywords`='$page_keywords' WHERE `ID`='$param_text_id'";
		else $sql="INSERT INTO `shop_texts` (`title`,`text`,`info`,`param_id`,`page_title`,`page_keywords`,`page_description`) values ('$title','$text','$info','$param_id','$page_title','$page_keywords','$page_description')";
		// print $sql;
		$result = mysql_query($sql) or die(mysql_error());
		
		print ' <em>Сохранено!</em>';
	}	
	elseif ($_POST['add']==2){
		$item_param_value=$_POST['item_param_value'];
		$item_param_id= $_POST['item_param_id'];
		$item_param_pos= $_POST['item_param_pos'];		
		$search=$_POST['search'];
		$ID=$_GET['item_param_id'];
		if ($ID) $sql="UPDATE `shop_item_param` SET `value`='$item_param_value',`item_param_id`='$item_param_id',`pos`='$item_param_pos',`search`='$search' WHERE `ID`='$ID'";
		else $sql="insert into `shop_item_param` (`value`,`item_param_id`,`pos`,`type`,`search`) values ('$item_param_value','$item_param_id','$item_param_pos','0','$search')";

		$result = mysql_query($sql) or die(mysql_error());
		print ' <em>Сохранено!</em>';
	}	
	elseif ($_POST['add']==3){
		$cat_param=$_POST['cat_param'];
		$cat_param_value= $_POST['cat_param_value'];
		$search=$_POST['search'];
		$ID=$_GET['item_param_value_id'];
		if ($ID) $sql="UPDATE `shop_item_param` SET `value`='$cat_param_value',`item_param_id`='$cat_param',`search`='$search' WHERE `ID`='$ID'";
		else $sql="insert into `shop_item_param` (`value`,`item_param_id`,`type`,`search`) values ('$cat_param_value','$cat_param','1','$search')";

		$result = mysql_query($sql) or die(mysql_error());
		print ' <em>Сохранено!</em>';
	}	
	if ($_GET['delid']){
		$delete=$_GET['delid'];
		$query = "DELETE FROM `shop_params` WHERE `id`='$delete'";
		mysql_query($query) or die(mysql_error());
		print ' <em>Удалено!</em>';					
	}
	if ($_GET['delparamid']){
		$delete=$_GET['delparamid'];
		$query = "DELETE FROM `shop_item_param` WHERE `id`='$delete'";
		mysql_query($query) or die(mysql_error());
		print ' <em>Удалено!</em>';					
	}	



////////////////////////



$params=get_params();
	foreach($params as $param_id => $param){
		if ($param['parent']==0){
		$all[$param['type']].='<tr>';
		$all[$param['type']].= '<td width="30" align="center"><a href="?param=add&id='.$param['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><td  width="30" align="center" >'.$param['ID'].'</td><td>'.$param['name'].'</td><td>'.$param['slug'].'</td><td>'.$param['pos'].'</td><td  width="30" align="center"><a href="?param=add&delid='.$param['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>';
		$all[$param['type']].= '</tr>';	
		
		$child=makechildtable($params, $param_id );
		if ($child) $all[$param['type']].=$child;
		
		
		
		}
		if ($param['type']==0) $options_params.='<option value="'.$param['ID'].'">'.$param['name'].' - '.$param['ID'].'</option>';
		
	}
	if ($_GET['id']){
		$typex[$params[$_GET['id']]['type']]=' selected="selected"';
		$name=$params[$_GET['id']]['name'];
		$slug=$params[$_GET['id']]['slug'];
		$archive=$params[$_GET['id']]['archive'];
		$pos=$params[$_GET['id']]['pos'];
		$logo=$params[$_GET['id']]['logo'];
		$cur_parent=$params[$_GET['id']]['parent'];
	}

?>

<!-- -->

<form class="add_form" id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
<h3>Категории, Бренды</h3>
<p>
    Название: <input type="text" name="name" id="name" size="100" value="<? echo $name; ?>" /></p>
    <p> 
	Тип:
    <select name="type" id="type">
    	<option value="2" <? echo $typex[2]; ?>>Бренд</option>    
    	<option value="0" <? echo $typex[0]; ?>>Тип</option>
    	<!-- <option value="1">Пол</option> -->
    	<!-- <option value="3" <? echo $type[3]; ?>>Фильтр</option> -->
    </select>
	Родитель: <select name="parent" id="parent">
	<option value="0"> </option>

	<?
	$sql="SELECT * FROM shop_params";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$struct[$row['ID']]=$row;
		// if ((!$_GET['id'])||($_GET['id']!=$row['ID'])){
		// 	if ($row['ID']==$cur_parent) $sel='selected="selected"';
		// 	else $sel='';
		// 	$parent.='<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].' - '.$row['ID'].'</option>';
		// }
	}
	
	$parent=makechildselect($struct, 0, $cur_parent);

	print $parent;
	?>
	</select>
Slug:<input type="text" name="slug" id="slug" value="<? echo $slug; ?>" /> 
Позиция: <input type="text" name="pos" id="pos" size="3" value="<? echo $pos; ?>" />
В архиве: <input type="checkbox" name="archive" <? if ($archive==1) print 'checked="checked"'; ?> value="1">
</p>
<p>Логотип: <input name="logofile" type="file" value="" /></p>

<?
if ($_GET['id']) {
	$param_text=get_param_texts($_GET['id']);
	$title=$param_text['title'];
	$text=$param_text['text'];
	$info=$param_text['info'];
}
?>

<p>Title:
	<textarea name="title" style="width:100%;"><? print $title; ?></textarea></p>
<p>
Text:
<textarea name="text" style="width:100%; height:50px;" class="mceSimple"><? print $text; ?></textarea>
</p>
<p>
Info: <textarea name="info" style="width:100%;"><? print $info; ?></textarea>
</p>
<p>
<em>Title</em> <input name="page_title" type="text" size="100" value="<? echo $param_text['page_title']; ?>" /><br/>
<em>Keywords</em> <input name="page_keywords" type="text" size="100" value="<? echo $param_text['page_keywords']; ?>" /><br/>
<em>Description</em> <input name="page_description" type="text" size="100" value="<? echo $param_text['page_description']; ?>" />
</p>

<input type="hidden" name="param_text_id" value="<? print $param_text['ID']; ?>" />

    <input type="hidden" name="add" value="1" />
  
    <input type="submit" name="button" id="button" value="Сохранить" />
  
  </form>


<h3>Категории</h3>  
<table class="table" cellspacing="1" cellpadding="5">  
<? print $all[0]; ?>
</table>
<h3>Бренды</h3>
<table class="table" cellspacing="1" cellpadding="5">  
<? print $all[2]; ?>
</table>
<!-- ////////////////////////////////// -->

<?
	$all= '<table class="table" cellspacing="1" cellpadding="5">';	
	$item_params=get_item_params();
	
	foreach ($item_params as $id => $item_param){
		
		$all.='<tr>';
		$all.= '<td width="30" align="center"><a href="?param=add&item_param_id='.$item_param['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><td>'.$item_param['value'].' '.$item_param['ID'].'</td><td>'.$params[$item_param['item_param_id']]['name'].'</td><td>'.$item_param['pos'].'</td><td width="30" align="center"><a href="?param=add&delparamid='.$item_param['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>';
		$all.= '</tr>';
		
		if ($_GET['item_param_id']==$item_param['ID']){
			$item_param_id=$item_param['item_param_id'];
			$item_param_value=$item_param['value'];
			$item_param_pos=$item_param['pos'];
			$item_param_search=$item_param['search'];
		}
	}
	$all.= '</table>';
 

?>
 
<form class="add_form" id="form1" name="form1" method="post" action="">
<h3>Характеристики</h3>
  <p><a name="newparam"></a>
    Параметр: <input type="text" name="item_param_value" id="item_param_value" value="<? echo $item_param_value; ?>" />  
Категория:
<select name="item_param_id" id="item_param_id">
    	<option value="0">Без катеории</option>    
    	<? print $options_params; ?>
    </select> 
Поиск: <input type="checkbox" name="search" <? if ($item_param_search==1) print 'checked="checked"'; ?> value="1">
    №: <input type="text" name="item_param_pos" id="item_param_pos" value="<? echo $item_param_pos; ?>" size="3" /> 
    &nbsp; &nbsp; &nbsp; [ <a href="?param=add#newparam">Добавить новый</a> ]

    <input type="hidden" name="add" value="2" />
    <br />
    <input type="submit" name="button" id="button" value="Сохранить" />
  </p>
  </form>
  
  
<? print $all; ?>


<?
	$item_param_values=get_item_param_values();
	
	$all= '<table class="table" cellspacing="1" cellpadding="5">';	
	foreach ($item_param_values as $item_param_value_id => $item_param_value){
		//$cat_param.='<option value="'.$item_param_value['ID'].'">'.$item_param_value['value'].'</option>';
		if ($_GET['item_param_value_id']==$item_param_value['ID']) {			
			$cat_param_value=$item_param_value['value'];
			$item_param_id=$item_param_value['item_param_id'];


		}
		$all.='<tr>';
		$all.= '<td align="center" width="30"><a href="?param=add&item_param_value_id='.$item_param_value['ID'].'#newparam"><img title="Редактировать" src="img/edit.png" border="0"></a></td><td>'.$item_param_value['value'].'</td><td>'.$item_params[$item_param_value['item_param_id']]['value'].' '.$item_params[$item_param_value['item_param_id']]['ID'].'</td><td align="center" width="30"><a href="?param=add&delparamid='.$item_param_value['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>';
		$all.= '</tr>';		
	}
	$all.= '</table>';
	
	foreach ($item_params as $id => $item_param){
		$slctd='';
		if ($id==$item_param_id) $slctd='selected="selected"';
		if ($item_param['type']==0) $options_item_param.='<option value="'.$item_param['ID'].'"'.$slctd.'>'.$item_param['value'].' - '.$item_param['ID'].'</option>';
	}
?>

<form class="add_form" id="form1" name="form1" method="post" action="">
<h3>Значения характеристик</h3>
  <p> 
    Значение: <input type="text" name="cat_param_value" value="<? echo $cat_param_value; ?>" />  
	Характеристика:
	<select name="cat_param">    	
    	<? echo $options_item_param; ?>
    </select> 
    <input type="hidden" name="add" value="3" />
    <br />
    <input type="submit" name="button" value="Сохранить" />
  </p>
  </form>

<? print $all; ?>