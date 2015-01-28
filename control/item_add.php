<?

if ($_GET['update_yml']==1) {
	include "yml.php";
	print 'Файл YML обновлен!';
}

/////////////////////////////////////////////
/////////////////////////////////////////////
//////////////// INSERT OR UPDATE ITEM
/////////////////////////////////////////////
/////////////////////////////////////////////

	if ($_POST['add']==1){ 
	
		/////////////////// IF XLS FILE!
			$tmp_file=$_FILES['xlsfile']['tmp_name'];
				$isxls=0;			
			if ($tmp_file){
				$isxls=1;
				$result_file=$_FILES['xlsfile']['name'];
				$type_file= $_FILES['xlsfile']['type'];
				$size_file= $_FILES['xlsfile']['size'];
				$error_file = $_FILES['xlsfile']['error'];
				$path='../_TMP/';
				$full_path=$path.$result_file;
				$ok=move_uploaded_file($tmp_file, $full_path);
	
				require_once ('Excel/reader.php'); 
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('UTF-8');
				$data->read($full_path);     

				$k=0;
				for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
					$l=0;
				  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
					$full_xls_item[$k][$l]=$data->sheets[0]['cells'][$i][$j];
					$l++;
				  }
				  $k++;
				}	
				unlink($full_path);
			}
		
		// END IF XLS

	if (!$isxls) $till=1;
	else $till=count($full_xls_item);
	$item_mother_param_id = $_POST['item_mother_param_id'];
	$item_param_id=$_POST['item_param_id'];

	$type= $_POST['type'];
	$brand= $_POST['brand'];
	
	

		
	for ($x=0;$x<$till;$x++){
		
	  if ($isxls){
		$name= mysql_escape_string($full_xls_item[$x][0]);
		$brand= $full_xls_item[$x][1];
		//$desc= mysql_escape_string($full_xls_item[$x][2]);
		//$article= $full_xls_item[$x][3];		
		$price= $full_xls_item[$x][2];
		$special_price= $full_xls_item[$x][3];
		//print_r( $name);
		$z=0;

		for ($y=4;$y<count($full_xls_item[$x]);$y++){
			$item_param_value[$z]= $full_xls_item[$x][$y];
			$z++;
		}
	 

	//print_r($item_param);
		//$desc= $full_xls_item[$x][1];
		
		// $price= $_POST['price']; ??  	
	  }

//////////////////////////////////////////////////////////////////////////////////////////
////////////////// MAIN PART OF INSERT OR UPDATE ITEM (IF NOT XLS)
//////////////////////////////////////////////////////////////////////////////////////////
	  
	  else {
		$name= $_POST['name'];	
		$slug_post=$_POST['slug'];		
		$item_param_value= $_POST['item_param_value'];		
		$desc= $_POST['desc'];
		$list= $_POST['list'];
		$exp= $_POST['exp'];		
		//$stars= $_POST['stars'];
		$price= $_POST['price'];
		$special_price= $_POST['special_price'];
		$article= $_POST['article'];
		$count= $_POST['count'];
		$special= $_POST['special'];
		$unq= $_POST['unq'];
		$indexpage= $_POST['indexpage'];
		$archive= $_POST['archive'];

		$tag=$_POST['tag'];

//		$size_scale=$_POST['size_scale'];
/*		
			if ($_POST['size']){
		$size=$_POST['size'];
		$amount=$_POST['amount'];
		$old_size=$_POST['old_size'];
		$old_amount=$_POST['old_amount'];		
			}
*/		
	  }
	  	$brand_slug='';


		$slug=slug($name);
		
		if ($slug!=$slug_post&&$slug_post!='') $slug=$slug_post;	
		
		
	//ГЛАВНЫЙ ВОПРОС	
	  if ($name&&$name!=''){		
	  
	  
	//////////////////////////////////
	///////////////  ИЗМЕНЯТЬ (Обновление значений в базе) 
	//////////////////////////////////		
		if ($_POST['id']) {
			$img_id=$_POST['img_id'];
			$ex_color_name=$_POST['ex_color_name'];
			$img_subitem=$_POST['img-subitem'];
			for ($k=0;$k<count($img_id);$k++){
				$img_id_k=$img_id[$k];
				$sql="UPDATE `shop_img` SET `color`='$ex_color_name[$k]',`subitem_id`='$img_subitem[$img_id_k]' WHERE `ID`='$img_id_k'";
				$result = mysql_query($sql) or die(mysql_error());
			}
			
			$id=$_POST['id'];
//			$sql="UPDATE `shop_catalog` SET `name`='$name',`desc`='$desc',`brand`='$brand',`type`='$type',`price`='$price',`special_price`='$special_price',`article`='$article',`special`='$special',`index`='$indexpage',`slug`='$slug',`size_type`='$size_scale' WHERE `ID`='$id'";
			$sql="UPDATE `shop_catalog` SET `name`='$name',`desc`='$desc',`brand`='$brand',`type`='$type',`price`='$price',`special_price`='$special_price',`article`='$article',`special`='$special',`index`='$indexpage',`slug`='$slug',`archive`='$archive',`count`='$count',`exp`='$exp',`list`='$list',`unq`='$unq' WHERE `ID`='$id'";

			$item_id=$id;
		}		
	//////////////////////////////////	
	/////////////// ДОБАВЛЯТЬ
	//////////////////////////////////	
		else {
//			$sql="insert into `shop_catalog` (`name`,`desc`,`brand`,`type`,`price`,`special_price`,`article`,`special`,`index`,`slug`,`size_type`) values ('$name','$desc','$brand','$type','$price','$special_price','$article','$special','$indexpage','$slug','$size_scale')";
			$sql="insert into `shop_catalog` (`name`,`desc`,`brand`,`type`,`price`,`special_price`,`article`,`special`,`index`,`slug`,`archive`,`count`,`exp`,`list`,`unq`) values ('$name','$desc','$brand','$type','$price','$special_price','$article','$special','$indexpage','$slug','$archive','$count','$exp','$list','$unq')";
		}

		$result = mysql_query($sql) or die(mysql_error());
		
		if (!$item_id) {
			$new_item_id=mysql_insert_id();
			//if ($type) $sql2="insert into `shop_params_links` (`item_id`,`param_id`) values ('$new_item_id','$type')";
		}
		else $new_item_id=$item_id;


	//// ADDING TAG	////////////////////////////////
		if ($tag) {
			$sqlx="insert into `shop_params_links` (`item_id`,`param_id`) values ('$new_item_id','$tag')";
			$result = mysql_query($sqlx) or die(mysql_error());
		}


	//////////////////////////////////	
	/////////////// Добавление значений дополнительных параметров в базу
	//////////////////////////////////

		if ($item_param_value){		 
			for ($j=0;$j<count($item_mother_param_id);$j++){
				$sql='';
				if (!$item_param_id[$j]){				
					//print $item_param_value[$j].' / ';				
					if ($item_param_value[$j]!='') { 
						$sql="INSERT INTO `shop_item_param` (`item_id`,`item_param_id`,`value`) VALUES ('$new_item_id','$item_mother_param_id[$j]','$item_param_value[$j]')";
					}
				}
				else {					 
					$sql="UPDATE `shop_item_param` SET `value`='$item_param_value[$j]' WHERE `ID`='$item_param_id[$j]'";
				}				 
				if ($sql) $result = mysql_query($sql) or die(mysql_error());	
			}
			unset($item_param_id, $item_param_value);			
		}
		if ($sql2) $result = mysql_query($sql2) or die(mysql_error());

////////////////////
//////////////////// SUBITEM 
//////////////////// 
		

		
		$subitem_new=$_POST['subitem_new'];
		
	
		if (count($subitem_new)>0){
			foreach($subitem_new as $prm){
				//print_r($prm);
				if ($prm[1]!=''||$prm[2]!=''){

					$pname=$prm[1];
					$pvalue=$prm[2];
					$pvalue2=$prm[3];
					$pvalue3=$prm[4];
					$instock=$prm[5];
					$in_stock=$prm[6];
					$psql="INSERT INTO `shop_subitem` (`item_id`,`name`,`value1`,`value2`,`value3`,`instock`,`in_stock`) VALUES ('$new_item_id','$pname','$pvalue','$pvalue2','$pvalue3','$instock','$in_stock')";
					$presult = mysql_query($psql) or die(mysql_error());
				}
			}
		}
		
		$subitem=$_POST['subitem'];
		if (count($subitem)>0){
			
			foreach($subitem as $pID => $prm){
				//print_r($prm);
				if ($prm[1]!=''||$prm[2]!=''){
					$pname=$prm[1];
					$pvalue=$prm[2];
					$pvalue2=$prm[3];
					$pvalue3=$prm[4];
					$instock=$prm[5];
					$in_stock=$prm[6];
					$psql="UPDATE `shop_subitem` SET `name`='$pname', `value1`='$pvalue', `value2`='$pvalue2', `value3`='$pvalue3', `instock`='$instock', `in_stock`='$in_stock' WHERE `ID`='$pID'";					
				}
				else{
					$psql = "DELETE FROM `shop_subitem` WHERE `ID`='$pID'";					
				}
				$presult = mysql_query($psql) or die(mysql_error());
			}
		}


////////////////////
//////////////////// ADDON PARAMS
//////////////////// 
		
//		print_r($_POST['item_addon_param_new']);
		
		$item_addon_param_new=$_POST['item_addon_param_new'];
	
		if (count($item_addon_param_new)>0){
			foreach($item_addon_param_new as $prm){
				//print_r($prm);
				if ($prm[1]!=''||$prm[2]!=''){

					$pname=$prm[1];
					$pvalue=$prm[2];
					$psql="INSERT INTO `shop_item_addon_param` (`item_id`,`name`,`value`) VALUES ('$new_item_id','$pname','$pvalue')";
					$presult = mysql_query($psql) or die(mysql_error());
				}
			}
		}
		
		$item_addon_param=$_POST['item_addon_param'];
		if (count($item_addon_param)>0){
			
			foreach($item_addon_param as $pID => $prm){
				//print_r($prm);
				if ($prm[1]!=''||$prm[2]!=''){
					$pname=$prm[1];
					$pvalue=$prm[2];
					$psql="UPDATE `shop_item_addon_param` SET `name`='$pname', `value`='$pvalue' WHERE `ID`='$pID'";					
				}
				else{
					$psql = "DELETE FROM `shop_item_addon_param` WHERE `ID`='$pID'";					
				}
				$presult = mysql_query($psql) or die(mysql_error());
			}
		}
		
	//// SIZES ADD OR UPDATE   /////////////////////
/*		
	$sql='';
   if ($old_size){
	foreach ($old_size as $key => $old_s){
		
		if ($size[$key]!=$old_s) {
			$size_id=$size[$key];
			$sql.="`size_id`='$size_id'";
		}
		
		if ($amount[$key]!=$old_amount[$key]){
			$new_amount=$amount[$key];
			if ($sql!='') $sql.=',';
			$sql.="`amount`='$new_amount'";		
		}
		
		if ($sql!=''){
			
			$sql="UPDATE `shop_instock` SET ".$sql." WHERE `ID`='$key'";
			
			$result = mysql_query($sql) or die(mysql_error());	
		}
	}
   }

	$new_size=$_POST['new_size'];
	$new_amount=$_POST['new_amount'];
	$sql_instock='';
	for ($i=0;$i<3;$i++){
		if ($new_amount[$i]!='') {
			if ($sql_instock!='') $sql_instock.=',';
			$sql_instock.='('.$new_item_id.','.$new_size[$i].','.$new_amount[$i].')';
		}
	}
	if ($sql_instock){
		$sql_instock="insert into `shop_instock` (`item_id`,`size_id`,`amount`) VALUES ".$sql_instock;
		$result = mysql_query($sql_instock) or die(mysql_error());	
	}
*/		
	
	//// FILES ADD OR UPDATE	/////////////////////
  	  if (!$isxls){
	  	 $brand_name='';
		if ($brand) {
			$brand_name_row = mysql_fetch_array(mysql_query("select name from `shop_params` WHERE id=$brand"));
			$brand_name=$brand_name_row['name'];
		}

		//////// UPLOAD ADDON FILES
		
			$tmp_addonfiles=$_FILES['addonfile']['tmp_name'];
			$result_addonfiles=$_FILES['addonfile']['name'];
			$type_addonfiles= $_FILES['addonfile']['type'];
			$size_addonfiles= $_FILES['addonfile']['size'];
			$error_addonfiles = $_FILES['addonfile']['error'];

			foreach ($result_addonfiles as $key => $result_addonfile){
				if ($result_addonfile!=''){
					$full_path='../files/'.$result_addonfile;
					$upload=move_uploaded_file($tmp_addonfiles[$key], $full_path);
					$sql="INSERT INTO `shop_files` (`item_id`,`filename`) VALUES ('$new_item_id','$result_addonfile')";		
					$result = mysql_query($sql) or die(mysql_error());
				}
			}
			
			print ' <em>Сохранено!</em>';
		
		//////// CHANGE FILES DESC-NAME
			if (count($_POST['filesname'])>0){
				foreach ($_POST['filesname'] as $fileid => $filetext){
					$sql="UPDATE `shop_files` SET `text`='$filetext' WHERE `ID`='$fileid'";
					$result = mysql_query($sql) or die(mysql_error());
				}
			}
		
		///////

		//$brand_name_result = str_replace($vowels, "-", $brand_name);
		$name=slug($name);
		

		include "upload.php"; //// MAKING PICTURES
			$color_name=$_POST['color_name'];
			$img_subitem=$_POST['img-char-new'];

		for ($j=0; $j<count($num);$j++){
			$filename=$num[$j];

			$color=$color_name[$j];
			$subitem=$img_subitem[$j];
			$sql="insert into `shop_img` (`item_id`,`filename`,`color`,`subitem_id`) values ('$new_item_id','$filename','$color','$subitem')";		
			$result = mysql_query($sql) or die(mysql_error());					
		}
	  }	
		/// EOFs		
		
		if (!$_POST['id']){
			$name='';
			$desc='';
			$exp='';			
			//$stars='';
			$price='';
			$special_price='';
			//$size='';
			$special='';
			$unq='';
			$indexpage='';
			$slug='';
			$archive='';			
			$type_select=$type;
			$sex_select=$sex;
			$brand_select=$brand;
		}
	  }

	  }	  
  
		print 'Saved!';
	}
	elseif ($_POST['apply']==1){
		
		// GROUP DELETE
		if ($_POST['delete']&&count($_POST['delete'])>0){
			$delete=$_POST['delete'];
			for ($i=0; $i<count($delete);$i++){
				delitem($delete[$i]);
			}
		}
		
		// SET/UNSET ARCHIVE
		
		if (isset($_POST['inarchive_old'])){
			$inarchive_new=array();
			if (isset($_POST['inarchive'])) $inarchive_new=$_POST['inarchive'];
			
			foreach ($_POST['inarchive_old'] as $id=>$value){
				if ($value==1){
					if (!isset ($inarchive_new[$id])) if (isset($sql_archive_unset)) $sql_archive_unset.=' OR id='.$id; else $sql_archive_unset='id='.$id;
				}
				else{
					if (isset ($inarchive_new[$id])&&$inarchive_new[$id]==1) if (isset($sql_archive_set)) $sql_archive_set.=' OR id='.$id; else $sql_archive_set='id='.$id;
				}
			}
		}
		
				
		// SET/UNSET INDEX
		
		if (isset($_POST['onindex_old'])){
			$onindex_new=array();
			if (isset($_POST['onindex'])) $onindex_new=$_POST['onindex'];
			
			foreach ($_POST['onindex_old'] as $id=>$value){
				if ($value==1){
					if (!isset ($onindex_new[$id])) if (isset($sql_index_unset)) $sql_index_unset.=' OR id='.$id; else $sql_index_unset='id='.$id;
				}
				else{
					if (isset ($onindex_new[$id])&&$onindex_new[$id]==1) if (isset($sql_index_set)) $sql_index_set.=' OR id='.$id; else $sql_index_set='id='.$id;
				}
			}
		}
		
		// SET/UNSET POPULAR		

		if (isset($_POST['pops_old'])){
			$pops_new=array();
			if (isset($_POST['pops'])) $pops_new=$_POST['pops'];
			
			foreach ($_POST['pops_old'] as $id=>$value){
				if ($value==1){
					if (!isset ($pops_new[$id])) if (isset($sql_pops_unset)) $sql_pops_unset.=' OR id='.$id; else $sql_pops_unset='id='.$id;
				}
				else{
					if (isset ($pops_new[$id])&&$pops_new[$id]==1) if (isset($sql_pops_set)) $sql_pops_set.=' OR id='.$id; else $sql_pops_set='id='.$id;			
				}
			}
		}		
		if (isset ($sql_archive_set)) {
			$sql="UPDATE `shop_catalog` SET `archive`='1' WHERE ".$sql_archive_set;
			$result = mysql_query($sql) or die(mysql_error());
		}
		if (isset ($sql_archive_unset)) {
			$sql="UPDATE `shop_catalog` SET `archive`='0' WHERE ".$sql_archive_unset;
			$result = mysql_query($sql) or die(mysql_error());
		}
		
		if (isset ($sql_index_set)) {
			$sql="UPDATE `shop_catalog` SET `index`='1' WHERE ".$sql_index_set;
			$result = mysql_query($sql) or die(mysql_error());
		}
		if (isset ($sql_index_unset)) {
			$sql="UPDATE `shop_catalog` SET `index`='0' WHERE ".$sql_index_unset;
			$result = mysql_query($sql) or die(mysql_error());
		}
		if (isset ($sql_pops_set)) {
			$sql="UPDATE `shop_catalog` SET `special`='1' WHERE ".$sql_pops_set;
			$result = mysql_query($sql) or die(mysql_error());
		}
		if (isset ($sql_pops_unset)) {
			$sql="UPDATE `shop_catalog` SET `special`='0' WHERE ".$sql_pops_unset;
			$result = mysql_query($sql) or die(mysql_error());
		}						
		
	}
	
	if ($_GET['del_item']){
		$delete=$_GET['del_item'];
		delitem($delete);		
	}
	
	if ($_GET['del_img']){
		$img_id=$_GET['del_img'];
		delimg($img_id);
	}

	if ($_GET['set_main_img']){
		$img_ID=$_GET['set_main_img'];
		$item_id=$_GET['edit'];
		$sql="UPDATE `shop_img` SET `main`='0' WHERE item_id=$item_id";
		$result = mysql_query($sql) or die(mysql_error());
		
		$sql="UPDATE `shop_img` SET `main`='1' WHERE ID=$img_ID";
		$result = mysql_query($sql) or die(mysql_error());
	}
	
	if ($_GET['delfile']){
		$file_id=$_GET['delfile'];
		delfile($file_id);
	}
	
	
	function delitem($delete){
		$query = "DELETE FROM `shop_catalog` WHERE `id`='$delete'";
		mysql_query($query) or die(mysql_error());	
		
		$query = "DELETE FROM `shop_item_param` WHERE `item_id`='$delete'";
		mysql_query($query) or die(mysql_error());
		
		$query = "DELETE FROM `shop_item_addon_param` WHERE `item_id`='$delete'";
		mysql_query($query) or die(mysql_error());
		
/*
		$query = "DELETE FROM `shop_catalog_links` WHERE `item_id`='$delete'";
		mysql_query($query) or die(mysql_error());
		
		$query = "DELETE FROM `shop_catalog_links` WHERE `clumb_id`='$delete'";
		mysql_query($query) or die(mysql_error());
	
		$query = "DELETE FROM `shop_item_param` WHERE `item_id`='$delete'";
		mysql_query($query) or die(mysql_error());

		$query = "DELETE FROM `shop_params_links` WHERE `item_id`='$delete'";
		mysql_query($query) or die(mysql_error());
*/		
		$sql="select ID from `shop_img` WHERE item_id=$delete";	
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			delimg($row['ID']);
		}
			
	}
	
	function delimg($img_id){
		global $CWD;
		$filename = mysql_fetch_array(mysql_query("select filename from `shop_img` WHERE id=$img_id"));
		$med=$CWD.'/products/'.$filename['filename'].'_medium.jpg';
		$sml=$CWD.'/products/'.$filename['filename'].'_small.jpg';
		unlink ($med);
		unlink ($sml);
		$query = "DELETE FROM `shop_img` WHERE `id`='$img_id'";
		mysql_query($query) or die(mysql_error());	
	}
	
	function delfile($file_id){
		//global $CWD;
		$filename = mysql_fetch_array(mysql_query("select filename from `shop_files` WHERE ID=$file_id"));
		unlink ('../files/'.$filename['filename']);
		$query = "DELETE FROM `shop_files` WHERE `ID`='$file_id'";
		mysql_query($query) or die(mysql_error());	
	}	
	
