<?php
session_start();
//include("settings.php");

function fphone ($phone){
	$phone2=preg_replace('/[^0-9]/', '', $phone);	 
	if (strlen($phone2)==11) {
		$fn=mb_substr($phone2, 0, 1);
		if ($fn==7||$fn==8){
			$phone3=mb_substr($phone2, 1, strlen($phone2));
		}
		else {
			$phone3=$phone2;
		}
	}
	else {
		$phone3=$phone2;
	}
	return $phone3;
}

function get_top_menu($active,$type=0){
	if ($type==2) $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 AND parent=0 ORDER BY name ASC";
	else $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 AND parent=0 ORDER BY pos ASC";
	$result = mysql_query($sql) or die(mysql_error());
	if ($active==0) $act=' class="active"';
	$menu='<li'.$act.'><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>';
	while ($row=mysql_fetch_array($result)) {
		$act='';
		if ($row['ID']==$active) $act=' class="active"';
		$menu.='<li'.$act.'><a href="/catalog_'.$row['slug'].'.html">'.$row['name'].'</a>';
	}
	return $menu;
}

function get_top_brandmenu(){
	$sql="SELECT * FROM shop_params WHERE type=2 AND archive=0 AND parent=0 ORDER BY name ASC";
	
	$result = mysql_query($sql) or die(mysql_error());
	
	while ($row=mysql_fetch_array($result)) {		
		$mnu[$row['ID']]=$row;
	}


	foreach ($mnu as $id => $val) {
		$act='';
		// if ($mnu['ID']==$active) $act='active';
		$menu.='<li><a href="/catalog_'.$val['slug'].'.html">'.$val['name'].'</a>';
		
		// if (count($subcat)>0){
		// 	$menu.='<ul class="dropdown-menu">';
		// 	foreach ($subcat as $sub_id => $subval) {
		// 		if ($subval['parent']==$id) $menu.='<li><a href="/catalog_'.$subval['slug'].'.html">'.$subval['name'].'</a></li>';
		// 	}
		// 	$menu.='</ul>';            
		// } 
      	$menu.='</li>';		
	}



	return $menu;
}

function get_top_allmenu($active,$type=0){
	if ($type==2) $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 AND parent=0 ORDER BY name ASC";
	else $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 AND parent=0 ORDER BY pos ASC";
	$result = mysql_query($sql) or die(mysql_error());
	if ($active==0) $act='active';
	$ids='';
	while ($row=mysql_fetch_array($result)) {		
		$mnu[$row['ID']]=$row;
		if ($ids=='') $ids=$row['ID'];
		else $ids.=', '.$row['ID'];
	}

	$sql="SELECT * FROM shop_params WHERE parent IN ($ids) AND archive=0";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$subcat[$row['ID']]=$row;
	}

	// $menu='<li class="'.$act.'"><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>';
	foreach ($mnu as $id => $val) {
		$act='';
		// if ($mnu['ID']==$active) $act='active';
		$menu.='<li class="dropdown '.$act.'"><a href="/catalog_'.$val['slug'].'.html" class="dropdown-toggle js-activated" data-toggle="dropdown">'.$val['name'].'</a>';

		if (count($subcat)>0){
			$menu.='<ul class="dropdown-menu">';
			foreach ($subcat as $sub_id => $subval) {
				if ($subval['parent']==$id) $menu.='<li><a href="/catalog_'.$subval['slug'].'.html">'.$subval['name'].'</a></li>';
			}
			$menu.='</ul>';            
		} 
      	$menu.='</li>';		
	}



	return $menu;
}

function get_full_menu($active,$type=0){
	if ($type==2) $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 ORDER BY name ASC";
	else $sql="SELECT * FROM shop_params WHERE type=$type AND archive=0 ORDER BY pos ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$array[$row['ID']]=$row;
	}
	$menu=gfm(0,$array,$active);

	return $menu['menu'];
}

function get_full_menu0(){
	$sql="SELECT * FROM shop_params WHERE type=0 AND archive=0 ORDER BY pos ASC";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$array[$row['ID']]=$row;
	}
	$menu=gfm0(0,$array);

	return $menu;
}

function gfm ($parent, $array,$active=0){
	foreach ($array as $key => $value) {
		if ($value['parent']==$parent) {
			$act='';
			if ($active==$value['ID']) {
				$act=' class="active"';
				$menu['active']=1;
			}
			$menu['menu'].='<li'.$act.'><a href="/catalog_'.$value['slug'].'.html">'.$value['name'].'</a>';
			$submenu=gfm($key, $array,$active);
			if ($submenu['menu']!=''){
				$cls='';
				if ($submenu['active']!=1&&$active!=$value['ID']) $cls=' class="hidden"';
				else $cls=' class="overactive"';
				if ($submenu['active']==1) $menu['active']=$submenu['active'];
				$menu['menu'].='<ul'.$cls.'>'.$submenu['menu'].'</ul>';
			}
			$menu['menu'].='</li>';
		}
	}
	return $menu;
}

function gfm0 ($parent, $array){
	foreach ($array as $key => $value) {
		if ($value['parent']==$parent) {			
			
			 
			$menu.='<li><a href="/catalog_'.$value['slug'].'.html">'.$value['name'].'</a>';
			$submenu=gfm0($key, $array);
			if ($submenu!=''){								
				$menu.='<ul>'.$submenu.'</ul>';
			}
			$menu.='</li>';
		}
	}
	return $menu;
}

function get_type_menu(){
	$sql="SELECT * FROM shop_params WHERE type=0 AND parent=0";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$menu.='<li><a href="/catalog_'.$row['slug'].'.html">'.$row['name'].'</a></li>
';
	}
	return $menu;
}

function get_brands_menu(){
	$brands=get_active_brands();
	foreach ($brands as $ID => $brand) {
		$menu.='<li><a href="/catalog_'.$brand['slug'].'.html">'.$brand['name'].'</a></li>';

	}
	return $menu;
}

function get_cat($slug){
	$sql="SELECT * FROM shop_params WHERE slug='$slug'";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$cat=$row;
	}
	return $cat;
}

function get_url_parts($url){
	$url_parts_=split('[/.]', $url);
	foreach ($url_parts_ as $value) {
		if ($value!='') $url_array[]=$value;
	}
	return $url_array;
}

function get_subcat($cat_id){
	$sql="SELECT * FROM shop_params WHERE parent=$cat_id AND archive=0";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$subcat[$row['ID']]=$row;
	}
	return $subcat;
}

function get_active_brands(){
	$sql="SELECT ID, name FROM shop_params WHERE ID IN (SELECT type FROM shop_catalog WHERE archive!=1) AND type=0 ORDER BY name ASC";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$cats_ids[$row['ID']]=$row['name'];
	}
	$active_brands=get_category_brands($cats_ids);
	return $active_brands;
}

function get_category_brands($cat_ids){
	$sql='';
	foreach ($cat_ids as $cat_id => $cat_name) {
		if ($sql=='') $sql='type='.$cat_id;
		else $sql.=' OR type='.$cat_id;
	}

	$sql="SELECT * FROM shop_params WHERE ID IN (SELECT brand FROM shop_catalog WHERE $sql) AND type=2 AND parent=0 AND archive!=1 ORDER BY name ASC";
	//print $sql;
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$brands[$row['ID']]=$row;
	}
	return $brands;
}

function get_search_params($cat_ids){

	$sql='';
	foreach ($cat_ids as $cat_id => $cat_name) {
		if ($sql=='') $sql='item_param_id='.$cat_id;
		else $sql.=' OR item_param_id='.$cat_id;
	}
	$sql="SELECT * FROM shop_item_param WHERE item_id=0 AND (item_param_id=0 OR $sql) AND search=1";
	$result = mysql_query($sql) or die(mysql_error());
	$sql='';
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
		if ($sql!='') $sql.=' OR item_param_id='.$row['ID'];
		else $sql.='item_param_id='.$row['ID'];
	}
	
	unset($row);
	$sql="SELECT * FROM shop_item_param WHERE $sql";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		if ($row['value']!=' ') $param_values[$row['item_param_id']][]=$row['value'];
	}

	foreach ($param_values as $param_id => $values) {
		$new_array[$param_id]['name']=$params[$param_id]['value'];
		$new_array[$param_id]['values']=array_unique($values);
		sort($new_array[$param_id]['values']);
	}


	//print_r($new_array);
	return $new_array;

}

