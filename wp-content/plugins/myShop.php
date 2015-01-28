<?php
/*
Plugin Name: MyShop Activate
Description: MyShop Activate
Author: andrew
Version: 1.0
Author URI: http://www.onemoredesign.ru
*/

function my_hook(){

	global $wp_query;	
    $url = parse_url($_SERVER['REQUEST_URI']);		    
    if( preg_match('/^\/catalog/',$url['path']) ){
            
        // Подавляем вывод
		unset($wp_query->query["error"]);
		$wp_query->query_vars["error"]="";
        $wp_query->is_404=false;
		status_header( 200 );
            
        if (file_exists(TEMPLATEPATH . "/shop.php")) {
            include(TEMPLATEPATH . "/shop.php");
			exit;
        }
    }
	elseif (preg_match('/^\/search/',$url['path']) ){
		unset($wp_query->query["error"]);
		$wp_query->query_vars["error"]="";
        $wp_query->is_404=false;
		status_header( 200 );
            
        if (file_exists(TEMPLATEPATH . "/searchc.php")) {
            include(TEMPLATEPATH . "/searchc.php");
			exit;
        }		
	}
	elseif (preg_match('/^\/item_/',$url['path']) ){
		
		$line=str_replace('/item_', '', $url['path']);
		$line=str_replace('.html', '', $line);
		$pos = strpos ($line, "_");
		$old_id=substr($line, 0, $pos);		
		$result= mysql_query("SELECT ID FROM shop_catalog WHERE old_id=$old_id ");
		$row=mysql_fetch_array($result);
		$ID=$row['ID'];
		header('Location: http://europrofcosmetic.ru/catalog/products/'.$ID);
	}
	elseif (preg_match('/^\/payment/',$url['path']) ){

		unset($wp_query->query["error"]);
		$wp_query->query_vars["error"]="";
        $wp_query->is_404=false;
		status_header( 200 );
            
        if (file_exists(TEMPLATEPATH . "/payment.php")) {
            include(TEMPLATEPATH . "/payment.php");
			exit;
        }		
	}	
}
//

function give_me_url(){
	//global $wp_query;
	global $URL_PARTS;
	
	$url = parse_url($_SERVER['REQUEST_URI']);
	$line=$url['path'];
	
	$i=0;
	
	while ($line) {
		$pos = strpos ($line, "/");
		if ($pos!==false) {
			if ($pos>0) $URL_PARTS[$i] =  trim(substr ($line, 0, $pos ));
			$line = substr_replace ($line, "", 0, $pos+1 ) ;
		} else {
			$URL_PARTS[$i]=$line;
			$line='';
		}
		if ($pos>0) $i++;
	}

	if (!$URL_PARTS[1]){
		if ($URL_PARTS[0]!='catalog.html'){
			$line=str_replace('catalog_', '', $URL_PARTS[0]);
			$line=str_replace('.html', '', $line);
			// $URL_PARTS[0]='shop';
			$URL_PARTS[1]=$line;
		}
		else {
			$URL_PARTS[0]='catalog';
		}
	}
	
	return $URL_PARTS;
}

//
function title_rewrite($title) {
    global $new_page_title;
    return $new_page_title.$title;
}


function get_param($slug){

	$result= mysql_query("SELECT shop_params.ID, shop_params.type, shop_params.name, shop_params.slug, shop_texts.title, shop_texts.page_title, shop_texts.page_keywords, shop_texts.page_description FROM shop_params, shop_texts WHERE shop_params.slug='$slug' AND shop_texts.param_id=shop_params.ID AND shop_params.archive=0 ");
	while ($row=mysql_fetch_array($result)){		
		$return[0]=$row['ID'];
		$return[1]=$row['type'];
		$return[2]=$row['name'];
		$return[3]=$row['slug'];
		$return[4]=$row['title'];

		$return[5]=$row['page_title'];
		$return[6]=$row['page_keywords'];
		$return[7]=$row['page_description'];		
	}
	return $return;
}
//
give_me_url();


// }
global $param;

$is=0;

if ($URL_PARTS[1]!='products'){
	$param=get_param($URL_PARTS[1]);
	 
	if ($param[0]) $is=1;
// print_r($param);
	for ($i=2;$i<count($URL_PARTS); $i++){
		$titlaz=get_param($URL_PARTS[$i]);
		if ($titlaz[0]) $is=1;
		else $is=0;
		$titla.=' '.$titlaz[2].' '.$titlaz[4];
	}
	
	$new_title=$param[2].$titla.' ';
	// $x_title=$param[2].$titla.' '.$param[4].' ';

	if ($param[5]!='') $new_page_title=$param[5].$titla.' ';
	else  $new_page_title=$param[2].$titla.' ';

	$page_keywords=$param[6];
	$page_description=$param[7];
	
	if ($URL_PARTS[1]=='order') {
		$new_page_title='Корзина';
		$is=1;
	}
}
else {
	$result=mysql_query("select name, seo_title from `shop_catalog` WHERE id=$URL_PARTS[2]");
	while ($product_name=mysql_fetch_array($result)){
		if ($product_name['seo_title']!='') $new_page_title= 'Купить '.$product_name['seo_title'].' с доставкой в Москве ';
		else $new_page_title= 'Купить '.$product_name['name'].' с доставкой в Москве ';
		$is=1;
	}
}

if ($URL_PARTS[0]=='catalog'&&!$URL_PARTS[1]){
	/*
	$result=mysql_query("select name from `shop_params` WHERE type=0");
		$new_title='';
	while ($product_name = mysql_fetch_array($result)){
		$new_title.=$product_name[0].', ';
	}
	*/
	$new_page_title="Каталог косметики EuroProfCosmetic";
	$is=1;
}
//
if ($URL_PARTS[0]=='payment'){
	$is=1;
	$new_page_title="Оплата заказа";
}

 

if ($is==1){
	add_action('template_redirect', 'my_hook');
	add_filter('wp_title', 'title_rewrite');
}
?>