//////////////////////////////////////////////////////		
//////////////////////////////////////////////////////
///////////////  GETTING ALL ABOUT ITEM
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////	

	if ($_GET['edit']){
		
		$_GET['showadd']=1;

		if ($_GET['rmtag']){
			$params_links_id=$_GET['rmtag'];
			$query = "DELETE FROM `shop_params_links` WHERE `id`='$params_links_id'";
			mysql_query($query) or die(mysql_error());				
		}

		if ($_GET['rmtype']){
			$params_links_id=$_GET['rmtype'];
			$query = "DELETE FROM `shop_params_links` WHERE `id`='$params_links_id'";
			mysql_query($query) or die(mysql_error());				
		}
		
		$id=$_GET['edit'];
		$item_id=$id;
		$sql="select * from `shop_catalog` WHERE id=$id";	
		$result = mysql_query($sql) or die(mysql_error());		
		
		$item=mysql_fetch_array($result);
		
		$name=$item['name'];
		$list=$item['list'];
		$slug=$item['slug'];		
		$desc=$item['desc'];
		$exp=$item['exp'];		
		$archive=$item['archive'];
//		$item_size_type=$item['size_type'];		
		
		$count=$item['count'];
		$article=$item['article'];
		$price=$item['price'];
		$special_price=$item['special_price'];

		$special=$item['special'];
		$unq=$item['unq'];
		$indexpage=$item['index'];
		
		$type_select=$item['type'];
		$brand_select=$item['brand'];

		$sql="select * from `shop_params_links` WHERE item_id=$id";	
		$result = mysql_query($sql) or die(mysql_error());
		$j=0;
		while ($row=mysql_fetch_array($result)) {
			$tag_param[$j][0]=$row['ID'];		
			$tag_param[$j][1]=$row['param_id'];			
			$j++;
		}
		// print_r($tag_param);

		if (!$_GET['copy']) $id='<input type="hidden" name="id" value="'.$id.'" />';


		//////// Дополнительные поля Вывод полей и значений
		
		$sql2="select * from `shop_item_param` WHERE item_id=$item_id AND type=0 ORDER BY ID DESC";			
		$result2 = mysql_query($sql2) or die(mysql_error());
			
		while ($row2=mysql_fetch_array($result2)) {
			$item_param_i[$row2['item_param_id']][0]=$row2['ID'];
			$item_param_i[$row2['item_param_id']][1]=$row2['value'];									
		}

		$sql="select * from `shop_item_param` WHERE (item_param_id=$type_select OR item_param_id=0) AND item_id=0 ORDER BY pos ASC";	
		$result = mysql_query($sql) or die(mysql_error());		
		
		$item_params='<table border="0">';
		
		while ($row=mysql_fetch_array($result)) {
			$item_params.='<tr><!--<td>'.$row['pos'].'</td>--><td><!-- '.$row['ID'].' --><input type="hidden" name="item_mother_param_id[]" value="'.$row['ID'].'" />';
			$item_param_iX='';
			if (!$_GET['copy']) $item_param_iX=$item_param_i[$row['ID']][0];
			$item_params.='<input type="hidden" name="item_param_id[]" value="'.$item_param_iX.'" />';
			$item_params.=$row['value'].'</td><td>';			
			$item_params_values_select= make_item_params_values_select($row['ID'],$item_param_i[$row['ID']][1]);
			if ($item_params_values_select) $item_params.=$item_params_values_select;
			else $item_params.='<input name="item_param_value[]" type="text" value="'.$item_param_i[$row['ID']][1].'" />';
			
			$item_params.='</td></tr>';
		}
		$item_params.='</table>';		
	}