function get_category_prices($cat_ids){
	$sql='';
	foreach ($cat_ids as $cat_id => $cat_name) {
		if ($sql=='') $sql='type='.$cat_id;
		else $sql.=' OR type='.$cat_id;
	}

	$sql="SELECT price, special_price FROM shop_catalog WHERE $sql";
	$i=0;
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		if ($row['special_price']!=0) $prices[$i]=$row['special_price'];
		else $prices[$i]=$row['price'];
		$i++;
	}
	sort($prices);
	
	$X1=round($prices[0],-1);

	if ($X1>$prices[0]) $X1=$X1-10;


	$XN=round($prices[count($prices)-1],-1);

	if ($XN<$prices[count($prices)-1]) $XN=$XN+10;

	$Xi=$X1;
	$n=0;
	while ($Xi <= $XN) {
		$X[$n]=$Xi;
		$Xi+=20;
		$n++;
	}
	
	//asort($X);

	// print_r($X);
	return $X;
}

function get_settingz(){
	$sql="SELECT * FROM `shop_settings`";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$out[$row['setting']]=$row['value'];
	}
	return $out;
}

function get_newprd_title($setting){
	$sql="SELECT value FROM `shop_settings` WHERE setting='$setting'";
	$result = mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$out=$row['value'];

	return $out;
}

function get_category_params($cat_id){
	$sql="SELECT * FROM shop_item_param WHERE item_param_id=$cat_id";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}
	return $params;
}

function get_param_chiled($parma_id){
	$sql="SELECT * FROM shop_item_param WHERE item_param_id=$parma_id AND type=1";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}
	return $params;
}

function dimox_breadcrumbs($catalog='') {
  $delimiter = '&rsaquo;'; //разделить между ссылками
  $name = 'Главная'; //текст ссылка "Главная"
  $currentBefore = '<span class="current">';
  $currentAfter = '</span>';
  if ( !is_home() && !is_front_page() || is_paged() ) {

    echo '<div id="crumbs">';

    global $post;
    $home = get_bloginfo('url');
    echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';

    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $currentBefore;
      single_cat_title();
      echo $currentAfter;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('d') . $currentAfter;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('F') . $currentAfter;

    } elseif ( is_year() ) {
      echo $currentBefore . get_the_time('Y') . $currentAfter;

    } elseif ( is_single() ) {
      $cat = get_the_category(); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo $currentBefore;
      the_title();
      echo $currentAfter;

    } elseif ( is_page() && !$post->post_parent ) {
      echo $currentBefore;
      the_title();
      echo $currentAfter;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;

    } elseif ( is_search() ) {
      echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;

    } elseif ( is_tag() ) {
      echo $currentBefore . 'Posts tagged &#39;';
      single_tag_title();
      echo '&#39;' . $currentAfter;

    } elseif ( is_author() ) {
      global $author;
      $userdata = get_userdata($author);
      echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;

    } elseif ( is_404() ) {
      echo $currentBefore . 'Error 404' . $currentAfter;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

	if ($catalog) echo $catalog;

    echo '</div>';

  }
}


// SHOP FUNCTIONS

if ($_POST['gobuy']) { unset($_SESSION["ORDER"]); }

if ($_POST['buy']){
	$buy=$_POST['buy'];
	$color=$_POST['color'];

	if (count($_SESSION["ORDER"])==0) {
		//session_register("ORDER");
		$_SESSION["ORDER"][0][0] = $buy;
		$_SESSION["ORDER"][0][1] = $color;
		//print '?';
	}
	else{
		$z=count($_SESSION["ORDER"]);
		$_SESSION["ORDER"][$z][0]=$buy;
		$_SESSION["ORDER"][$z][1]=$color;

	}
}
if (isset($_GET['remove'])){
	$remove=$_GET['remove'];
	array_splice ($_SESSION["ORDER"], $remove, 1);
}

function occurrence ($ip='', $to = 'utf-8'){
	$ip = ($ip) ? $ip : $_SERVER['REMOTE_ADDR'] ;
	$xml =  simplexml_load_file('http://ipgeobase.ru:7020/geo?ip='.$ip);
	if($xml->ip->message){
		if( $to == 'utf-8' ) {
			return $xml->ip->message;
		} 
		else {
			if( function_exists( 'iconv' ) ) return iconv( "UTF-8", $to . "//IGNORE",$xml->ip->message);else return "The library iconv is not supported by your server";
		}
	} 
	else { 
		if( $to == 'utf-8' ) {return $xml->ip->region;} else {if( function_exists( 'iconv' ) ) return iconv( "UTF-8", $to . "//IGNORE",$xml->ip->region);
		else return "The library iconv is not supported by your server";}
	}
}


