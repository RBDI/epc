<?php get_header(); ?>
<div class="white-block">
	<div class='row'>
	<!--
		<div class='span12 pingation'>
			<? dimox_breadcrumbs(1); ?>
			<p><a href='#null'>Каталог</a>  /  <a href='#null'>Видеорегистраторы</a>  / Garmin</p>
		</div>
	-->	
		<div class='span10'>		
<?
global $new_title;
global $view_type,$view_brand, $special, $URL_PARTS;

if ($URL_PARTS[1]!='products') {
	if ($URL_PARTS[1]=='special') print '<h1> '.get_newprd_title('index_prod_title').' </h1>';
	//else print '<h2> '.$new_title.' </h2>';
}

//if (!$_POST['is_search']){
	if ($URL_PARTS[1]=='products') {

		$item=get_item($URL_PARTS[2]);	

		//print "<div class='product noborder'>";

		print "<h3 style='margin-top:0px;'><a href='/shop/".$item['type_slug']."'>".$item['type_name']."</a> / <a href='/shop/".$item['brand_slug']."'>".$item['brand_name']."</a></h3>";
		print '<div class="row">';
		print "<div class='span5 product-view'>";
		foreach ($item['image'] as $image_id =>$image){			
			$previews.= '<div class="preview_s"><a href="javascript:{};" onclick="show_image(\''.$image['filename'].'\')"><img src="/products/'.$image['filename'].'_small.jpg" alt="" /></a></div>';
			$bigimg="<img src='/products/".$image['filename']."_medium.jpg' id='bigimg' alt='' />";
		}
		print "<p> $bigimg </p>";
		print $previews;
		if (count($item['files'])!=0){		 
			print "<h4>Files</h4>";
			foreach ($item['files'] as $file_id =>$file){
				if ($file['text']!='') $fname=$file['text'];
				else $fname=$file['filename'];
				print "<li><a href='/files/".$file['filename']."'>".$fname." </a></li>";
			}			 
		}
		print "</div>";
			
		if ($item['special_price']>0) $price=$item['special_price'];
		else $price=$item['price'];
		print "
			<div class='span5 product-data'>";
		print "<h2>".$item['brand_name']."<br/> ".$item['name']."</h2>";
		print "
				<p class='product-price-big'>Prix: ".$price." &euro;</p>";
		if ($item['special_price']>0) print '<p>Ancien prix: <span style="text-decoration: line-through;">'.$item['price'].' &euro;</span></p>';
		/*	
		if ($item['count']>0&&$item['archive']==0) print "<div class='label label-success'>en stock</div>";
		elseif ($item['count']==0&&$item['archive']==0) print "<div class='label label-warning'>sur demande</div>";
		elseif ($item['archive']!=0) print "<div class='label'>pas en stock</div>";
		*/
		print $item['desc'];

		if (count($item['params'])!=0){
			//print "<h4>title</h4>";
			print "<table class='table table-condensed'>";
			foreach ($item['params'] as $param_id =>$param){
				if (($param_id==6209||$param_id==6942)&&$param['value']!='') $param['value'].=' &euro;';
				
				if ($item['type_id']==202 && $param_id==6034 && $param['value']=='') $param['value']="0.75";

				if ($param_id==6034&&$param['value']!='') $param['value'].=" L";
				
				if ($param['value']!='') print "<tr><td width='250'>".$param['name']."</td><td>".$param['value']."</td></tr>";
				
				
			}
			print "</table>";
		}		
		//
		if (count($item['addon_params'])!=0){
			print "<div class='char'>";
			//print "<h4>title</h4>";
			print "<table class='table table-condensed'>";
			foreach ($item['addon_params'] as $param_id =>$param){
				print "<tr><td width='242'>".$param['name']."</td><td>".$param['value']."</td></tr>";
			}
			print "</table>";
			print "</div>";			
		}

 
		if ($_POST['buy']){
			print 'Товар добавлен в корзину! <br />
Вы можете сразу перейти к <a href="/shop/order">Оформлению заказа &rarr;</a> или сделать это позже через ссылку в верхней части страницы.';
		}
						
		else{

			if ($item['archive']==1) print "<div class='label label-warning'>No</div>";
			else print '					 
						<a href="javascript:{};" onclick="putinbasket('.$item['ID'].')" id="ache_'.$item['ID'].'" class="btn btn-large btn-success" >Acheter</a> Total: <input style="width:50px;" type="text" id="count_'.$item['ID'].'" value="1">
					<div id="finir" style="display:none; margin-top:10px;"><a class="btn btn-warning"  href="/shop/order">Valider &rarr;</a></div>						
			              
			           
				 ';
							
		}

		print '<p align="center"><img src="/logos/'.$item['brand_logo'].'" width="150" /></p>';

		print '</div>';		
		print "</div>";
		
	}
	elseif ($URL_PARTS[1]=='order')	print order();
	else {
		if ($_GET['clear']=='filter') {unset($_SESSION['fltr']);}

		if ($_POST['srch']||$_POST['srch_price']){
			$_SESSION['fltr']['price']=$_POST['srch_price'];			
			$_SESSION['fltr']['params']=$_POST['srch'];

			// $_SESSION['fltr']['brand']=$_POST['filter_brand'];
			// $_SESSION['fltr']['intrvl']=$_POST['intrvl'];
			$_SESSION['fltr']['cat_id']=$view_type;
		}

		if ($_SESSION['fltr']['cat_id']!=$view_type) unset($_SESSION['fltr']);
		
		if ($_POST['search_word']!=''){
			$items=get_search_result($_POST['search_word']);
		}
		elseif ($URL_PARTS[1]=='special'){
			$items=get_special_products(1);
		}
		else{
			//print $view_brand;
			if ($view_type)	$items=get_category_products($view_type,0,$_SESSION['fltr']);
			elseif ($view_brand) $items=get_category_products($view_brand, 2);
		}
		$items_count=count($items);
		
		if ($view_type) {
			foreach ($items as $item_id => $item){
				$brands[$item['brand_id']]['name']=$item['brand_name'];
				$brands[$item['brand_id']]['slug']=$item['brand_slug'];
				$brands[$item['brand_id']]['logo']=$item['brand_logo'];
			}

			$types[$view_type]['name']='';
			$types[$view_type]['slug']='';


			$subcats=get_subcat($view_type);
			$subcat_str='';
			foreach ($subcats as $subcat_id => $subcat) {
				if ($subcat_str!='') $subcat_str.=', ';
				$subcat_str.='<a href="/shop/'.$subcat['slug'].'/">'.$subcat['name'].'</a>';

				$types[$subcat_id]['name']='';
				$types[$subcat_id]['slug']='';
			}
			if ($subcat_str!='') $subcat_str='/ '.$subcat_str;

		}
		elseif ($view_brand){
			foreach ($items as $item_id => $item){
				$types[$item['type_id']]['name']=$item['type_name'];
				$types[$item['type_id']]['slug']=$item['type_slug'];

			}	
			$brands=get_category_brands($types);
		}

		$search_params=get_search_params($types);

		foreach ($search_params as $param_id => $param) {
			$srch[$param_id]=$param['name'].': <select name="srch['.$param_id.']"><option value="">Tous</option>';
			foreach ($param['values'] as $value) {
				$sl='';
				if ($_SESSION['fltr']['params'][$param_id]==$value) $sl=' selected="selected" ';
				if ($value!='') $srch[$param_id].='<option value="'.$value.'" '.$sl.'>'.$value.'</option>';
			}

			$srch[$param_id].='</select> ';
		}

		//print_r($srch);

		$prices=get_category_prices($types);
		//print_r($prices);
		$prc='Prix: <select name="srch_price">
		<option value="">Tous</option>';
		foreach ($prices as $value) {
			$chk='';
			if ($_SESSION['fltr']['price']==$value) $chk=' selected="selected" ';
			$prc.='<option value="'.$value.'" '.$chk.'>'.$value.' - '.($value+20).' &euro;</option>';
		}
		
		
		$prc.='</select> ';

		$ttl='';
		foreach ($types as $type_id => $type) {
					$ttl.='<a href="/shop/'.$type['slug'].'/">'.$type['name'].'</a> ';
				}		
		print '<h1 style="margin-top:0px;">'.$ttl.$new_title.' <span style="font-size:28px;">'.$subcat_str.'</span>   </h1>';
		$brand_nav='<ul class="breadcrumb">';
		$active='';
		foreach ($brands as $id => $brand) {
			if ($id==$view_brand) $active='class="active"';
			$brand_nav.='<li '.$active.'><a href="/shop/'.$brand['slug'].'"><img src="/logos/'.$brand['logo'].'" width="60"></a><br/><a href="/shop/'.$brand['slug'].'">'.$brand['name'].'</a></li>';
			$active='';
		}
		$brand_nav.='</ul>';

		print $brand_nav;

		// FILTER

		if ($view_type==202){
		print '<div class="srch"><form method="post" action="?page=1">';
		print $prc;

		foreach ($srch as $param_id => $value) {			
			print $value;
		}
		print ' <input type="submit" value="Trouver" class="btn btn-success">
		<input type="hidden" name="isfilter" value="1">
		</form></div>';
		}
		//END OF FILTER



		if ($items_count>15&&!$_POST['search_word']){
			$page_nav='<ul>';
			for ($i=1;$i<=ceil($items_count/15);$i++){
				if (($_GET['page']&&$_GET['page']==$i)||(!$_GET['page']&&$i==1)) $page_nav.='<li class="active"><a href="?page='.$i.'">'.$i.'</a></li>';
				else $page_nav.='<li><a href="?page='.$i.'">'.$i.'</a></li>';
			}
			$page_nav.='</ul>';

		}

		//if ($page_nav) print '<div class="pagination">'.$page_nav.'</div>';

		if ($_GET['page']) $page=$_GET['page'];
		else $page=1;
		if (count($items)==0&&$_POST['search_word']!='') print '<h2>По вашему запросу товаров не найдено</h2><p>Попробуйте воспользоваться поиском еще раз. Поиск осуществляется по всей фразе в названиях брендов, категорий и продуктов.</p>';
		$i=0;
		
		print '<div class="row">';
		foreach ($items as $item_id => $item){
			$i++;
//			print $i.'-'.$item_id.' / ';			
			if((($i>($page-1)*15)&&($i<=($page)*15))||$_POST['search_word']!=''){				
			print "<div class='span2 product'>";
			 
			if ($item['image']) {
			print "
				<div class='product-preview'>
					<p><a href='/shop/products/".$item_id."'><img src='/products/".$item['image']."_small.jpg' title='".$item['name']."' /></a></p>
				</div>";
				 
			}	
			if ($item['special_price']>0) $price=$item['special_price'];
			else $price=$item['price'];
			
			//$price=substr($price,0,-3).' '.substr($price,-3);
			if ($item['brand_name']!='') $item['brand_name']='<i>'.$item['brand_name']."</i>";
			$title=$item['brand_name'];
			if ($item['name']!=''&&$item['name']!=' ') $title.="<br> ".$item['name'];
			if ($item['item_param']['6207']!='') $title.="<br> ".$item['item_param']['6207'];
			if ($item['item_param']['6208']!='') $title.="<br> ".$item['item_param']['6208'];

			if ($item['item_param']['6034']!='') $title.=" ".$item['item_param']['6034']."L";
			 
			if ($item['type_id']==202||$view_type==202) $packz=' Carton de ';
				else $packz=' Pack de ';
			if ($item['item_param']['6045']!='') $title.= "<br/> ".$packz.$item['item_param']['6045'];

			print "
				<div class='product-desc' >
					<h4><a href='/shop/products/".$item_id."'>".$title."</a></h4>					
					<p class='product-price'>Prix: ".$price." &euro; 
					<!--<a href='javascript:{};' id='ache_".$item_id."' onclick='putinbasket(".$item_id.")' class='btn btn-mini btn-success'>Acheter</a> -->
					<a href='/shop/products/".$item_id."'  class='btn btn-mini btn-info'>Voir</a>
					<input type='hidden' id='count_".$item_id."' value='1'>";

			if ($item['special_price']>0) print '<br/>Ancien prix: <span style="text-decoration: line-through;">'.$item['price'].' &euro;</span>';
			print "</p>";

			/*
			if ($item['count']>0&&$item['archive']==0) print "<div class='label label-success'>en stock</div>";
			elseif ($item['count']==0&&$item['archive']==0) print "<div class='label label-warning'>sur demande</div>";
			elseif ($item['archive']!=0) print "<div class='label'>pas en stock</div>";
			
			if ($item['exp']!='') print "<div class='exp'>".$item['exp']."</div>";
			else print "<div class='exp'>".$item['desc']."</div>";
			*/
			print "</div>";			
			print "</div>";
			}
		}
		print '</div>';
	}


?>

<div class="row">
	<div class="span10">
<div class="pagination" style="margin-top:20px;">
<? print $page_nav; ?>  
</div>
	</div>
</div>
		</div>
<? //get_sidebar(); ?>
	</div>
</div>	
<?php  get_footer(); ?>