//////////////////////// Загрузка дополнительных полей при добавлении позиции

if ($_GET['add_type'])	{
	$add_type=$_GET['add_type'];
	
	$sql="select * from `shop_item_param` WHERE (item_param_id=$add_type OR item_param_id=0) ORDER BY pos ASC";	
	$result = mysql_query($sql) or die(mysql_error());		
	$type_select=$add_type;
	$item_params_id='';
	$item_params='<table border="0">';
	while ($row=mysql_fetch_array($result)) {
		$item_param_names.=' | '.$row['value'];
		
		$item_params.='<tr><!--<td>'.$row['pos'].'</td>--><td><!-- '.$row['ID'].' --><input type="hidden" name="item_mother_param_id[]" value="'.$row['ID'].'" />';
		$item_params.=$row['value'].'</td><td>';		
		
		$item_params_values_select= make_item_params_values_select($row['ID']);
		if ($item_params_values_select) $item_params.=$item_params_values_select;
		else $item_params.='<input name="item_param_value[]" type="text" value="" />';
		$item_params.='</td></tr>';
	}
	$item_params.='</table>';	
}
////////////////////////


	$sql="select * from `shop_params` ORDER BY ID DESC";	
	$result = mysql_query($sql) or die(mysql_error());	
	
	$type=''; 
	$brand='';
	$sex='';	
	
	while ($row=mysql_fetch_array($result)) {
		$selected = '';	
		$params[$row['ID']]=$row['name'];

			for ($i=0;$i<count($tag_param);$i++){
				if ($tag_param[$i][1]==$row['ID']){
				 	$tag_list.='<strong>'.$row['name'].' <a title="Удалить привязку к тэгу" href="?edit='.$item_id.'&rmtag='.$tag_param[$i][0].'">x</a></strong>, ';
					$y=1;
				}
			}
		
		if ($row['type']==0){ 
			$selected='';
			if ($row['ID']==$type_select) $selected='selected="selected"';
			$t_active='';
			if ($row['ID']==$type_select) $add_types.='<li class="active">'.$row['name'].'</li>';
			else $add_types.='<li><a href="?add_type='.$row['ID'].'">'.$row['name'].'</a></li>';
			$type.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>'; 




			$type_array[$row['ID']]=$row;
			
		}
			
		if ($row['type']==2){ 
			if ($row['ID']==$brand_select) $selected='selected="selected"';		
			$brand.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>'; 
			$brand_array[$row['ID']]=$row;

		}
		
		if ($row['type']==1){
			if ($row['ID']==$sex_select) $selected='selected="selected"';
			$sex.='<option value="'.$row['ID'].'" '.$selected.'>'.$row['name'].'</option>';
		}
			
	}

	$type=makechildselect($type_array,0,$type_select);
	$brand=makechildselect($brand_array,0,$brand_select);

	$subtype=makechildselect($type_array);
	