function order(){

	$order='<h1>Ваш заказ</h1>';

	if ($_POST['gobuy']==1&&count($_POST['id'])>0){

		$username=$_POST['username'].' '.$_POST['username2'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$phone=fphone($phone);
		$adress=$_POST['posta'].' '.$_POST['adress'];//.', Индекс:'..', Этаж:'.$_POST['enage'].', Код домофона:'.$_POST['interphone'];

		$promocode=$_POST['promocode'];

		foreach ($_POST['time'] as $time) {
			$delivery_time.=$time.' ';
		}



		// $adress.='<br/>Время доставки: '.$delivery_time;
		// $adress.='<br/> Способ оплаты:'.$_POST['payment'];		
		
		$comment=$_POST['comment'];
		if ($promocode!='') $comment.='<p>Промокод: '.$promocode.'</p>';
		

		$item_id=$_POST['id'];
		$article=$_POST['article'];
		$item_name=$_POST['name'];
		$count=$_POST['count'];
		$item_discount=$_POST['item_discount'];
		$items_price=$_POST['items_price'];
		$date_time=date("c",strtotime('+3 hours'));
		// print_r($date_time);
		$epc_source=$_COOKIE['epc_source'];
		$epc_term=$_COOKIE['epc_term'];

		$sql="INSERT INTO `shop_users` (`name`,`email`,`phone`,`adress`,`comment`,`status`,`date_time`,`epc_source`,`epc_term`) VALUES ('$username','$email','$phone','$adress','$comment','3','$date_time','$epc_source','$epc_term')";
		// print $sql;
		$result = mysql_query($sql) or die(mysql_error());
		$user_id=mysql_insert_id();
		$user_ip=$_SERVER['REMOTE_ADDR'];
		date_default_timezone_set('Europe/Moscow');
		$date_time = date('d.m.y H:i');
		$email_title='Заказ # '.$user_id.' @ EuroProfCosmetic';
		$email_text='Заказ # <strong>'.$user_id.'</strong> / '.$date_time.'
		<p>
		Имя '.$username.'<br />
		E-mail: '.$email.'<br />
		Телефон: '.$phone.'<br />
		Адрес: '.$adress.'<br />
		Комментарий: '.$comment.'
		</p>
		<p>
		Заказ:<br />
		';

	

		$user_text='<table width="100%" border="1">';
		for ($k=0;$k<count($item_id);$k++){
			if (!$item_discount[$k]) $item_discount[$k]=1;

			$price=$items_price[$k]/$count[$k]/$item_discount[$k];
			if ($item_discount[$k]!=1) $discount=(1-$item_discount[$k])*100;
			else $discount='';

			$sql="INSERT INTO `shop_orders` (`user_id`,`item_id`,`count`,`date_time`,`ip`,`price`,`discount`) VALUES ('$user_id','$item_id[$k]','$count[$k]','$date_time','$user_ip','$price','$discount')";
			$result = mysql_query($sql) or die(mysql_error());
			$email_text.='<a href="http://europrofcosmetic.ru/catalog/products/'.$item_id[$k].'">'.$item_name[$k].'</a> ('.$article[$k].') / '.$count[$k].' шт. / '.$items_price[$k].' р. <br />
			';
			$user_text.='<tr><td>'.$item_name[$k].'</td><td>'.$count[$k].' </td><td>'.$items_price[$k].' р.</td></tr>';
		}

		//$_POST['total_price']+=5;
		//$user_text.='<tr><td>Livraison</td><td></td><td>5 р.</td></tr>';
		$email_text.='Итого: '.$_POST['total_price'].' р.<br>';
		$user_text.='<tr><td colspan="2" align="right" valign="top">Итого:</td><td><strong>'.$_POST['total_price'].' р.</td></tr></table>';
		
		$email_text.='</p><p>Откуда: '.$epc_source.'; Запрос: '.$epc_term.'</p>';
		$email_text.='Регион: '.occurrence($user_ip).' ('.$user_ip.')';
		

		
		$order.='<p>Большое спасибо! Ваш заказ #<strong>'.$user_id.'</strong> принят. Дождитесь нашего звонка (или письма) для подтверждения.</p>';
		// $order.='<p>Заказы принятые с 28 декабря 2014 г. по 11 января 2015 г. будут обработанные и доставлены после 12 января.<br>
		// Спасибо за понимание.<br>
		// Поздравляем вас с Новым годом и Рождеством Христовым!</p>';
		$order.='<p><a href="/catalog.html">Вернуться в каталог &rarr;</a></p>';
		//print getcwd();
		include 'send_email.php';
		unset($_SESSION["ORDER"]);
	}
	else{

		$order_items=get_order_items();

		foreach ($order_items as $j => $item) {
			// print_r($item);
			$filename='';
			foreach ($item['image'] as $id => $image) {

				if ($image['subitem_id']==$item['ID']){
					$filename=$image['filename'];
				}
				if ($filename=='') $filename=$image['filename'];
			}
			$item_list.='<tr>
			<td bgcolor="#FFFFFF">'.($j+1).'</td>
			<td bgcolor="#FFFFFF" width="50"><img src="/products/'.$filename.'_small.jpg" width="50"></td>
			<td><input type="hidden" name="id['.$j.']" value="'.$item['ID'].'"> <input type="hidden" name="name['.$j.']" value="'.$item['name'].'"> <input type="hidden" name="article['.$j.']" value="'.$item['article'].'"><a href="/catalog/products/'.$item['item_ID'].'">'.$item['name'].'</a></td>
			<td><span id="item_price_'.$j.'">'.($item['price']).'</span> р. <input type="hidden" id="item_discount_'.$j.'" name="item_discount['.$j.']" value="'.$item['discount'].'"></td>
			<td width="88">
			<a href="javascript:{}" onclick="ch_count('.$j.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
			<a href="javascript:{}" onclick="ch_count('.$j.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>
			<input type="text" size="2" class="form-control"  onchange="ch_prix('.$j.')" id="count_'.$j.'" name="count['.$j.']" value="'.$item['count'].'" style="width:42px; margin-left: 18px;"></td>
			<td><input type="hidden" value="'.round($item['price']*$item['count']*$item['discount']).'" id="ttl2_'.$j.'" name="items_price['.$j.']"><span id="ttl_'.$j.'">'.round($item['price']*$item['count']*$item['discount']).'</span> р.</td>
			<td><a href="?remove='.$item['key'].'"><span class="glyphicon glyphicon-remove"></span></a></td>
			</tr>';
			$total+=round($item['price']*$item['count']*$item['discount']);
		}
			$delivery=0;
			$total+=$delivery;
			$jx=$j+1;
			$my_order='<form id="form1" class="form-horizontal" role="form" name="form1" method="post" action="#send" onsubmit="return Validate(1);">
			<input type="hidden" id="total_items" value="'.$jx.'">
			<table  class="table table-condensed">';
			$my_order.=$item_list;
			//$my_order.='<tr><td>-</td><td><a href="#livraison">Livraison</a></td><td></td><td></td><td>'.$delivery.' р.</td><td></td></tr>';
			$my_order.='<tr><td colspan="4"></td><td align="right">Итого:</td><td><strong><input type="hidden" name="total_price" id="total_price" value="'.$total.'"><span id="total_prix">'.$total.'</span> р.</strong></td><td></td></tr>';
			$my_order.='</table>';


		$order.=$my_order;
		$dsp='display:none;';
		if ($_SESSION['promocode']=='EPC20'||$_SESSION['promocode']=='ЕРС20'||$_SESSION['promocode']=='epc20'||$_SESSION['promocode']=='ерс20') $dsp='';
		$order.='<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
			    <label for="inputName_1" class="col-md-3 control-label">Промокод:</label>
			    <div class="col-md-5">
			      	<input type="text" class="form-control" id="promocode" name="promocode" placeholder="Промокод (если есть)" value="'.$_SESSION['promocode'].'">
			    </div>
			    <div id="promotext" style="'.$dsp.'">Ваша скидка: 20% на косметику.</div>

			</div>

		</div>
		</div>';
		$order.='<h3>Ваши данные: </h3>';
		$order.='
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
			    	<label for="inputName_1" class="col-md-3 control-label">Имя*:</label>
			    	<div class="col-md-9">
			      		<input type="text" class="form-control" id="inputName_1" name="username" placeholder="Введите имя">
			    	</div>
				</div>
				<div class="form-group">
			    	<label for="inputPhone" class="col-md-3 control-label">Телефон*:</label>
			    	<div class="col-md-9">
			      		<!--<input type="text" class="form-control" id="inputPhone" name="phone" placeholder="9">-->
			      		<div style="display:inline-block;">+7</div> <input type="text" style="display:inline-block; width:95%;" class="form-control" id="inputPhone" name="phone" placeholder="9275556677">
			    	</div>
				</div>
				<div class="form-group">
			    	<label for="inputContact_1" class="col-md-3 control-label">E-mail*:</label>
			    	<div class="col-md-9">
			      		<input type="email" class="form-control" id="inputContact_1" name="email" placeholder="name@yandex.ru">
			    	</div>
				</div>
				<div class="form-group">
			    	<label for="inputAdress" class="col-md-3 control-label">Адрес доставки*:</label>
			    	<div class="col-md-9">
			    		<div class="row">
			    			<div class="col-md-3">
			    				<input type="text" class="form-control" name="posta" value="" placeholder="125375" >
			    			</div>
			    			<div class="col-md-9">
			    				<input type="text" class="form-control" id="inputAdress" name="adress" placeholder="Москва, Тверская ул., д.45, кв. 47">
			    			</div>
			    		</div>			      		
			    	</div>
				</div>
				<div class="row">
			<div class="col-md-offset-3 col-md-9">
			
		<input type="hidden" name="gobuy" value="1">
		<input type="submit" class="btn btn-lg btn-success" value="Отправить заказ" />
		<p style="margin-top:20px;">* - поля, обязательные для заполнения</p>
			</div>
		</div>
			</div>
			<div class="col-md-5">
				<p><strong>Комментарий:</strong></p>
			    		
			    		<textarea class="form-control" name="comment" id="comment" rows="3"></textarea>
			</div>
		</div>

			</div>
		</form>
		
		';

	}
	return $order;
}

function get_order_items(){
	$j=0;
	
	 // print_r($_SESSION);
	foreach ($_SESSION["ORDER"] as $key => $value) {
		if ($value[0]>0&&$value[1]>0&&$value[2]>0){	
			$item=get_item($value[0]);
			// print_r($item);
			// $j++;
			$j=$key;
			if ($item['subitem'][$value[2]]['value2']>0) $price=$item['subitem'][$value[2]]['value2'];
			else $price=$item['subitem'][$value[2]]['value1'];
			// if ($value[1]<1) $value[1]=1;
			// print $price;
			// print_r($item);
			
			// $order_item[$j]['ID']=$value[0]; // !!!!!!
			$order_item[$j]['ID']=$value[2]; // !!!!!!
			$order_item[$j]['item_ID']=$value[0]; // !!!!!!

			$order_item[$j]['name']=$item['brand_name'].' - '.$item['name'];
			if ($item['subitem'][$value[2]]['name']!='') $order_item[$j]['name'].=', '.$item['subitem'][$value[2]]['name'];
			$order_item[$j]['article']=$item['subitem'][$value[2]]['value3'];
			$order_item[$j]['count']=$value[1];
			if ($value[3]) $order_item[$j]['discount']=$value[3];
			else $order_item[$j]['discount']=1;
			$order_item[$j]['price']=$price;
			$order_item[$j]['key']=$key;
			$order_item[$j]['image']=$item['image'];
		}
		else {
			unset ($_SESSION["ORDER"][$key]);
		}

	}
	return $order_item;
}

function cats_view($view=0,$tb=0){

	global $URL_PARTS;
	$slugg=$URL_PARTS[1];

	if ($tb==2){
		$sql="SELECT * FROM `shop_params` WHERE ID IN (SELECT brand FROM `shop_catalog` WHERE type='$view');";
	}
	else if ($tb==1){
		$sql="SELECT * FROM `shop_params` WHERE ID IN (SELECT type FROM `shop_catalog` WHERE brand='$view');";
	}

		//$sql="select * from `shop_params` where type=0";
		$result = mysql_query($sql) or die(mysql_error());

		while ($row=mysql_fetch_array($result)) {

			//$param[$row['ID']][0]=$row['name'];
			//$param[$row['ID']][1]=$row['slug'];

			//$img_cat_ID='CAT'.$row['ID'];

			$sql2="select * from `shop_img` where `color`='$img_cat_ID' ";
			$result2 = mysql_query($sql2) or die(mysql_error());
			$row2=mysql_fetch_array($result2);

			if ($tb==2) $slug = $slugg.'/'.$row['slug'];
			else if ($tb==1) $slug = $row['slug'].'/'.$slugg;

			$cats.='<div class="category">';
				//$cats.= '<div class="img"><a href="/shop/'.$row['slug'].'"><img src="'.get_option('home').'/products/'.$row2['filename'].'_.jpg" border="0" /></a></div>';
				$cats.= '<div class="info">';
					$cats.= '<div class="name" align="center"><a href="/catalog_'.$slug.'.html"><img src="/logos/'.$row['logo'].'" border="0"></a><br /><a href="/catalog_'.$slug.'.html">'.$row['name'].'</a></div>';// <img src="'.$img_sex.'" border=0></div>';
				$cats.='</div>';
			$cats.='</div>';
		}


	return $cats;
}

function get_special_products($special){
	if ($special!=55) $sql="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.slug, shop_params.name, shop_params.slug, shop_params.type FROM shop_catalog, shop_params WHERE shop_catalog.special=$special AND (shop_params.ID=shop_catalog.type OR shop_params.ID=shop_catalog.brand)";
	else $sql="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.slug, shop_params.name, shop_params.slug, shop_params.type FROM shop_catalog, shop_params WHERE shop_catalog.index=1 AND (shop_params.ID=shop_catalog.type OR shop_params.ID=shop_catalog.brand)";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$items[$row['ID']]['name']=$row[1];
		$items[$row['ID']]['slug']=$row[2];				
		// $items[$row['ID']]['image']=$row[3];
		if ($row[6]==2){
			$items[$row['ID']]['brand_name']=$row[4];
			$items[$row['ID']]['brand_slug']=$row[5];			
		}
		elseif ($row[6]==0){
			$items[$row['ID']]['type_name']=$row[4];
			$items[$row['ID']]['type_slug']=$row[5];			
		}
	}

	foreach ($items as $id => $value) {
		if ($sql_img_ids) $sql_img_ids.=' OR item_id='.$id;
		else $sql_img_ids='item_id='.$id;

	}

	if ($sql_img_ids) {
		$sql="SELECT * FROM shop_img WHERE $sql_img_ids";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			if ($items[$row['item_id']]['image'][$row['subitem_id']]==''||$row['main']==1) $items[$row['item_id']]['image'][$row['subitem_id']]=$row['filename'];			
		}
	
		$sql="SELECT * FROM shop_subitem WHERE ($sql_img_ids) AND instock!=2";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			$items[$row['item_id']]['subitem'][$row['ID']]['value']=$row['name'];
			$items[$row['item_id']]['subitem'][$row['ID']]['price']=$row['value1'];
			$items[$row['item_id']]['subitem'][$row['ID']]['special_price']=$row['value2'];
		}
	}
	return $items;
}


