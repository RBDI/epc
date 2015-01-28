<?
$params=get_params();
foreach($params as $param_id => $param){
	if ($param['parent']==0){
	$all[$param['type']].='<tr>';
	$all[$param['type']].= '<td width="30" align="center"><input type="checkbox" name="param['.$param_id.']" checked id="'.$param_id.'" class="xx param_'.$param['type'].'" value="'.$param['parent'].'"></td><td>'.$param['name'].' <small>('.$param['ID'].')</small></td>';
	$all[$param['type']].= '</tr>';	
	
	$child=makechildtable_($params, $param_id,$param['type'] );
	if ($child) $all[$param['type']].=$child;
	}
}
?>
<form method="POST" action="getprice/">
	<input name="test" type="hidden" value="1">
	<button type="submit" class="btn btn-info">Сохранить прайс <span class='glyphicon glyphicon-save'></span></button>
<div class="row">
	<div class="col-sm-6">
		<h3>Категории</h3>
		<a href="#" onclick="checkall(0,0)">Снять выделение</a> / <a href="#" onclick="checkall(0,1)">Выделить все</a>  
		<table class="table" cellspacing="1" cellpadding="5">  
		<? print $all[0]; ?>
		</table>
	</div>
	<div class="col-sm-6">

		<h3>Бренды</h3>
		<a href="#" onclick="checkall(2,0)">Снять выделение</a> / <a href="#" onclick="checkall(2,1)">Выделить все</a>  
		<table class="table" cellspacing="1" cellpadding="5">  
		<? print $all[2]; ?>
		</table>
	</div>
</div>
</form>
<?
function makechildtable_($struct, $parent=0, $type, $dash=''){
	$list='';
	foreach ($struct as $key => $row) {
		if ($row['parent']==$parent) {
			// $list.='<tr><td width="30" align="center"><a href="?param=add&id='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a></td><td  width="30" align="center" >'.$row['ID'].'</td><td>&nbsp;'.$dash.'&mdash; '.$row['name'].'</td><td>'.$row['slug'].'</td><td>'.$row['logo'].'</td><td  width="30" align="center"><a href="?param=add&delid='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td></tr>';
			$list.='<tr><td width="30" align="center"><input type="checkbox" name="param['.$row['ID'].']" id="'.$row['ID'].'" checked  class="xx param_'.$row['type'].'" value="'.$row['parent'].'"></td><td>&nbsp;'.$dash.'&mdash; '.$row['name'].' <small>('.$row['ID'].')</small></td></tr>';
			$child=makechildtable_($struct, $row['ID'], $row['type'], $dash.'&mdash;');
			if ($child) $list.=$child;
			$ID=$row['ID'];
			if ($row['order']==0)  mysql_query("UPDATE `shop_params` SET `order`='$ID' WHERE `ID`='$ID'");
		}
	}
	//if ($list) $list='<ul>'.$list.'</ul>';
	return $list;
}
?>
<script type="text/javascript">
function checkall (type,check) {
	
	if (check==0) $('.param_'+type).prop("checked", false);
	
	else if (check==1) $('.param_'+type).prop("checked", "checked");
	
}
function checkparent (parent) {
	if (parent!=0) {
		$('[name="param['+parent+']"]').prop("checked", true);		
		checkparent ($('[name="param['+parent+']"]').val());
	}
}

function checkchild (id) {	
	var obj = $('[value="'+id+'"]');
	if (obj){
		obj.prop("checked", true);
		obj.each(function() {
			checkchild($(this).attr('id'));
		});				
	}		
}

function uncheckchild (id) {	
	var obj = $('[value="'+id+'"]');
	if (obj){
		obj.prop("checked", false);
		obj.each(function() {
			uncheckchild($(this).attr('id'));
		});				
	}		
}

$('.xx').change(function() {
	if ($(this).prop("checked")){
		checkparent($(this).val());
		checkchild($(this).attr("id"));
	}
	else {
		uncheckchild($(this).attr("id"));
	}
	
});
</script>