?>
<!-- <div class="item_categories">
<ul><? print $add_types; ?></ul>
</div>
 -->
<?
if (isset($_GET['showadd'])) $_SESSION['showadd']=$_GET['showadd'];
if (isset($_SESSION['showadd']))	$showadd=$_SESSION['showadd'];
if (isset($showadd)&&$showadd==1) {
	$disp_add='style="display:block;"';
	$disp_butt='style="display:none;"';
}
else {
	$disp_add='style="display:none;"';
	$disp_butt='style="display:block;"';
}
?>
<div style="float:right; margin-top:-20px;"><a href="?products=1&update_yml=1" class="btn btn-default">Обновить YML-файл</a></div>
<div class="add_item_click"<? print $disp_butt; ?> >
<a href="?showadd=1"><img src="img/add.png" border="0" align="absmiddle"/> Добавить
<? 	if ($products==1){  ?> товар<? } else { ?> клумбу<? } ?>
</a>
</div>
<div class="add_item" <? print $disp_add; ?>>
<div style="float:right;">
<a href="?showadd=0">Закрыть [x]</a>
</div>
<?
$action='';
if ($_GET['edit']) $action='?edit='.$_GET['edit'];
else $action='?showadd=0';
?>
<form id="form1" name="form1" method="post" enctype="multipart/form-data" action="<? print $action; ?>">
Категории:
<select name="type" id="type">
<? print $type; ?>
    </select> 
    