function get_search_result($word){

	$sql="SELECT item_id FROM shop_subitem WHERE value3 LIKE '%$word%' AND instock!=2";
	$result = mysql_query($sql) or die(mysql_error());
	$sql_artilce='';
	while ($row=mysql_fetch_array($result)) {
		$sql_artilce.=' OR shop_catalog.ID='.$row['item_id'];
	}
	// print $sql_artilce;
	$sql="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.desc, shop_catalog.slug, shop_catalog.price, shop_params.name, shop_params.slug, shop_catalog.exp, shop_params.type, shop_catalog.list FROM shop_catalog, shop_params WHERE ((shop_catalog.name LIKE '%$word%') $sql_artilce OR (shop_params.name LIKE '%$word%')) AND ((shop_params.ID=shop_catalog.type) OR (shop_params.ID=shop_catalog.brand)) AND (shop_catalog.archive!=1)";

//	$sql=""
	//print $sql;

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$items[$row['ID']]['name']=$row[1];
		$items[$row['ID']]['desc']=$row[2];
		$items[$row['ID']]['list']=$row[9];
		$items[$row['ID']]['slug']=$row[3];
		// $items[$row['ID']]['count']=$row[8];
		$items[$row['ID']]['exp']=$row[7];
		$items[$row['ID']]['price']=$row[4];
		$items[$row['ID']]['image']='';
		if ($row[8]==2){
			$items[$row['ID']]['brand_name']=$row[5];
			$items[$row['ID']]['brand_slug']=$row[6];
		}
		else{
			$items[$row['ID']]['type_name']=$row[5];
			$items[$row['ID']]['type_slug']=$row[6];
		}
	}

	foreach ($items as $id => $value) {
		if ($sql_img_ids) $sql_img_ids.=' OR item_id='.$id;
		else $sql_img_ids='item_id='.$id;

	}

	// if ($sql_img_ids){
	// 	$sql="SELECT * FROM shop_img WHERE $sql_img_ids";
	// 	$result = mysql_query($sql) or die(mysql_error());
	// 	while ($row=mysql_fetch_array($result)) {
	// 		$items[$row['item_id']]['image']=$row['filename'];
	// 	}
	// }

	if ($sql_img_ids) {
		$sql="SELECT * FROM shop_img WHERE $sql_img_ids";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			$items[$row['item_id']]['image'][$row['subitem_id']]=$row['filename'];			
		}
		$sql="SELECT * FROM shop_item_param WHERE $sql_img_ids";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			if ($row['item_param_id']!=0) $items[$row['item_id']]['item_param'][$row['item_param_id']]=$row['value'];
		}
		$sql="SELECT * FROM shop_subitem WHERE ($sql_img_ids) AND instock!=2";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			$items[$row['item_id']]['subitem'][$row['ID']]['value']=$row['name'];
			$items[$row['item_id']]['subitem'][$row['ID']]['price']=$row['value1'];
			$items[$row['item_id']]['subitem'][$row['ID']]['special_price']=$row['value2'];		
		}
	}
	return $items;
}

function get_category_subs($struct,$parent,$type=0){
	foreach ($struct as $id => $value) {		
		if ($value['parent']==$parent) {
			if ($type==0) {
				$sll[0].=' OR shop_catalog.type='.$id;
				$sll[1].=' OR shop_params_links.param_id='.$id;
			}
			elseif ($type==2){
				$sll[0].=' OR shop_catalog.brand='.$id;				
			}
			$sllx=get_category_subs($struct,$id,$type);
			$sll[0].=$sllx[0];
			$sll[1].=$sllx[1];

		}
	}
	return $sll;
}

