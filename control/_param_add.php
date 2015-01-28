<?

	if ($_POST['add']==1){
		$type=$_POST['type'];
		$name= $_POST['name'];
		$slug= $_POST['slug'];
		if ($slug=='') $slug=slug($name);
		$logo= $_POST['logo'];
		//$parent_cats_list= $_POST['parent_cats_list'];		
		$order= $_POST['order'];				
		$ID=$_GET['id'];
		$desc=$_POST['desc'];
		$title=$_POST['title'];			
		
		$tmp_file=$_FILES['logofile']['tmp_name'];
		$result_file=$_FILES['logofile']['name'];
		$type_file= $_FILES['logofile']['type'];
		$size_file= $_FILES['logofile']['size'];
		$error_file = $_FILES['logofile']['error'];
		
		
		if ($ID) {
			if ($result_file!='') $sql_logo=",`logo`='$result_file'";
			$sql="UPDATE `shop_params` SET `type`='$type',`name`='$name',`slug`='$slug' $sql_logo WHERE `ID`='$ID'";
			$result = mysql_query($sql) or die(mysql_error());
			
			
			$sql="select ID from `shop_texts` where `param_id`='$ID'";
			$result = mysql_query($sql) or die(mysql_error());
			$row=mysql_fetch_array($result);
					
			if (!$row){
				$sql="insert into `shop_texts` (`param_id`,`title`,`text`) values ('$ID','$title','$desc')";
				$result = mysql_query($sql) or die(mysql_error());
			}
			else {
				$sql="UPDATE `shop_texts` SET `title`='$title',`text`='$desc' WHERE `param_id`='$ID'";
				$result = mysql_query($sql) or die(mysql_error());
			}
		}
		else {
			$sql="insert into `shop_params` (`type`,`name`,`slug`,`logo`) values ('$type','$name','$slug','$result_file')";
			$result = mysql_query($sql) or die(mysql_error());
			$new_param_id=mysql_insert_id();
			$sql="insert into `shop_texts` (`param_id`,`title`,`text`) values ('$new_param_id','$title','$desc')";
			$result = mysql_query($sql) or die(mysql_error());
		}
		
		
		if ($type==5){
			$old_size_value=$_POST['old_size_value'];
			$size_value=$_POST['size_value'];
			$new_size_value=$_POST['new_size_value'];
			
			if ($size_value){
				foreach ($size_value as $key=>$value){
					if ($old_size_value[$key]!=$value&&$value!=''){
						if ($sql_size_value) $sql_size_value.=',';
						$sql_size_value.="(`value`='$value')";
			
						$sql="UPDATE `shop_sizes` SET `value`='$value' WHERE `ID`='$key'";
						$result = mysql_query($sql) or die(mysql_error());
					}
					elseif ($value==''){
						$query = "DELETE FROM `shop_sizes` WHERE `ID`='$key'";
						mysql_query($query) or die(mysql_error());				
					}
				}
			}
						
			if ($new_size_value){
				foreach ($new_size_value as $new_size){
					$type_param_id=$ID;
					if ($new_size!=''){
						$sql="insert into `shop_sizes` (`type_param_id`,`value`) values ('$type_param_id','$new_size')";
						$result = mysql_query($sql) or die(mysql_error());
					}
				}
			}
		}
		
		$full_path='../logos/'.$result_file;
		print $tmp_file;
		if ($tmp_file!='') move_uploaded_file($tmp_file, $full_path);
		
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



$all= '<table class="table" cellspacing="1" cellpadding="5">';
$size='<table class="table" cellspacing="1" cellpadding="5">';

	$sql="select * from `shop_params`  ORDER BY ID DESC";	
	$result = mysql_query($sql) or die(mysql_error());
	$i=0;
	
	while ($row=mysql_fetch_array($result)) {
		if ($row['type']==0){
			$struct[$i]=$row;
			$cats_list_arr[$i][0]=$row['ID'];
			$cats_list_arr[$i][1]=$row['name'];
			$i++;
		}
		elseif ($row['type']==2) {
		
		$all.='<tr>';
		$all.= '<td  width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><!--<td>'.$row['ID'].'</td>--><td>'.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>';
		$all.= '</tr>';
		}
		elseif ($row['type']==5) {
		
		$size.='<tr>';
		$size.= '<td  width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><!--<td>'.$row['ID'].'</td>--><td>'.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>';
		$size.= '</tr>';
		}		
		
		if ($row['type']==0) {
			$category[$row['ID']]=$row['name'];
			$item_params[$row['ID']]=$row['name'];	
		}
		
				
		if ($_GET['id']==$row['ID']){
			$type_select[$row['type']]=' selected="selected"';
			$name=$row['name'];
			$slug=$row['slug'];
			$logo=$row['logo'];
			//$order=$row['order'];
			//$parent=$row['parent'];
		}
	}
	$size.= '</table>';	
	$all.= '</table>';


//--------
$list='';
if (isset($_GET['moveup'])){
	foreach ($struct as $key => $row) {
		if ($row['parent']==0){
			
			if ($row['ID']==$_GET['moveup']&&isset($prev_pos)){
				$prev_pos_id=$prev_pos['ID'];			
				$prev_pos_order=$prev_pos['order'];
				$cur_pos_id=$row['ID'];
				$cur_pos_order=$row['order'];
				
				$struct[$key]=$prev_pos;
				$struct[$prev_key]=$row;				
				
				mysql_query("UPDATE `shop_params` SET `order`='$prev_pos_order' WHERE `ID`='$cur_pos_id'");
				mysql_query("UPDATE `shop_params` SET `order`='$cur_pos_order' WHERE `ID`='$prev_pos_id'");				
			}		
			
			$prev_pos=$row;
			$prev_key=$key;		

			makechildlist($struct, $row['ID'] );
			
			
		}
	}
}
/*
if (isset($_GET['movedown'])){
}
*/

function makechildlist($struct, $parent=0){
	$list='';
	foreach ($struct as $key => $row) {
		if ($row['parent']==$parent) {

			if ($row['ID']==$_GET['moveup']&&isset($prev_pos)){
				$prev_pos_id=$prev_pos['ID'];			
				$prev_pos_order=$prev_pos['order'];
				$cur_pos_id=$row['ID'];
				$cur_pos_order=$row['order'];
				
				$struct[$key]=$prev_pos;
				$struct[$prev_key]=$row;
				
				mysql_query("UPDATE `shop_params` SET `order`='$prev_pos_order' WHERE `ID`='$cur_pos_id'");
				mysql_query("UPDATE `shop_params` SET `order`='$cur_pos_order' WHERE `ID`='$prev_pos_id'");				
			}		
			
			$prev_pos=$row;
			$prev_key=$key;

			 makechildlist($struct, $row['ID'] );
			
		}
	}
}


//--------



foreach ($cats_list_arr as $key => $value){
	if ($value[0]!=$parent) $cats_list.='<option value="'.$value[0].'">'.$value[1].'</option>';
	else  $cats_list.='<option value="'.$value[0].'" selected="selected">'.$value[1].'</option>';	
	
}

foreach ($struct as $key => $row) {
	if ($row['parent']==0){
		$list.='<tr><td width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><!--<td>'.$row['ID'].'</td>--><td>'.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td></tr>';
		$child=makechildtable($struct, $row['ID'] );
		if ($child) $list.=$child;
		
		$ID=$row['ID'];
		if ($row['order']==0)  mysql_query("UPDATE `shop_params` SET `order`='$ID' WHERE `ID`='$ID'");
		
	}
}


function makechildtable($struct, $parent=0, $dash=''){
	$list='';
	foreach ($struct as $key => $row) {
		if ($row['parent']==$parent) {
			$list.='<tr><td width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><!--<td>'.$row['ID'].'</td>--><td>&nbsp;'.$dash.'&mdash; '.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td  width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td></tr>';
			
			$child=makechildtable($struct, $row['ID'], $dash.'&mdash;');
			if ($child) $list.=$child;
			$ID=$row['ID'];
			if ($row['order']==0)  mysql_query("UPDATE `shop_params` SET `order`='$ID' WHERE `ID`='$ID'");
		}
	}
	//if ($list) $list='<ul>'.$list.'</ul>';
	return $list;
}

$all_cats= '<table class="table" cellspacing="1" cellpadding="5">'.$list.'</table>';



?>



<!-- -->

<div class="add_item">
<h2><a href="?param=add">Добавление</a> / редактирование элемента структуры</h2>
<form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
 <? if (!$_GET['id']) $name=''; ?>
    Название <input type="text" name="name" id="name" value="<? echo $name; ?>" /> 

Тип: <select name="type" id="type">
    	<option value="0" <? echo $type_select[0]; ?>>Категория</option>
    	<option value="2" <? echo $type_select[2]; ?>>Бренд</option>    
    	<option value="5" <? echo $type_select[5]; ?>>Размерная шкала</option>            
    	<!-- <option value="1">Пол</option> -->
    	<!--<option value="3" <? echo $type[3]; ?>>Фильтр</option>-->
    </select>
    
    
<!-- №: <input type="text" name="order" value="<? echo $order; ?>" size="3" />   -->

<!--
    <br />    
Родитель:     <select name="parent_cats_list" >
<option value="" selected="selected">Нет</option>
<? print $cats_list; ?>
</select>
-->

 <? if (!$type_select[5]) { ?>
Slug: <input type="text" name="slug" id="slug" value="<? echo $slug; ?>" /> <br />
Изображение: <input name="logofile" type="file" value="" />
<p>
<?
$xparam_id=$_GET['id'];
$xsql="SELECT * FROM shop_texts WHERE param_id='$xparam_id'";
$xresult = mysql_query($xsql) or die(mysql_error());
$texts=mysql_fetch_array($xresult)
?>
<em>Заголовок</em> <input name="title" type="text" size="30" value="<? echo $texts['title']; ?>" />
<em>Описание<br /></em>
<textarea name="desc" cols="70" rows="10" class="mceSimple"><? echo $texts['text']; ?></textarea>
</p>
<? } 
else {
	$type_param_id=$_GET['id'];
	$sql="SELECT * FROM shop_sizes WHERE type_param_id='$type_param_id'";
	$result = mysql_query($sql) or die(mysql_error());
	print '<table>';
	while ($row=mysql_fetch_array($result)) {
		$old_value.='<tr><td><input type="text" name="size_value['.$row['ID'].']" value="'.$row['value'].'"><input type="hidden" name="old_size_value['.$row['ID'].']" value="'.$row['value'].'"></td></tr>';
	}
	if ($old_value) print '<tr><td>Размерны</td></tr>'.$old_value;
	print '</table>';
?>
<table>
<tr><th>Добавить новые размеры</th></tr>
<tr><td><input type="text" name="new_size_value[]" value=""></td></tr>
<tr><td><input type="text" name="new_size_value[]" value=""></td></tr>
<tr><td><input type="text" name="new_size_value[]" value=""></td></tr>
</table>
<?	} ?>	     
    

    <input type="hidden" name="add" value="1" />
    <br />
    <input type="submit" name="button" id="button" value="Сохранить" />
 
  </form>
</div>  
<h3>Категории</h3>
<? print $all_cats; ?>
<h3>Бренды</h3>
<? print $all; ?>

<!-- ////////////////////////////////// -->
<?

/*
$all= '<table class="table" cellspacing="1" cellpadding="2">';
	$sql="select * from `shop_item_param` WHERE item_id=0 ORDER BY item_param_id DESC";	
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$all.='<tr>';
		$all.= '<td>'.$row['ID'].'</td><td>'.$row['pos'].'</td><td>'.$category[$row['item_param_id']].'</td><td>'.$row['value'].'</td><td><a href="?param=add&item_param_id='.$row['ID'].'#newparam">Редактировать</a></td><td><a href="?param=add&delparamid='.$row['ID'].'">Удалть</a></td>';
		$all.= '</tr>';
		
		if ($_GET['item_param_id']==$row['ID']){
			$item_param_id=$row['item_param_id'];
			$item_param_value=$row['value'];
			$item_param_pos=$row['pos'];
		}
	}
	$all.= '</table>';
*/	
?>

<?
/*
foreach ($item_params as $key => $value) {
	$select='';
	if ($key==$item_param_id) $select=' selected="selected"';
   $item_cats.='<option value="'.$key.'" '.$select.'>'.$value.'</option>';
}
*/
?>

<!-- 
<div class="add_item">

<form id="form1" name="form1" method="post" action="">
 <a name="newparam"></a>
    Параметр: <input type="text" name="item_param_value" id="item_param_value" value="<? echo $item_param_value; ?>" />  
Категория:
<select name="item_param_id" id="item_param_id">
    	<option value="0">Без катеории</option>    
    	<?// print $item_cats; ?>
    </select> 
    №: <input type="text" name="item_param_pos" id="item_param_pos" value="<? echo $item_param_pos; ?>" size="3" /> 
    &nbsp; &nbsp; &nbsp; [ <a href="?param=add#newparam">Добавить новый</a> ]

    <input type="hidden" name="add" value="2" />
    <br />
    <input type="submit" name="button" id="button" value="Сохранить/Добавить" />
  
  </form>
</div>  
  
<? //print $all; ?>
-->