Бренд:
<select name="brand" id="brand">
<? print $brand; ?>
    </select> 
<div class="tags_select">
Доп. категории:
<?
print $tag_list;
print'<em>добавить:</em>
    <select name="tag" id="tag">
	<option value=""></option>
'.$subtype.'
    </select>
';
?>
</div>     

    
<?
if (!$_GET['edit'])$name='';
$name=htmlspecialchars($name);
if ($archive) $archivech='checked="checked"';
?>
<p><em>Название:</em><input type="text" name="name" size="40" value="<? echo $name; ?>" /> / <em>Slug:</em> <input type="text" name="slug" value="<? echo $slug; ?>" /> &nbsp; &nbsp; <b>В архиве:</b><input name="archive" type="checkbox" <? print $archivech; ?> value="1" /></p>

<p>    
<em>Краткое описание<br /></em>
<textarea name="exp" cols="70" rows="7" class="mceSimple"><? echo $exp; ?></textarea>
</p>
   
<p>    
<em>Описание<br /></em>
<textarea name="desc" cols="70" rows="12" class="mceSimple"><? echo $desc; ?></textarea>

<? if ($unq) $unqch='checked="checked"'; ?>

Уникальный текст: <input name="unq" type="checkbox" <? print $unqch; ?> value="1" />
</p>    

<!-- <table>
	<tr>
		<td>
 -->
 <!-- Артикул: <input type="text" name="article" value="<? echo $article; ?>" /> -->

<!-- /// -->

Отображение: 
<select name="list">
	<option value="0">Отдельные товары</option>
	<option value="1" <? if ($list==1) print 'selected="selected"'; ?> >Выпадающий список</option>
</select>

<table>
	<tr>
		<th>Характеристика</th>
		<th>Цена</th>
		<th>Цена со скидкой</th>
		<th>Артикул</th>
		<th>ID</th>
		<th>Наличие</th>
		<th>На складе</th>
	</tr>
<?
if ($item_id){
$sql_subitem="select * from `shop_subitem` WHERE item_id=$item_id";
$result_subitem = mysql_query($sql_subitem) or die(mysql_error());
$x=2;

while ($row_subitem=mysql_fetch_array($result_subitem)) {
	$char[$row_subitem['ID']]=$row_subitem['ID'];
	 $se[0]='';
	 $se[1]='';
	 $se[2]=''; 
	if ($row_subitem['instock']==0) $se[0]='selected="selected"';
	elseif ($row_subitem['instock']==1) $se[1]='selected="selected"';
	elseif ($row_subitem['instock']==2) $se[2]='selected="selected"';
	$instk='<select name="subitem['.$row_subitem['ID'].'][5]"><option value="0" '.$se[0].'>В наличии</option><option value="1" '.$se[1].'>Под заказ</option><option value="2" '.$se[2].'>Нет</option></select>';
	if (!$_GET['copy']) {
		print '<tr id="subitem-x"><td><input name="subitem['.$row_subitem['ID'].'][1]" value="'.$row_subitem['name'].'"></td><td><input name="subitem['.$row_subitem['ID'].'][2]" value="'.$row_subitem['value1'].'"></td><td><input name="subitem['.$row_subitem['ID'].'][3]" value="'.$row_subitem['value2'].'"></td><td><input name="subitem['.$row_subitem['ID'].'][4]" value="'.$row_subitem['value3'].'"></td><td>'.$row_subitem['ID'].'</td><td>'.$instk.'</td><td><input size="5" name="subitem['.$row_subitem['ID'].'][6]" value="'.$row_subitem['in_stock'].'"></td></tr>';
	}
	else{
		$x++;
		print '<tr id="subitem-'.$x.'"><td><input name="subitem_new['.$x.'][1]" value="'.$row_subitem['name'].'"></td><td><input name="subitem_new['.$x.'][2]" value="'.$row_subitem['value1'].'"></td><td><input name="subitem_new['.$x.'][3]" value="'.$row_subitem['value2'].'"></td><td><input name="subitem_new['.$x.'][4]" value="'.$row_subitem['value3'].'"></td><td>'.$instk.'</td><td><input size="5" name="subitem['.$row_subitem['ID'].'][6]" value="'.$row_subitem['in_stock'].'"></td></tr>';
	}
}
}