function get_category_products($cat_id,$type=0,$fltr){	

	$sql="SELECT shop_params.ID, shop_params.name, shop_params.slug, shop_params.type, shop_params.parent, shop_params.logo FROM shop_params";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}	

	foreach ($params as $ID => $value) {
		if ($value['type']==$type) {
			$struct[$ID]['ID']=$value['ID'];
			$struct[$ID]['parent']=$value['parent'];
		}
	}

	// $sql="SELECT ID, parent FROM shop_params WHERE type=$type";
	// $result = mysql_query($sql) or die(mysql_error());
	// while ($row=mysql_fetch_array($result)) {
	// 	$struct[$row['ID']]=$row;		
	// }

	$sllx=get_category_subs($struct,$cat_id,$type);
	$sll=$sllx[0];
	// $shop_params_links="OR (shop_catalog.ID=shop_params_links.item_id AND (shop_params_links.param_id=$cat_id".$sllx[1].'))';
	$shop_params_links="SELECT item_id FROM shop_params_links WHERE shop_params_links.param_id=$cat_id";
	$items_ids='';
	$result = mysql_query($shop_params_links) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$items_ids.=' OR shop_catalog.ID='.$row['item_id'];
	}


	$sql1="SELECT shop_catalog.ID, shop_catalog.name, shop_catalog.desc, shop_catalog.slug, shop_catalog.price, shop_catalog.special_price, shop_catalog.article, shop_catalog.count, shop_catalog.archive, shop_catalog.exp, shop_catalog.type, shop_catalog.brand, shop_catalog.list, shop_catalog.special";

	if ($type==0){
		$sql=$sql1." FROM shop_catalog WHERE ((shop_catalog.type=$cat_id $sll) $items_ids) AND shop_catalog.archive=0";
		// if ($fltr){
		// 	$sql=$sql1." FROM shop_catalog, shop_params, shop_item_param WHERE shop_catalog.type=$cat_id AND shop_params.ID=shop_catalog.brand AND shop_catalog.archive=0";
		// 	if ($fltr['price']){
		// 		$X1=$fltr['price'];
		// 		$X2=$X1+20;
		// 		$sql_filter.=" AND shop_catalog.price<=".$X2.' AND shop_catalog.price>='.$X1;				
		// 	}
		// 	// if (count($fltr['brand'])>0){
		// 	// 	$sql_filter.=" AND (";
		// 	// 	$a=0;
		// 	// 	foreach ($fltr['brand'] as $brand){
		// 	// 		if (!$a) { $sql_filter.=" shop_params.ID=$brand"; $a=1;}
		// 	// 		else  $sql_filter.=" OR shop_params.ID=$brand";
		// 	// 	}
		// 	// 	$sql_filter.=")";
		// 	// }
		// 	if (count($fltr['params'])>0){
		// 		//$sql_filter.=" AND(";
		// 		$b=0;
		// 		$sql_flt="";
		// 		foreach ($fltr['params'] as $param_id => $param_value){
		// 			if ($param_value!=''){
		// 				if ($sql_flt!='') $sql_flt.=" OR (shop_item_param.value = '$param_value' AND shop_item_param.item_param_id=$param_id)";
		// 				else $sql_flt.="(shop_item_param.value = '$param_value' AND shop_item_param.item_param_id=$param_id)";
		// 				$itm_ids[$param_id]= array();
		// 			}
		// 			// if ($b==1) $sql_filter.=" AND (shop_catalog.ID IN (SELECT item_id FROM shop_item_param WHERE";
		// 			// else  $sql_filter.=" (shop_catalog.ID IN (SELECT item_id FROM shop_item_param WHERE";
		// 			// $a=0;
		// 			// foreach ($param_value_ids as $param_value_id) {
		// 			// 	if (!$a) { $sql_filter.=" value=$param_value_id"; $a=1; }
		// 			// 	else { $sql_filter.=" OR value=$param_value_id";  }
		// 			// }
		// 			// $sql_filter.="))";
		// 			// $b=1;
		// 		}

		// 		if ($sql_flt!=''){
		// 			$sqls="SELECT item_id,item_param_id FROM shop_item_param WHERE ".$sql_flt;
		// 			//print $sqls;
		// 			$results = mysql_query($sqls) or die(mysql_error());				
		// 			while ($rows=mysql_fetch_array($results)) {					
		// 				$itm_ids[$rows['item_param_id']][]=$rows['item_id'];
		// 			}
		// 		}
		// 		$common_elements = array();
		// 		$first=array_shift($itm_ids);
		// 		//print_r($itm_ids);
		// 		if (count($itm_ids)>0){
		// 			foreach ($first as $value) {
		// 				$element = $value;
		// 				foreach ($itm_ids as $value2) {
		// 					if(!in_array($value,$value2)){
		// 						$element = false;
		// 					}						
		// 				}
		// 				if($element) $common_elements[] = $element;
		// 			}
		// 		}
		// 		else{
		// 			$common_elements=$first;
		// 		}
		// 		$sql_items='';
		// 		foreach ($common_elements as $value) {
		// 			if ($sql_items!='') $sql_items.=' OR ';
		// 			$sql_items.='shop_catalog.ID='.$value;
		// 		}
		// 		if ($sql_items!='') $sql_items=' AND ('.$sql_items.')';
		// 		//$sql_filter.=" OR shop_catalog.ID IN ()";
		// 	}
		// 	// if (count($fltr['intrvl'])>0){
		// 	// 	$b=0;
		// 	// 	foreach ($fltr['intrvl'] as $param_id => $param_value_ids){
		// 	// 		if ($param_value_ids['from']!=0) { $from.=" value>=".$param_value_ids['from']; }
		// 	// 		if ($param_value_ids['to']!=0) { $to.=" value<=".$param_value_ids['to']; }
		// 	// 		if ($from!=''&& $to!='') $sql_intrvl.=$from.' AND'.$to;
		// 	// 		else $sql_intrvl.=$from.$to;
		// 	// 		if ($sql_intrvl!=''){
		// 	// 			if ($b==1) $sql_filter_intrvl.=" AND (shop_catalog.ID IN (SELECT item_id FROM shop_item_param WHERE";
		// 	// 			else  $sql_filter_intrvl.=" (shop_catalog.ID IN (SELECT item_id FROM shop_item_param WHERE";
		// 	// 			$sql_filter_intrvl.=$sql_intrvl." AND item_param_id=$param_id))";
		// 	// 			$b=1;
		// 	// 		}
		// 	// 	}
		// 	// 	if ($sql_filter_intrvl!='') $sql_filter_intrvl=" AND(".$sql_filter_intrvl.")";
		// 	// }
		// 	//print $sql_filter_intrvl;
		// 	//if ($sql_filter_intrvl!='') $sql_filter.=$sql_filter_intrvl;
		// 	$sql.=$sql_filter.$sql_items;			
		// }
	}
	else {

		$sql=$sql1." FROM shop_catalog WHERE ((shop_catalog.brand=$cat_id $sll) $items_ids) AND shop_catalog.archive=0";
		// print $sql;
	}
	if ($type==0) $sql.=' ORDER BY shop_catalog.brand ASC';
	elseif ($type==2) $sql.=' ORDER BY shop_catalog.type ASC';
	// print $sql;
	// "shop_catalog.ID, shop_catalog.name, shop_catalog.desc, shop_catalog.slug, shop_catalog.price, shop_catalog.special_price, shop_catalog.article, shop_catalog.count, shop_catalog.archive, shop_catalog.exp, shop_catalog.type, shop_catalog.brand";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$items[$row[0]]['name']=str_replace('"', '&quot;', $row[1]);
		$items[$row[0]]['desc']=$row[2];
		$items[$row[0]]['list']=$row[12];
		$items[$row[0]]['special']=$row[13];
		$items[$row[0]]['slug']=$row[3];
		$items[$row[0]]['price']=$row[4];

		$items[$row[0]]['special_price']=$row[5];
		$items[$row[0]]['article']=$row[6];
		$items[$row[0]]['count']=$row[7];
		$items[$row[0]]['archive']=$row[8];
		$items[$row[0]]['exp']=$row[9];

		if ($params[$row[11]]['parent']!=0) {
			$brand=get_parentest($params[$row[11]]['parent'],$params);

			$items[$row[0]]['subbrand']['name']=$params[$row[11]]['name'];
			$items[$row[0]]['subbrand']['slug']=$params[$row[11]]['slug'];
		}
		else $brand=$params[$row[11]];
		
		$items[$row[0]]['brand_id']=$brand['ID'];
		$items[$row[0]]['brand_name']=$brand['name'];
		$items[$row[0]]['brand_slug']=$brand['slug'];
		$items[$row[0]]['brand_logo']=$brand['logo'];		

		$items[$row[0]]['type_id']=$params[$row[10]]['ID'];
		$items[$row[0]]['type_name']=$params[$row[10]]['name'];
		$items[$row[0]]['type_slug']=$params[$row[10]]['slug'];
		$items[$row[0]]['type_logo']=$params[$row[10]]['logo'];		
		$items[$row[0]]['type_parent']=$params[$row[10]]['parent'];

		// if ($type==0){
		// 	$items[$row[0]]['brand_name']=$pa
		// 	$items[$row[0]]['brand_slug']=$row[6];
		// 	$items[$row[0]]['brand_id']=$row[12];
		// 	$items[$row[0]]['brand_logo']=$row[13];
		// }
		// else{
		// 	$items[$row[0]]['type_name']=$row[5];
		// 	$items[$row[0]]['type_slug']=$row[6];
		// 	$items[$row[0]]['type_id']=$row[12];
		// 	$items[$row[0]]['type_logo']=$row[13];
		// }
	}
	foreach ($items as $id => $value) {
		if ($sql_img_ids) $sql_img_ids.=' OR item_id='.$id;
		else $sql_img_ids='item_id='.$id;
	}
	if ($sql_img_ids) {
		$sql="SELECT * FROM shop_img WHERE $sql_img_ids";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			$items[$row['item_id']]['image'][$row['subitem_id']]=$row['filename'];			
		}
		$sql="SELECT * FROM shop_item_param WHERE $sql_img_ids";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			if ($row['item_param_id']!=0) $items[$row['item_id']]['item_param'][$row['item_param_id']]=$row['value'];
		}
		$sql="SELECT * FROM shop_subitem WHERE ($sql_img_ids) AND instock!=2";
		$result = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($result)) {
			$items[$row['item_id']]['subitem'][$row['ID']]['value']=$row['name'];
			$items[$row['item_id']]['subitem'][$row['ID']]['price']=$row['value1'];
			$items[$row['item_id']]['subitem'][$row['ID']]['special_price']=$row['value2'];
			$items[$row['item_id']]['subitem'][$row['ID']]['value3']=$row['value3'];
		}
	}
	return $items;
}


