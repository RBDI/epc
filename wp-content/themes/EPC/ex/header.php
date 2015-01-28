<?

global $URL_PARTS;
global $sex, $brand;



for ($i=1; $i<count($URL_PARTS);$i++){
  $param=get_param($URL_PARTS[$i]);
  
  //print_r ($param);
    
    if ($param[1]==0) { $type_select=$param; $type_select[3].='/'; }
    elseif ($param[1]==1) { $sex_select=$param; $sex_select[3].='/'; }
    elseif ($param[1]==2) { $brand_select=$param; $brand_select[3].='/'; }
    elseif ($param[1]==3) { $special_select=$param; }   
  
}
  

  $sql="select * from `shop_params` ORDER BY pos ASC";  
  $result = mysql_query($sql) or die(mysql_error());
  
  $type='<option value="">Любой...</option>'; 
  
  if (!$type_select[0]) $all_types_selected='class="selected"'; 
  if (!$sex_select[0]) $all_sex_selected='class="selected"';
  if (!$brand_select[0]) $all_brand_selected='class="selected"';  
  
  $brand='<ul><li style="margin-bottom:11px;"><a href="/shop/'.$sex_select[3].$type_select[3].'" '.$all_brand_selected.'>Все бренды</a></li>'; 
//  $sex='<ul><li><a href="/shop/'.$type_select[3].$brand_select[3].'" '.$all_sex_selected.'>Все</a></li>';
  
  global $type_menu,$brand_logo;
  global $fullmenu;
  global $view_type,$view_brand,$view_sex,$special,$type_menu_index;
  $type_menu='<ul><li style="margin-bottom:11px;"><a href="/shop/'.$brand_select[3].'" '.$all_types_selected.'>Все категории</a></li>';
  
  while ($row=mysql_fetch_array($result)) {
    $selected = ''; 
    
    if ($row['type']==0){ 
      if ($row['ID']==$type_select[0]) {
        $selected='class="active"';
        $view_type=$type_select[0];
        if ($brand_select[0]) $crumb_type='<a href="/shop/'.$row['slug'].'">'.$row['name'].'</a> &rsaquo; ';
        else $crumb_type=$row['name'];
      }
        
      if ($row['ID']!=24&&$row['ID']!=25&&$row['parent']==0) {
        $type_menu.='<li><a href="/shop/'.$sex_select[3].$row['slug'].'/'.$brand_select[3].'" '.$selected.'>'.$row['name'].'</a></li>';         
        
        $type_idd=$row['ID'];
        $is_prod=mysql_fetch_array(mysql_query("select ID from `shop_catalog` WHERE `type`='$type_idd' LIMIT 1"));
/*        
        if ($is_prod['ID']!='') $type_menu_index.='<div class="index_type" align="center"><a href="/shop/'.$sex_select[3].$row['slug'].'/'.$brand_select[3].'" '.$selected.'><img src="/logos/'.$row['logo'].'" border="0"></a><br /><a href="/shop/'.$sex_select[3].$row['slug'].'/'.$brand_select[3].'" '.$selected.'>'.$row['name'].'</a></div>';
        else $type_menu_index.='<div class="index_type" align="center"><img src="/logos/'.$row['logo'].'" border="0"><br />'.$row['name'].'</div>';
*/        
        $type_menu_index.='<li '.$selected.'><a href="/shop/'.$sex_select[3].$row['slug'].'/" >'.$row['name'].'</a></li>';
      }
      
      $fullmenu.='<a href="/shop/'.$sex_select[3].$row['slug'].'/'.$brand_select[3].'" >'.$row['name'].'</a>, '; 
    }
    
    if ($row['type']==1){
      if ($row['ID']==$sex_select[0]) {
        $selected='class="selected"';
        $view_sex=$sex_select[0];
        
      }
      $sex.='<li><a href="/shop/'.$row['slug'].'/'.$type_select[3].$brand_select[3].'" '.$selected.'><img src="/products/logos/'.$row['slug'].'.gif" border="0"> '.$row['name'].'</a></li>';
    }
    if ($row['type']==2){ 
      if ($row['ID']==$brand_select[0]) {
        $selected='class="selected"';
        $view_brand=$brand_select[0];
        $crumb_brand=$row['name'];
      }
      $smallogo='logos/small/'.$row['logo'];
      if (!is_file($smallogo)) $smallogo='logos/'.$row['logo'];
      
      $brand.='<li><a href="/shop/'.$sex_select[3].$type_select[3].$row['slug'].'" '.$selected.'><img src="/'.$smallogo.'" border="0" width="50" height="15"> '.$row['name'].'</a></li>'; 
      
      $brand_idd=$row['ID'];
      $is_prod=mysql_fetch_array(mysql_query("select ID from `shop_catalog` WHERE `brand`='$brand_idd' LIMIT 1"));
      /*
      if ($is_prod['ID']!='') $brand_logo.='<a href="/shop/'.$sex_select[3].$type_select[3].$row['slug'].'" '.$selected.'><img src="/logos/'.$row['logo'].'" border="0"></a>';
      else 
      */
      if ($row['ID']!=143) $brand_logo.='<a href="/shop/'.$row['slug'].'"><img src="/logos/'.$row['logo'].'" border="0"></a>';
    }
    if ($row['type']==3){
      if ($special_select[3]=='sale15') $special=3;
      if ($special_select[3]=='new') $special=2;
      if ($special_select[3]=='sale') $special=1;     
    }   
  }
  $type_menu.='</ul>';
  $type.='</ul>';
  $brand.='</ul>';
  $sex.='</ul>';
  