///////////////// GETTING IMAGES FOR ITEM


if ($item_id){
	$sql="select * from `shop_img` where `item_id`='$item_id' ";	
	$result = mysql_query($sql) or die(mysql_error());
	$jj=0;

	while ($row=mysql_fetch_array($result)) {
		$char_select='';
		$sct=0;
		foreach ($char as $value) {
			if ($row['subitem_id']==$value) {
				$char_select.='<option value="'.$value.'" selected="selected">'.$value.'</option>';
				$sct=1;
			}
			else $char_select.='<option value="'.$value.'">'.$value.'</option>';
			
		}
		
		if ($sct!=1) $char_select='<select name="img-subitem['.$row['ID'].']"><option value="" selected="selected">Для всех</option>'.$char_select;
		else $char_select='<select name="img-subitem['.$row['ID'].']"><option value="">Для всех</option>'.$char_select;
		$char_select.='</select>';

		$imgs.='<tr>
		<td> <img src="/products/'.$row['filename'].'_small.jpg" border=0" /></td>
		<td>'.$char_select.'</td>
		<td valign="center" align="right">
		<input type="hidden" name="img_id['.$jj.']" value="'.$row['ID'].'" />		
		<input name="ex_color_name['.$jj.']"  size="10" type="text" value="'.$row['color'].'" />';
		if ($row['main']==0) $imgs.='<br/><a href="?edit='.$_GET['edit'].'&set_main_img='.$row['ID'].'">Главная</a>';
		$imgs.='
</td><td> <a href="?edit='.$item_id.'&del_img='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td>

</tr>';
		$jj++;
	}
}
?>
			<tr>
				<td colspan="4">Новая</td>
			</tr>
			 <tr id="subitem-param-0">			 	
			 	<td><input name="subitem_new[0][1]" value=""></td><td><input name="subitem_new[0][2]" value=""></td><td><input name="subitem_new[0][3]" value=""></td><td><input name="subitem_new[0][4]" value=""></td><td></td>
			 </tr>
</table>			 
			<!-- </div> -->
			<!-- <div id="item_addon_param"></div> -->
				
			<input type="hidden" id="subitem_count" value="<? print $x+1; ?>">
			
			<!-- <a href="javascript:{};" onclick="add_item_addon_param_value_new()">Добавить</a> -->

<!-- /// -->

<!-- Цена: <input type="text" name="price" value="<? echo $price; ?>" /> Цена со скидкой: <input type="text" name="special_price" value="<? echo $special_price; ?>" />
</p> 
Количество: <input type="text" name="count" value="<? echo $count; ?>" />
 -->		
<!-- </td>
		<td>
		<h4>Файлы</h4>
		<table>