function get_parentest($parent, $array){	
	foreach ($array as $id => $param) {		
		if ($id==$parent) {
			if ($param['parent']==0) {

				return $param;
			}
			else return get_parentest ($param['parent'],$array);
		}
	}	
}


function get_item($item_id){

	$sql="SELECT shop_params.ID, shop_params.name, shop_params.slug, shop_params.type, shop_params.parent, shop_params.logo, shop_params.country, shop_params.text FROM shop_params";
	$result = mysql_query($sql) or die(mysql_error());	
	while ($row=mysql_fetch_array($result)) {
		$params[$row['ID']]=$row;
	}

	// $sql="SELECT shop_catalog.name,shop_catalog.desc, shop_catalog.slug, shop_catalog.price, shop_params.ID, shop_params.name, shop_params.slug, shop_params.type, shop_catalog.special_price, shop_catalog.article, shop_catalog.count, shop_catalog.archive, shop_params.logo, shop_params.country, shop_params.text, shop_catalog.seo_title, shop_catalog.seo_text, shop_params.parent FROM shop_catalog, shop_params WHERE shop_catalog.ID=$item_id AND (shop_params.ID=shop_catalog.type OR shop_params.ID=shop_catalog.brand)";
	$sql="SELECT shop_catalog.name, shop_catalog.desc, shop_catalog.slug, shop_catalog.price, shop_catalog.special_price, shop_catalog.article, shop_catalog.count, shop_catalog.archive, shop_catalog.seo_title, shop_catalog.seo_text, shop_catalog.type, shop_catalog.brand, shop_catalog.unq, shop_catalog.list FROM shop_catalog WHERE shop_catalog.ID=$item_id";

	$result = mysql_query($sql) or die(mysql_error());
	$item['ID']=$item_id;

	while ($row=mysql_fetch_array($result)) {
		$item['name']=str_replace('"', '&quot;', $row[0]);
		$item['desc']=$row[1];
		$item['list']=$row[13];
		$item['slug']=$row[2];
		$item['price']=$row[3];

		$item['special_price']=$row[4];
		$item['article']=$row[5];
		$item['count']=$row[6];
		$item['archive']=$row[7];

		$item['seo_title']=$row[8];
		$item['seo_text']=$row[9];
		$item['unq']=$row[12];

		$item['image']='';

		if ($params[$row[11]]['parent']!=0) {
			$brand=get_parentest($params[$row[11]]['parent'],$params);

			$item['subbrand']['name']=$params[$row[11]]['name'];
			$item['subbrand']['slug']=$params[$row[11]]['slug'];
		}
		else $brand=$params[$row[11]];
		
		$item['brand_id']=$brand['ID'];
		$item['brand_name']=$brand['name'];
		$item['brand_slug']=$brand['slug'];
		$item['brand_logo']=$brand['logo'];
		$item['brand_country']=$brand['country'];
		$item['brand_text']=$brand['text'];


		$item['type_id']=$params[$row[10]]['ID'];
		$item['type_name']=$params[$row[10]]['name'];
		$item['type_slug']=$params[$row[10]]['slug'];
		$item['type_parent']=$params[$row[10]]['parent'];
		 
	}

	$sql="SELECT * FROM shop_img WHERE item_id=$item_id";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$images[$row['ID']]=$row;
	}

	if (count($images)==0) {
		$item['image'][0]['ID']=0;
		$item['image'][0]['item_id']=$item_id;
		$item['image'][0]['filename']='bouteille';
	}
	else $item['image']=$images;

	$sql="SELECT * FROM shop_files WHERE item_id=$item_id";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$files[$row['ID']]=$row;
	}
	$item['files']=$files;

	$param_id=$item['type_id'];

	$sql="SELECT * FROM shop_item_param WHERE (item_param_id=$param_id OR item_param_id=0) AND item_id=0 ORDER BY pos ASC";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row=mysql_fetch_array($result)) {

		if ($row['item_id']==0){
			$param[$row['ID']]['name']=$row['value'];
			$param[$row['ID']]['value']=get_right_param_value($row['ID'],$item_id);
		}
		else {
			//$param[$row['item_param_id']]['value']=$row['value'];
		}
	}

	$item['params']=$param;

	$sql="SELECT * FROM shop_item_addon_param WHERE item_id=$item_id";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$addon_param[$row['ID']]=$row;
	}
	$item['addon_params']=$addon_param;

	$sql="SELECT * FROM shop_subitem WHERE item_id=$item_id AND instock!=2";

	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$subitem[$row['ID']]=$row;
	}
	$item['subitem']=$subitem;


	

	return $item;
}

function get_right_param_value($item_param_id,$item_id){
	$sql="SELECT value FROM shop_item_param WHERE item_param_id=$item_param_id AND item_id=$item_id";

	$result = mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$value=$row[0];
	/*
	if ($value) {
		$sql="SELECT value FROM shop_item_param WHERE ID=$value";
		print $sql;
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_array($result);
		if ($row[0]) $value=$row[0];
	}
	*/
	return $value;
}

