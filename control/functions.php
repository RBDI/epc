<?

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

function makechildselect($struct, $parent=0, $select, $dash=''){
	$list='';
	foreach ($struct as $key => $row) {
		if ($row['parent']==$parent) {
			$selected='';
			if ($row['ID']==$select) $selected='selected="selected"';
			$list.='<option value="'.$row['ID'].'" '.$selected.'>'.$dash.$row['name'].'</option>';
			
			$child=makechildselect($struct, $row['ID'], $select ,$dash.'&nbsp;&nbsp;&nbsp;');
			if ($child) $list.=$child;			
		}
	}	
	return $list;
}

function makechildtable($struct, $parent=0, $dash=''){
	$list='';
	foreach ($struct as $key => $row) {
		if ($row['parent']==$parent) {
			$list.='<tr><td width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><td  width="30" align="center" >'.$row['ID'].'</td><td>&nbsp;'.$dash.'&mdash; '.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td  width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td></tr>';
			
			$child=makechildtable($struct, $row['ID'], $dash.'&mdash;');
			if ($child) $list.=$child;
			$ID=$row['ID'];
			if ($row['order']==0)  mysql_query("UPDATE `shop_params` SET `order`='$ID' WHERE `ID`='$ID'");
		}
	}
	//if ($list) $list='<ul>'.$list.'</ul>';
	return $list;
}

function get_param_texts ($param_id){
	$sql="SELECT * FROM `shop_texts` WHERE param_id=$param_id";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$param_texts[$row['param_id']]=$row;
	}
	$out=$param_texts[$param_id];	
	return $out;
}

function get_params (){
	$sql="SELECT * FROM `shop_params` ORDER BY pos,ID ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}
	return $params;
}

function get_item_params ($param_id=0){
	if ($param_id!=0) $sql="SELECT * FROM `shop_item_param` WHERE item_id=0 AND type=0 AND item_param_id=$param_id";
	else $sql="SELECT * FROM `shop_item_param` WHERE item_id=0 AND type=0";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}	
	return $params;
}

function get_item_param_values ($item_param_id=0){
	if ($item_param_id!=0) $sql="SELECT * FROM `shop_item_param` WHERE item_id=0 AND type=1 AND item_param_id=$item_param_id";
	else $sql="SELECT * FROM `shop_item_param` WHERE item_id=0 AND type=1";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$params_values[$row['ID']]=$row;
	}	
	return $params_values;
}

function get_item_params_values ($item_id){
	$sql="SELECT * FROM `shop_item_param` WHERE item_id=$item_id";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$values[$row['ID']]=$row;
	}	
	return $values;
}

function make_item_params_values_select($item_param_id,$current_id=0){
	$item_param_values=get_item_param_values($item_param_id);
	//$item_params_values=get_item_params_values($item_id);
	if (count($item_param_values)>0){
		$options='<select name="item_param_value[]">';
		foreach ($item_param_values as $ID => $item_param_value){
			if ($current_id!=0&&$current_id==$item_param_value['ID']) $options.='<option value="'.$item_param_value['ID'].'" selected="selected">'.$item_param_value['value'].' - '.$item_param_value['ID'].'</option>';
			else $options.='<option value="'.$item_param_value['ID'].'">'.$item_param_value['value'].' - '.$item_param_value['ID'].'</option>';
		}
		$options.='</select>';
	}
	return $options;
}
?>