<?
$fsql="select * from `shop_files` where `item_id`='$item_id' ";	
$fresult = mysql_query($fsql) or die(mysql_error());
while ($frow=mysql_fetch_array($fresult)) {
	print '<tr><td><input type="text" name="filesname['.$frow['ID'].']" value="'.$frow['text'].'"></td><td><a href="/files/'.$frow['filename'].'">'.$frow['filename'].'</a></td><td> <a href="?edit='.$item_id.'&delfile='.$frow['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td></tr>';
}
?>		
			<tr>
				<td>
					Загрузить новый файл:
				</td>
				<td colspan="2">
					<input name="addonfile[]" type="file" value="" min="1" max="30" multiple="">
				</td>
			</tr>
		</table>
		</tr>
	</tr>
</table> -->
<?
if ($indexpage) $indexch='checked="checked"';
if ($special) $specialch='checked="checked"';
?>
<!-- <p>На главной странице:<input name="indexpage" type="checkbox" <? print $indexch; ?> value="1" /> /  Популярный товар:<input name="special" type="checkbox" <? print $specialch; ?> value="1" /></p> -->
<p>На главной странице: <input name="special" type="checkbox" <? print $specialch; ?> value="1" /></p>
<table border="0">
	<tr>
		<td valign="top">
    <? echo $item_params_id.$item_param_id.$item_params; ?>
    	</td>
		<td valign="top">


			 
<?
if ($item_id){
$sqladdon="select * from `shop_item_addon_param` WHERE item_id=$item_id";
$resultaddon = mysql_query($sqladdon) or die(mysql_error());
$x=2;
while ($rowaddon=mysql_fetch_array($resultaddon)) {
	if (!$_GET['copy']) {
		print '<div id="addon-param-x"><input name="item_addon_param['.$rowaddon['ID'].'][1]" value="'.$rowaddon['name'].'"> <input name="item_addon_param['.$rowaddon['ID'].'][2]" value="'.$rowaddon['value'].'"></div>';
	}
	else{
		$x++;
		print '<div id="addon-param-'.$x.'"><input name="item_addon_param_new['.$x.'][1]" value="'.$rowaddon['name'].'"> <input name="item_addon_param_new['.$x.'][2]" value="'.$rowaddon['value'].'"></div>';
	}
}
}
?>
			 
				<div id="addon-param-0"> <input name="item_addon_param_new[0][1]" value=""> <input name="item_addon_param_new[0][2]" value=""></div>
				<div id="addon-param-1"> <input name="item_addon_param_new[1][1]" value=""> <input name="item_addon_param_new[1][2]" value=""></div>
				<div id="addon-param-2"> <input name="item_addon_param_new[2][1]" value=""> <input name="item_addon_param_new[2][2]" value=""></div>								
			</div>
			<div id="item_addon_param">
			</div>
				
			<input type="hidden" id="item_addon_param_count" value="<? print $x+1; ?>">
			
			<a href="javascript:{};" onclick="add_item_addon_param_value_new()">Добавить</a>
		</td>
	</tr>
</table>	
    <!--Артикул: <input type="text" name="size" value="<? echo $size; ?>" /> -->


<!-- SIZES & AMOUNT -->
<!--
<? if ($item_size_type) { ?>

Размерная шкала: <? echo $size_scale; ?> 

<table cellpadding="" cellspacing="5" border="0"><tr><td valign="top">

<? if ($instock) { ?>

<table cellpadding="" cellspacing="5" border="0">
<tr><td colspan="2" align="left"><em>Наличие товара по размерам</em></td> 
</tr>
<tr>
<th align="left">Размер</th><th align="left">Количество</th> 
</tr>
<tr>
<? echo $instock; ?>
</tr>
</table>
<? } ?>

</td><td valign="top">

<table cellpadding="" cellspacing="5" border="0" style="border:1px dashed #999999;">
<tr><td colspan="2" align="left"><em>Добавить новые размеры:</em></td></tr>
<tr><th align="left">Размер</th><th align="left">Количество</th></tr>
<tr>
<td>
<select name="new_size[0]">
<? echo $new_size; ?>
</select></td>
<td>
<div><input name="new_amount[0]" type="text" value="" size="10" /></div>
</td>
</tr>
<tr>
<td>
<select name="new_size[1]">
<? echo $new_size; ?>
</select></td>
<td>
<div><input name="new_amount[1]" type="text" value="" size="10" /></div>
</td>
</tr>
<tr>
<td>
<select name="new_size[2]">
<? echo $new_size; ?>
</select></td>
<td>
<div><input name="new_amount[2]" type="text" value="" size="10" /></div>
</td>
</tr>
</table>

</td></tr></table>
<? } else { ?>
<strong>Выберите размерную шкалу:</strong> <? echo $size_scale; }?> 

 -->

<table cellpadding="" cellspacing="5" border="0"><tr><td valign="top">
<? if ($imgs){ ?>
<table cellspacing="2" cellspacing="0" border="0" >
<tr><td colspan="3"><em>Изображения:</em></td></tr>
<tr><th>Картинка</th><th>Характеристика</th><th>Title</th><th></th></tr>
<? echo $imgs; } ?>
</table>
</td><td valign="top">

<table cellpadding="" cellspacing="5" border="0" style="border:1px dashed #999999;">
<tr><td colspan="3"><em>Добавить новые изображения</em></td></tr>
<tr><th>Файл</th><th>Характеристика</th><th>Title</th> </tr>
<?

	$char_select='<select name="img-char-new[]"><option value="" selected="selected">Для всех</option>';
	foreach ($char as $value) {
		$char_select.='<option value="'.$value.'">'.$value.'</option>';
	}
	$char_select.='</select>';

?>
<tr><td><input name="userfile[]" type="file" value="" /></td><td><? print $char_select; ?></td><td><input name="color_name[]" type="text" value="" size="10" /></td></tr>
<tr><td><input name="userfile[]" type="file" value="" /></td><td><? print $char_select; ?></td><td><input name="color_name[]" type="text" value="" size="10" /></td></tr>
<tr><td><input name="userfile[]" type="file" value="" /></td><td><? print $char_select; ?></td><td><input name="color_name[]" type="text" value="" size="10" /></td></tr>
</table>

</td></tr></table>

<? if (!$_GET['edit']) { ?>

<div class="add_xls">
Загрузить данные из файла Excel: <input name="xlsfile" type="file" value="" /> (<b>Внимание!</b> Порядок полей в файле XLS: <em>Название | Описание | Артикул | Цена<? print $item_param_names; ?></em>) Также <strong>необходимо</strong> выбрать категорию.</div>
<? } ?>

<!--    <input type="hidden" name="filt" value="1" /> -->
    <input type="hidden" name="add" value="1" /> 
    <? echo $id; ?>
    <input type="submit" name="button" id="button" value="Сохранить &raquo;" />
&nbsp; &nbsp; &nbsp; <a href="?showadd=0">Закрыть форму [x]</a>
<br />
<? 	if ($products!=1){ 
$sql="select * from `shop_catalog_links` where `clumb_id`='$item_id' ORDER BY ID DESC";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$prod_id[$row['item_id']]['ID']=$row['ID'];
	$prod_id[$row['item_id']]['count']=$row['count'];
}

?>


<h3>Товары </h3>
<table class="table" cellspacing="1" cellpadding="5"> 
<tr> <td width="35">Кол-во</td><td>Название товара</td><td>Артикул</td><td>Цена</td></tr>
<?

$sql="select * from `shop_catalog` WHERE brand!=0 ORDER BY brand DESC";
$result = mysql_query($sql) or die(mysql_error());
$i=0;
$old_brand='';
while ($row=mysql_fetch_array($result)) {
	if ($row['brand']!=$old_brand){
		print '<tr><td colspan="4"><em>'.$param[$row['brand']].'</em></td></tr>';
		$old_brand=$row['brand'];
	}
	print '<tr><td>';
	print '<input name="prod_id['.$i.']" type="hidden" value="'.$row['ID'].'" />';
	if (isset($prod_id[$row['ID']]['ID'])) { 
		print '<input name="prod_count['.$i.']"  size="4" type="text" value="'.$prod_id[$row['ID']]['count'].'" />';
	}
	else {
		print '<input name="prod_count['.$i.']"  size="4" type="text" value="" />';
	}
	print'</td><td>'.$row['name'].'</td><td>'.$row['art'].'</td><td>'.$row['price'].'</td></tr>';
	$i++;
}
?>
 </table><br />
 <input type="submit" name="button" id="button" value="Сохранить &raquo;" />
&nbsp; &nbsp; &nbsp; <a href="?showadd=0">Закрыть форму [x]</a>
<? } ?>
</form>

 </div>

<?