function item_view($view=0,$type=0,$brand=0,$sex=0,$special=0){

	$sql="select * from `shop_params`";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$param[$row['ID']][0]=$row['name'];
		$param[$row['ID']][1]=$row['slug'];
	}


	if ($view==0){
		if ($type||$brand||$sex||$special){
			$filter='WHERE ';
			if ($type) $filtered.='type='.$type.' ';
			if ($brand&&$filtered) $filtered.='AND brand='.$brand.' ';
			elseif($brand&&!$filtered) $filtered.='brand='.$brand.' ';
			if ($sex&&$filtered) $filtered.='AND sex='.$sex.' ';
			elseif($sex&&!$filtered) $filtered.='sex='.$sex.' ';
			if ($special==1) $sp_filter='OR special=3 ';
			if ($special&&$filtered) $filtered.='AND special='.$special.' '.$sp_filter;
			elseif($special&&!$filtered) $filtered.='special='.$special.' '.$sp_filter;
		}

		$sql="select * from `shop_catalog` ".$filter.$filtered."ORDER BY name ASC";

	}
	else {
		global $URL_PARTS;
		$id=$URL_PARTS[2];
		$sql="select * from `shop_catalog` WHERE `ID`='$id' LIMIT 1";
	}

	if ($_POST['is_search']){
		$word=$_POST['word'];
		$sql="SELECT * FROM `shop_catalog` WHERE `name` LIKE '%$word%' OR `desc` LIKE '%$word%'";

		if ($_POST['is_search']==2) {
			//$TYPE=$_POST['TYPE'];
			//$BRAND=$_POST['BRAND'];
			$model=$_POST['model'];
			$device=$_POST['device'];
			if ($model&&$device){
				$sql="SELECT * FROM `shop_catalog` WHERE (`name` LIKE '%$model%' OR `desc` LIKE '%$model%' AND `type`=69) OR `ID` IN (SELECT item_id FROM `shop_item_param` WHERE `value` LIKE '%$device%')";
			}
			else if ($model&&!$device){
				$sql="SELECT * FROM `shop_catalog` WHERE `name` LIKE '%$model%' OR `desc` LIKE '%$model%' AND `type`=69";
			}
			else if (!$model&&$device){
				$sql="SELECT * FROM `shop_catalog` WHERE `type`=69 AND `ID` IN (SELECT item_id FROM `shop_item_param` WHERE `value` LIKE '%$device%')";
			}
		}
	}

	$result = mysql_query($sql) or die(mysql_error());
	$item='';

	if ($type){
		$get_sql="select * from `shop_item_param` WHERE item_param_id=$type AND item_id=0 ORDER BY pos ASC";
		$get_result = mysql_query($get_sql) or die(mysql_error());
		$i=0;
		while ($get_row=mysql_fetch_array($get_result)) {
			$type_param[$i]['ID']=$get_row['ID'];
			$type_param[$i]['value']=$get_row['value'];
			$i++;
		}
	}


	while ($row=mysql_fetch_array($result)) {


		// IMGs
		$item_id=$row['ID'];

		$imgs=mysql_query("select filename, color, ID from `shop_img` WHERE `item_id`='$item_id' ORDER BY ID ASC");
		$i=0;
		$img[0]['filename']='no_photo';
		while ($imgg=mysql_fetch_array($imgs)) {
			$img[$i]['filename']=$imgg['filename'];
			$img[$i]['color']=$imgg['color'];
			$img[$i]['id']=$imgg['ID'];
			if ($_GET['color']&&($_GET['color']==$imgg['ID'])) $cur_color=$i;
			$i++;
		}
		//
		if ($type!=69){
		if ($view==0) $item.='<div id="'.$row['ID'].'" class="product">';
		else $item='<div id="'.$row['ID'].'">';
		}


//		$real_price= round($row['price']*44,-2); // CALCULATING PRICE
		$real_price=$row['price'];
		if ($row['special']==3) {
			$old_price=$real_price;
			$real_price=round($row['price']*0.70,-1);
		}


	//	if ($param[$row['sex']][1]=='womens') $img_sex='/products/logos/womens.png';
	//	if ($param[$row['sex']][1]=='mens') $img_sex='/products/logos/mens.png';
				if ($view==1){

					$item.='<table border="0"><tr>';
					$item.='<td valign="top" class="full_item" width="400">';

						$item.= '<div class="brand"><a href="/catalog_'.$param[$row['brand']][1].'.html">'.$param[$row['brand']][0].'</a></div>';
						$item.= '<div class="name">'.$row['name'].'</div>';

						/*
						if ($row['special']==5) $item.= '<div class="price"><strong>Поступления товара ожидается.</strong></div>';
						else if ($row['special']==3) $item.= '<div class="price">Цена: '.$real_price.' руб.<br /><small>Старая цена: '.$old_price.' руб.</small></div>';
						else $item.= '<div class="price">Цена: '.$real_price.' руб.</div>';
						*/

						if ($_GET['buy']){
							$item.='<div class="buyyet">Товар добавлен в корзину! <br />
Вы можете сразу перейти к <a href="/catalog/order">Оформлению заказа &rarr;</a> или сделать это позже через ссылку в правом верхнем углу страницы.</div>';
						}
						else{
							if (!$_GET['color']) $color=$img[0]['id'];
							else $color=$_GET['color'];

							//if ($row['special']==5) $item.= '<div class="buynow"><a href="?buy='.$row['ID'].'&color='.$color.'">Предзаказ</a></div>';
							//else $item.= '<div class="buynow"><a href="?buy='.$row['ID'].'&color='.$color.'">Купить!</a></div>';
						}

						$item.= '<div class="size">'.$row['size'].'</div>';
						$description=str_replace('
','<br />',$row['desc']);

						$item.='<div class="desc">';

						$sql2="select * from `shop_item_param` WHERE item_id=$item_id ORDER BY ID DESC";

						$result2 = mysql_query($sql2) or die(mysql_error());

						while ($row2=mysql_fetch_array($result2)) {
							$item_param_values[$row2['item_param_id']]=$row2['value'];
						}
///////////////////////
						$type_select=$row['type'];
						$sql3="select * from `shop_item_param` WHERE item_param_id=$type_select AND item_id=0 ORDER BY pos ASC";
						$result3 = mysql_query($sql3) or die(mysql_error());

						while ($row3=mysql_fetch_array($result3)) {
							if ($item_param_values[$row3['ID']]) $item_params.='<p><strong>'.$row3['value'].':</strong> '.$item_param_values[$row3['ID']].'</p>';
						}

						$item.=$item_params;

						if ($description) $item.= '<p>'.$description.'</p>';
					$item.='</div></td>';



					$item.='<td valign="top" >';

				if ($img[0]['filename']=='no_photo') $img[0]['filename']='';
				if ($img[$cur_color]['filename']!=''||$img[0]['filename']!=''){
					if ($_GET['color']>0){
						$bigfile=get_option('home').'/products/'.$img[$cur_color]['filename'].'.jpg';
						if (!is_file($bigfile)) $bigfile=get_option('home').'/products/'.$img[$cur_color]['filename'].'_medium.jpg';
						$item.= '<div id="big_img"><a href="'.$bigfile.'" target="_blank"><img src="'.get_option('home').'/products/'.$img[$cur_color]['filename'].'_medium.jpg" border="0" width="230"/></a></div><div id="color_name"></div>';
					}
					else{
						$bigfile=get_option('home').'/products/'.$img[0]['filename'].'.jpg';
						if (!is_file($bigfile)) $bigfile=get_option('home').'/products/'.$img[0]['filename'].'_medium.jpg';
						$item.= '<div id="big_img"><a href="'.$bigfile.'" target="_blank"><img src="'.get_option('home').'/products/'.$img[0]['filename'].'_medium.jpg" border="0" width="230"/></a></div><div id="color_name"></div>';
					}
				}

					$imgs='';
					for ($j=0;$j<count($img);$j++){
						//$imgs.= '<div id="img_'.$j.'" class="imgs"><a href="?color='.$img[$j]['id'].'"><img src="'.get_option('home').'/products/'.$img[$j]['filename'].'_small.jpg" border="0" width="90" /></a></div>';

						//if ($img[$j]['color']!='') {$imgs.= '<div id="color_'.$j.'" class="color_names">Цвет: '.$img[$j]['color'].'</div>';}
					}
					$item.=$imgs;
					$item.='<br><strong>Задать вопрос по данной модели:</strong>';
					$item.=do_shortcode( '[contact-form 2 "Вопрос по модели"]' );
					$item.='<script>
					var a;
					a=document.getElementById("model");
					a.value="'.$param[$row['brand']][0].' '.$row['name'].'"
					</script>';
					$item.='</td>';

					$item.='</tr></table>';

				}
				else{

					 if ($type==69){
					 	$imgggg='<a href="/catalog/products/'.$row['ID'].'"><img src="'.get_option('home').'/products/'.$img[0]['filename'].'_small.jpg" border="0" width="60" /></a>';
					 }
					 else $item.= '<div class="product-preview"><p><a href="/catalog/products/'.$row['ID'].'"><img src="'.get_option('home').'/products/'.$img[0]['filename'].'_small.jpg" border="0" width="60" /></a></p></div>';

					$item_params='';
					$item_paramss='';
					$sql2="select * from `shop_item_param` WHERE item_id=$item_id ORDER BY ID DESC";
					$result2 = mysql_query($sql2) or die(mysql_error());

					while ($row2=mysql_fetch_array($result2)) {
						$item_params[$row2['item_param_id']]=$row2['value'];
						//if ($row2['value']!='') $item_params.=$row2['value'].', ';
						//if ($row2['value']!='') $item_paramss.='<td>'.$row2['value'].'</td>';
					}
					$item_param_string='';
					$item_param_string_69='';

					for ($j=0;$j<count($type_param);$j++){

						 if ($type_param[$j]['ID']!=753&&$type_param[$j]['ID']!=754&&$type_param[$j]['ID']!=59&&$type_param[$j]['ID']!=58) $item_param_string_69.='<td>'.$item_params[$type_param[$j]['ID']].'</td>'; //
						if ($item_params[$type_param[$j]['ID']]) $item_param_string.=$item_params[$type_param[$j]['ID']].', ';
					}

					if ($type==69){
						$item.='<tr><td>'.$imgggg.'</td><td width="150"><a href="/catalog/products/'.$row['ID'].'">'.$param[$row['brand']][0].' '.$row['name'].'</a> '.$item_param_string_69.'</td></tr>';
					}
					else{
					$item.= '<div class="info">';

						$item.= '<div class="name"><a href="/catalog/products/'.$row['ID'].'">'.$param[$row['brand']][0].' '.$row['name'].'</a> </div>';
						$item.= '<div class="info_text">'.$item_param_string.'</div>';
						//$item.= '<div class="brand"> <a href="/shop/'.$param[$row['type']][1].'/'.$param[$row['brand']][1].'">'.$param[$row['type']][0].' '.$param[$row['brand']][0].'</a></div>';
						//if ($row['special']!=5) $item.= '<div class="price">'.$real_price.' руб.</div>';
					$item.='</div>';
					}
				  }

		if ($type!=69){
			$item.= '</div>

';}

	}

	for ($j=0;$j<count($type_param);$j++){
		if ($type_param[$j]['ID']!=753&&$type_param[$j]['ID']!=754&&$type_param[$j]['ID']!=59&&$type_param[$j]['ID']!=58) $item_param_table_head.='<td>'.$type_param[$j]['value'].'</td>';
	}

	if ($type==69) $item='<table width="100%"><tr class="item_table_head"><td></td><td>Название</td>'.$item_param_table_head.'</tr>'.$item.'</table>';
	return $item;
}

function index_cat($type){
	$name=''; $desc=''; $image=''; $brands=''; $sex='';
$item=
'    <div class="index_block">';

/*
'        <div class="img"><a href="/shop/new"><img src="http://www.onemoreshop.ru/products/Brunotti-Miltas-Mens-Jacket-868_small.jpg" border="0"></a></div>
        <div class="index_info">
            <div class="index_name"><a href="/shop/new">HOT NEW STUFF!</a></div>
            <div class="index_desc">Самые новые и актуальные коллекции 09-10. Для тех кому ничего не жалко ради стиля.</div>
        </div>

';

	$sql="select * from `shop_params`";
	$result = mysql_query($sql) or die(mysql_error());
	while ($row=mysql_fetch_array($result)) {
		$param[$row['ID']][0]=$row['name'];
		$param[$row['ID']][1]=$row['slug'];
	}
*/






	switch ($type) {
		case 1:
			//NEW
			$name='HOT NEW STUFF!';
			break;
		case 2:
			//SALE
			break;
		case 3:
			//Jackets
			break;
		case 4:
			//Pants
			break;
		case 5:
			//Gloves
			break;
		case 6:
			//goggles
			break;
		case 7:
			//Sweatshirts
			break;
		case 8:
			//Other
			break;
	}

	if ($type) $filtered.='type='.$type;
	if ($special) $filtered.='special='.$special;

	$sql="select * from `shop_catalog` WHERE ".$filtered." ORDER BY ID DESC";
	$result = mysql_query($sql) or die(mysql_error());

	while ($row=mysql_fetch_array($result)) {
	}
		// IMGs
		$item_id=$row['ID'];
		$imgs=mysql_query("select filename, color, ID from `shop_img` WHERE `item_id`='$item_id' ORDER BY ID ASC");
		$i=0;
		while ($imgg=mysql_fetch_array($imgs)) {
			$img[$i]['filename']=$imgg['filename'];
			$img[$i]['color']=$imgg['color'];
			$img[$i]['id']=$imgg['ID'];
			if ($_GET['color']&&($_GET['color']==$imgg['ID'])) $cur_color=$i;
			$i++;
		}
		//
	if ($type!=69){
	$item='</div>';}
	return $item;
}

function get_text($param_id){
	$sql="select * from `shop_texts` WHERE `param_id`='$param_id' LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($result);
	$content = '<h1 style="font-size:30px;">'.$row['title'].'</h1>'.$row['text'];
	return $content;
}

//



# WIDGET: Top Sidebar
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Top Sidebar',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
		'before_widget' => '',
        'after_widget' => '',
    ));