?>

<?

// FOR Breadcrumbs
if ($URL_PARTS[0]=='shop'){

if (!$URL_PARTS[1]){
  $crumbs='Каталог';
}
else{
  $crumbs='Catalog &rsaquo; ';
} 

if ($URL_PARTS[1]=='products'&&$URL_PARTS[2]){
  $ITEM_ID=$URL_PARTS[2];
  $sql="select name,type,brand from `shop_catalog` WHERE `ID`='$ITEM_ID' LIMIT 1";  
  $I_TEM=mysql_fetch_array(mysql_query($sql));
  $product=$I_TEM['name'];
  
  $sql="select name, slug,type from `shop_params` WHERE `ID`='$I_TEM[1]' OR `ID`='$I_TEM[2]' LIMIT 2";  
  $result = mysql_query($sql) or die(mysql_error());
  while ($row=mysql_fetch_array($result)) {
    if ($row['type']==0) {$crumb_type='<a href="/shop/'.$row['slug'].'">'.$row['name'].'</a> &rsaquo; '; $crumb_type_slug=$row['slug'];}
    else {$crumb_brand=$row['name'].'</a> &rsaquo; '; $crumb_brand_slug=$row['slug'];}
  }   
  
  $crumb_brand='<a href="/shop/'.$crumb_type_slug.'/'.$crumb_brand_slug.'">'.$crumb_brand;
  
} 
  $crumbs.=$crumb_type.$crumb_brand.$product;

}
// EOF FOR Breadcrumbs
global $new_title;

if ($_SESSION['fltr']['cat_id']!=$view_type&&$URL_PARTS[1]!='products') unset($_SESSION['fltr']);


add_filter( 'wpcf7_form_class_attr', 'your_custom_form_class_attr' );

function your_custom_form_class_attr( $class ) {
  $class .= ' form-horizontal';
  return $class;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><? print $new_title; ?> - Baradom.eu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <!--<link href='http://fonts.googleapis.com/css?family=Elsie:400,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>-->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,900,500&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

    <link href="<?php bloginfo('template_url'); ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet">
    <link href="<?php bloginfo('template_url'); ?>/css/bootstrap-responsive.css" rel="stylesheet">
    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php bloginfo('template_url'); ?>/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_url'); ?>/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_url'); ?>/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_url'); ?>/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_url'); ?>/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/ico/favicon.png">
  <? wp_head(); ?>
  </head>

  <body>

    <div class="container">

      <div class="masthead">
        <div class="header">
        <div class="row">
          <div class="span2">
            <h3 class="muted"><a href="/" title="Baradom - Boisson À La Maison"><img src="<?php bloginfo('template_url'); ?>/images/baradom_wings.png"></a></h3>
          </div>
          <div class="span6"  >
            <div class="h_cont">
              <div style="color:#FFF; margin-bottom:6px;">Livraison de boissons à domicile à Lyon et agglomération.</div>
            Téléphone: <span class="phone">04 78 47 47 47</span>
            <div style="color:#FFF;font-size:12px;">La livraison de 10h à 22h, du Lundi au Samedi.</div>
             
            <!--E-mail: <span class="mail"><a href="mailto:contact@baradom.eu">contact@baradom.eu</a></span>-->
          </div>
              <div class="top-menu">
                <a href="/" >Accueil</a> - 
                <a href="/about/" >Qui sommes-nous?</a> - 
                <a href="/contacts/" >Nous contacter</a> &nbsp; 
                <!-- <a href="/entreprises/" class="btn btn-primary" >Entreprises</a> -->
              </div>
            
          </div>
          <div class="span2">
            <div class="basket" align="center">
            <a href="/shop/"><img src="<? bloginfo('template_url'); ?>/images/basket.gif" border="0" /></a> 
            <?
               print 'Panier: <strong><span id="basketcount">'.count($_SESSION["ORDER"]).'</span></strong><br> <span class="gotoorder"><a class="btn btn-warning btn-small"  href="/shop/order">Voir</a></span>';
            ?>
            </div>
          </div>
        </div>
        </div>

        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <ul class="nav" style="text-transform: uppercase;">
                <?
                global $type_menu_index;
                print $type_menu_index;
                ?>
              </ul>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>

      <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="myModalLabel">Acheter</h3>
      </div>
      <div class="modal-body">
      <p>One fine body…</p>
      </div>
      <div class="modal-footer">
      <button class="btn btn-success">Acheter</button>
      <button class="btn btn-small" data-dismiss="modal" aria-hidden="true">Close</button>
      </div>
      </div>