if (!isset($_GET['edit'])&&$_GET['showadd']!=1){



if (isset($_GET['rownum'])) $_SESSION['rownum']=$_GET['rownum'];
if (isset($_SESSION['rownum']))	$rownum=$_SESSION['rownum'];
else $rownum=20;
$limit=', '.$rownum;



if ($products==1){
	if (isset($_GET['pagenum'])) $_SESSION['pagenum_p']=$_GET['pagenum'];
	
	if (isset($_SESSION['pagenum_p'])) $pagenumz=$_SESSION['pagenum_p'];
	else $pagenumz=0;
}
else{	
	if (isset($_GET['pagenum'])) $_SESSION['pagenum']=$_GET['pagenum'];
	if (isset($_SESSION['pagenum'])) $pagenumz=$_SESSION['pagenum'];
	else $pagenumz=0;
}
	$start=$pagenumz*$rownum;
	$limit='LIMIT '.$start.$limit;
	
	
?> 
 
<?
$filtered='';
if ($_GET['edit']&&$type_select!='')  $filtered.=" WHERE type=".$type_select;
if ($_POST['add_type']) $filtered.=" WHERE type=".$_POST['add_type'];
if ($_POST['add_brand']) $filtered.=" WHERE brand=".$_POST['add_brand'];
/*
$sql="select * from `shop_params` ORDER BY ID DESC";	
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$param[$row['ID']]=$row['name'];		
}
*/


	$pure_sql="select * from `shop_catalog` $filtered ORDER BY ID DESC ";

	// print $pure_sql;
	
	//$pure_sql="select * from `shop_catalog` ORDER BY ID DESC ";
	
	
	$sql=$pure_sql.$limit;
	
	if ($_POST['searchw']!=''){
		$search_word=$_POST['searchw'];

		$sql="SELECT item_id FROM shop_subitem WHERE value3 LIKE '%$search_word%'";
		$result = mysql_query($sql) or die(mysql_error());
		$sql_artilce='';
		while ($row=mysql_fetch_array($result)) {
			$sql_artilce.=' OR shop_catalog.ID='.$row['item_id'];
		}		

		$sql="SELECT * FROM  `shop_catalog` WHERE  `name` LIKE  '%$search_word%' $sql_artilce";
	}	
	
	$result = mysql_query($sql) or die(mysql_error());
	?>
    

    
<div class="nav">
<div style="float:left;">
	<form name="xx" method="post">
    	<input name="searchw" type="text" />
 
<select name="add_type" >
	<option value=""></option>
<? print $type; ?>
    </select> 
    
 
<select name="add_brand" >
	<option value=""></option>
<? print $brand; ?>
    </select>     	
    	<input name="" type="submit" value="Поиск" />
    </form>
</div>
<form name="form" id="form">
Страница:
<?
	$all=mysql_num_rows(mysql_query($pure_sql));
	$pages=ceil($all/$rownum);
	
?>
  <select name="jumpMenu2" id="jumpMenu2" onchange="MM_jumpMenu('parent',this,0)">
<?  
	for ($i=1;$i<=$pages;$i++){
		$j=$i-1;
		$selected='';
		if ($j==$pagenumz) $selected='selected="selected"'; 
	    print '<option value="?pagenum='.$j.'" '.$selected.'>'.$i.'</option>';
	}
?>    
  </select>
Выводить:
<?
$selecte[$rownum]='selected="selected"'; 
?>
  <select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
    <option value="?rownum=20" <? print $selecte[20]; ?>>20</option>
    <option value="?rownum=50" <? print $selecte[50]; ?>>50</option>
    <option value="?rownum=100" <? print $selecte[100]; ?>>100</option>            
    <option value="?rownum=10000" <? print $selecte[10000]; ?>>Все</option>    
  </select>
</form>
</div>

<script>
function checkall(){	
	var ii=document.getElementById("check_count").value;
	for (i=0; i<ii; i++){	
		var item=document.getElementById("checks"+i);
		if (item) {
			if (item.checked==true)
				item.checked=false;
			else
				item.checked=true;
	 	}	
	}
};
</script>

<form id="form1" name="form1" method="post" action=""> 
<!-- <form id="form1" name="form1" >  -->
<table class="table" cellspacing="1" cellpadding="5"> 
<tr>
	
<td></td><td>Name</td><td>Price</td><td>View</td><td>Brand</td><td>Type</td><td>Архив</td><td>Главн.</td><td>Попул.</td><td></td><td align="center" style="background:#FF0000;">
		<input type="checkbox" onclick="checkall();">
	</td></tr>
    <?
	$k=0;
	while ($row=mysql_fetch_array($result)) {
		$item_id=$row['ID'];
		$img=mysql_fetch_array(mysql_query("select * from `shop_img` WHERE `item_id`='$item_id' LIMIT 1"));
		
	 	if ($products==0){ 
			$sql2="select * from `shop_params_links` WHERE item_id='$item_id'";	
			$result2 = mysql_query($sql2) or die(mysql_error());		
			$item_cats_list='';
			while ($row2=mysql_fetch_array($result2)) {
				$item_cats_list.=$param[$row2['param_id']].', ';
			}
		}
		else  $item_cats_list=$param[$row['brand']];


		
		$sub_sql="SELECT * from `shop_subitem` WHERE `item_id`='$item_id'";
		$sub_result = mysql_query($sub_sql) or die(mysql_error());
		$sub_price='';
		while ($sub_row=mysql_fetch_array($sub_result)){
			$name='';
			if ($sub_row['name']!='') $name=$sub_row['name'].' - ';
			$sub_price.='<div>'.$name.'<span id="price_'.$sub_row['ID'].'"><a href="javascript:{};" onclick="edit_price('.$sub_row['ID'].');" id="price_count_'.$sub_row['ID'].'">'.$sub_row['value1'].'</a></span> руб.</div>';
		}
		
		print '<tr>
		<td width="35" align="center"><a href="?edit='.$row['ID'].'"><img title="Редактировать" src="img/edit.png" border="0"></a> <!--<a href="?edit='.$row['ID'].'&copy=1"><img title="Дублировать" src="img/copy.png" border="0"></a>--></td><td><strong>'.$row['name'].'</strong></td>
		<td width="120">'.$sub_price.'</td>
		<td width="50">';
		$archive_value=0;	
		$index_value=0;	
		$pops_value=0;
		$checks0='';
		$checks1='';
		$checks2='';
		if ($row['archive']==1){
			$archive_value=1;
			$checks0='checked="checked"';
		}				
		if ($row['index']==1){
			$index_value=1;
			$checks1='checked="checked"';
		}
		if ($row['special']==1){
			$pops_value=1;
			$checks2='checked="checked"';			
		}
		
		if  ($img['filename']) print '<img src="/products/'.$img['filename'].'_small.jpg" width="50" border="0">';
		print'</td><td>'.$item_cats_list.'</td><td>'.$params[$row['type']].'</td><td width="30" align="center"><input type="checkbox" name="inarchive['.$row['ID'].']" value="1" '.$checks0.' /><input type="hidden" name="inarchive_old['.$row['ID'].']" value="'.$archive_value.'"></td><td width="30" align="center"><input type="checkbox" name="onindex['.$row['ID'].']" value="1" '.$checks1.' /><input type="hidden" name="onindex_old['.$row['ID'].']" value="'.$index_value.'"></td><td width="30" align="center"><input type="checkbox" name="pops['.$row['ID'].']" value="1" '.$checks2.' /><input type="hidden" name="pops_old['.$row['ID'].']" value="'.$pops_value.'"></td><td width="30" align="center"><a href="?del_item='.$row['ID'].'"><img title="Удалить" src="img/del.png" border="0"></a></td><td width="30" align="center"><input type="checkbox" id="checks'.$k.'" name="delete[]" value="'.$row['ID'].'" /></td></tr>';
		$k++;
	}
	print '<tr><td colspan="5"><td colspan="4" align="center"><input id="check_count" name="check_count" type="hidden" value="'.$k.'" /><input type="submit" name="button2" id="button2" value=" &uarr; Применить &uarr;" /></td></tr></table>';
	print '<input type="hidden" name="apply" value="1" />';
	print '</form>';
	
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
<?
}
?>