# WIDGET: Right Sidebar
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Right Sidebar',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
		'before_widget' => '',
        'after_widget' => '',
    ));

# WIDGET: Footer Sidebar
if ( function_exists('register_sidebar') )
    register_sidebar(array(
		'name' => 'Footer Sidebar',
        'before_title' => '<div class="footer_widget"><h2>',
        'after_title' => '</h2>',
		'before_widget' => '',
        'after_widget' => '</div>',
    ));

# Displays a list of pages
function dp_list_pages() {
	global $wpdb;
	$querystr = "SELECT $wpdb->posts.ID, $wpdb->posts.post_title FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'page' ORDER BY $wpdb->posts.post_title ASC";
	$pageposts = $wpdb->get_results($querystr, OBJECT);
	if ($pageposts) {
		foreach ($pageposts as $post) {
			?><li><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></li><?php
		}
	}
}

# Displays a list of categories
function dp_list_categories($exclude='') {
	if (strlen($exclude)>0) $exclude = '&exclude=' . $exclude;
	$categories = get_categories('hide_empty=1'.$exclude);
	$first = true; $count = 0;
	foreach ($categories as $category) {
		$count++; if ($count>5) break; // limit to 5
		if ($category->parent<1) {
			if ($first) { $first = false; $f = ' class="f"'; } else { $f = ''; }
			?><li<?php echo $f; ?>>
			<a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->name ?><?php echo $raquo; ?></a></li>
			<?php
		}
	}
}

# Displays a list of popular posts
function dp_popular_posts($num, $pre='<li>', $suf='</li>', $excerpt=false) {
	global $wpdb;
	$querystr = "SELECT $wpdb->posts.post_title, $wpdb->posts.ID, $wpdb->posts.post_content FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'post' ORDER BY $wpdb->posts.comment_count DESC LIMIT $num";
	$myposts = $wpdb->get_results($querystr, OBJECT);
	foreach($myposts as $post) {
		echo $pre;
		?><a class="title" href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title ?></a><?php
		if ($excerpt) {
			dp_attachment_image($post->ID, 'medium', 'alt="'.$post->post_title.'"');
			?><p><?php echo dp_clean($post->post_content, 85); ?>... <a href="<?php echo get_permalink($post->ID); ?>">Read More</a></p><?php
		}
		echo $suf;
	}
}

# Displays a list of recent categories
function dp_recent_comments($num, $pre='<li>', $suf='</li>') {
	global $wpdb, $post;
	$querystr = "SELECT $wpdb->comments.comment_ID, $wpdb->comments.comment_post_ID, $wpdb->comments.comment_author, $wpdb->comments.comment_content, $wpdb->comments.comment_author_email FROM $wpdb->comments WHERE $wpdb->comments.comment_approved=1 ORDER BY $wpdb->comments.comment_date DESC LIMIT $num";
	$recentcomments = $wpdb->get_results($querystr, OBJECT);
	foreach ($recentcomments as $rc) {
		$post = get_post($rc->comment_post_ID);
		echo $pre;
		dp_gravatar(52, 'alt="'.$rc->comment_author.'"', $rc->comment_author_email);
		?><a href="<?php the_permalink() ?>#comment-<?php echo $rc->comment_ID ?>"><?php echo dp_clean($rc->comment_content, 38); ?></a><?php
		echo $suf;
	}
}


# Displays post image attachment (sizes: thumbnail, medium, full)
function dp_attachment_image($postid=0, $size='thumbnail', $attributes='') {
	if ($postid<1) $postid = get_the_ID();
	if ($images = get_children(array(
		'post_parent' => $postid,
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
		foreach($images as $image) {
			$attachment=wp_get_attachment_image_src($image->ID, $size);
			?><img src="<?php echo $attachment[0]; ?>" <?php echo $attributes; ?> /><?php
		}
}

# Removes tags and trailing dots from excerpt
function dp_clean($excerpt, $substr=0) {
	$string = strip_tags(str_replace('[...]', '...', $excerpt));
	if ($substr>0) {
		$string = substr($string, 0, $substr);
	}
	return $string;
}

# Displays the comment authors gravatar if available
function dp_gravatar($size=50, $attributes='', $author_email='') {
	global $comment, $settings;
	if (dp_settings('gravatar')=='enabled') {
		if (empty($author_email)) {
			ob_start();
			comment_author_email();
			$author_email = ob_get_clean();
		}
		$gravatar_url = 'http://www.gravatar.com/avatar/' . md5(strtolower($author_email)) . '?s=' . $size . '&amp;d=' . dp_settings('gravatar_fallback');
		?><img src="<?php echo $gravatar_url; ?>" <?php echo $attributes ?>/><?php
	}
}

# Retrieves the setting's value depending on 'key'.
function dp_settings($key) {
	global $settings;
	return $settings[$key];
}

?>