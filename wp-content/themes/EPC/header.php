<?
// SET COOKIE

// print_r($_GET);
// print_r($_SERVER);
  $source=$_GET['utm_source'];
  $term=$_GET['utm_term'];
   
  $epc_source=$_COOKIE['epc_source'];
  $epc_term=$_COOKIE['epc_term'];

  if ($epc_source==''){
    setcookie("epc_source", $source, time()+60*60*24*365);
    setcookie("epc_term", $term, time()+60*60*24*365);    
  }
?>
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
        $type_menu_index.='<li '.$selected.'><a href="/catalog_'.$sex_select[3].$row['slug'].'.html" >'.$row['name'].'</a></li>';
      }
      
      $fullmenu.='<a href="/shop/'.$sex_select[3].$row['slug'].'/'.$brand_select[3].'" >'.$row['name'].'</a>, '; 
    }
    
    if ($row['type']==1){
      if ($row['ID']==$sex_select[0]) {
        $selected='class="selected"';
        $view_sex=$sex_select[0];
        
      }
      // $sex.='<li><a href="/catalog_'.$row['slug'].'/'.$type_select[3].$brand_select[3].'" '.$selected.'><img src="/products/logos/'.$row['slug'].'.gif" border="0"> '.$row['name'].'</a></li>';
    }
    if ($row['type']==2){ 
      if ($row['ID']==$brand_select[0]) {
        $selected='class="selected"';
        $view_brand=$brand_select[0];
        $crumb_brand=$row['name'];
      }
      $smallogo='logos/small/'.$row['logo'];
      if (!is_file($smallogo)) $smallogo='logos/'.$row['logo'];
      
      $brand.='<li><a href="/catalog_'.$sex_select[3].$type_select[3].$row['slug'].'.html" '.$selected.'><img src="/'.$smallogo.'" border="0" width="50" height="15"> '.$row['name'].'</a></li>'; 
      
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

  global $page_description, $page_keywords;

?>

<?

// FOR Breadcrumbs
// if ($URL_PARTS[0]=='shop'){

// if (!$URL_PARTS[1]){
//   $crumbs='Каталог';
// }
// else{
//   $crumbs='Catalog &rsaquo; ';
// } 

// if ($URL_PARTS[1]=='products'&&$URL_PARTS[2]){
//   $ITEM_ID=$URL_PARTS[2];
//   $sql="select name,type,brand from `shop_catalog` WHERE `ID`='$ITEM_ID' LIMIT 1";  
//   $I_TEM=mysql_fetch_array(mysql_query($sql));
//   $product=$I_TEM['name'];
  
//   $sql="select name, slug,type from `shop_params` WHERE `ID`='$I_TEM[1]' OR `ID`='$I_TEM[2]' LIMIT 2";  
//   $result = mysql_query($sql) or die(mysql_error());
//   while ($row=mysql_fetch_array($result)) {
//     if ($row['type']==0) {$crumb_type='<a href="/shop/'.$row['slug'].'">'.$row['name'].'</a> &rsaquo; '; $crumb_type_slug=$row['slug'];}
//     else {$crumb_brand=$row['name'].'</a> &rsaquo; '; $crumb_brand_slug=$row['slug'];}
//   }   
  
//   $crumb_brand='<a href="/shop/'.$crumb_type_slug.'/'.$crumb_brand_slug.'">'.$crumb_brand;
  
// } 
//   $crumbs.=$crumb_type.$crumb_brand.$product;

// }
// EOF FOR Breadcrumbs
global $new_title,$x_title;

if ($_SESSION['fltr']['cat_id']!=$view_type&&$URL_PARTS[1]!='products') unset($_SESSION['fltr']);


add_filter( 'wpcf7_form_class_attr', 'your_custom_form_class_attr' );

function your_custom_form_class_attr( $class ) {
  $class .= ' form-horizontal';
  return $class;
}

?>
<!DOCTYPE html>
<html>
  <head>
<?    
    if ($page_description) print '<meta name="description" content="'.$page_description.'">';
    if ($page_keywords) print '<meta name="keywords" content="'.$page_keywords.'">';
?>    
    <title><?php wp_title(); ?></title>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- Bootstrap -->
    <link href="<?php bloginfo('template_url'); ?>/css/bootstrap.css" rel="stylesheet" media="screen">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />    
    <?
     if ($_GET['page']) {
      $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
      print '<link rel=canonical href="http://'.$_SERVER['HTTP_HOST'].$uri_parts[0].'"/>';
      }
    ?>	   

    <link href="<?php bloginfo('template_url'); ?>/style.css" rel="stylesheet" media="screen">    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php bloginfo('template_url'); ?>/js/html5shiv.js"></script>
      <script src="<?php bloginfo('template_url'); ?>/js/respond.min.js"></script>
    <![endif]-->
    <link href='http://fonts.googleapis.com/css?family=PT+Serif+Caption:400,400italic&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1&appId=127344077345328";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>


    <!-- Put this script tag to the <head> of your page -->
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?101"></script>

    <script type="text/javascript">
      VK.init({apiId: 3966422, onlyWidgets: true});
    </script>

  </head>
  <body>    
    <div class="container header">
      <div class="row">
        <div class="col-xs-3">
          <div style="font-size:12px;" align="center">
          <a href="/" class="main_logo"><img src="<?php bloginfo('template_url'); ?>/img/logo_europrof.png"></a>
          Интернет-магазин профессиональной косметики и оборудования.</div>
        </div>
        <div class="col-xs-6"  style="margin-top:6px;">
          <div class="row">
            <div class="col-xs-12" align="center">
          <strong>Время работы:</strong> Пн-Пт, 10:00 - 19:00 <strong>E-mail:</strong> zakaz@europrofcosmetic.ru               
            </div>
            <div class="col-xs-12" align="center">
              <a href="/delivery_and_payment.html"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/cards.png" width="100" title="Вы можете оплатить заказ банковской картой Visa или MasterCard"></a>
              <a href="/delivery_and_payment.html"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/yad.png" width="70" style="margin-left:10px;" title="Вы можете оплатить заказ Яндекс.Деньгами"></a>
            </div>

          </div>
          
<!--           <div class="row">
            <div class="col-xs-4">
              <div class="checkit nums">1</div>
              Сертификаты, Гарантии
            </div>
            <div class="col-xs-4">
              Отзывы
            </div>
            <div class="col-xs-4">
              Удобная оплата
            </div>
          </div> -->
        </div>
        <div class="col-xs-3" align="center">
          
<?
$user_ip=$_SERVER['REMOTE_ADDR'];
$city=occurrence($user_ip);
// print $city;
if ($city=='Москва') {
  $phone='Наш телефон в Москве <div class="myfont mainphone">8 (499) 322-10-17</div>';
  $miniphone='8 (499) 322-10-17';
}
elseif ($city=='Санкт-Петербург') {
  $phone='Наш телефон в Петербурге <div class="myfont mainphone">8 (812) 426-14-61</div>';
  $miniphone='8 (812) 426-14-61';
}
else {
  $phone='Бесплатные звонки по России <div class="myfont mainphone">8 (800) 500-05-79</div>';
  $miniphone='8 (800) 500-05-79';
}
?>          
          <? print $phone; ?>
          
          <!-- <div class="backcall"><a data-toggle="modal" href="#Backcall" >Оставьте номер</a> и мы перезвоним вам</div>       -->
        </div>
      </div>

<!-- <div class="myfont" style="margin-top:-20px; margin-bottom:20px; background:url(<?php bloginfo('template_url'); ?>/img/8marth.jpg) center center #009eed; text-align:center; color:#FFF; text-shadow: 1px 1px 0px #888; font-size:25px; padding:15px 0px;">
  <a href="/catalog_8-marta.html" style="color:#FFF;">Скидки в для милых дам в связи с 8 марта! &rarr;</a>
</div>
 -->

<!-- <div class="myfont" style="margin-top:5px; margin-bottom:5px; background: url(<?php bloginfo('template_url'); ?>/img/atm.jpg); text-align:center; color:#FFF; text-shadow: 1px 2px 1px #444; font-size:20px; padding:15px 0px;">
  1-4 ноября магазин не работает! С праздником!
</div> -->


    </div>

  <!-- Modal -->
  <div class="modal fade" id="Backcall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Обратный звонок</h4>
        </div>
        <div class="modal-body">

          <form class="form-horizontal" role="form" id="backcall_form">
            <div class="form-group">
              <label for="inputName_backcall" class="col-xs-3 control-label">Ваше имя</label>
              <div class="col-xs-8">
                <input type="text" class="form-control" name="inputName_backcall" id="inputName_backcall" placeholder="Введите имя">
              </div>
            </div>
            <div class="form-group">
              <label for="inputContant_backcall" class="col-xs-3 control-label">Телефон</label>
              <div class="col-xs-8">              
                <div class="input-group">
                  <span class="input-group-addon">+7</span><input type="text" class="form-control" name="inputContact_backcall" id="inputContact_backcall" placeholder="495XXXXXXX">
                </div>
              </div>
            </div>
              <div class="form-group">
                <div class="col-xs-offset-3 col-xs-8">
                  <button type="button" class="btn btn-success btn-lg myfont" onclick="backcall();" >Позвоните мне!</button>
                </div>
              </div>            
          </form>
          <div style="display:none;" id="backcall_success" align="center">
            <big>Ваша заявка отправлена!</big><br/>
            Скоро мы вам перезвоним.
            <div style="margin-top:10px;"><button type="button" class="btn btn-default" onclick="return refresh_backcall();" data-dismiss="modal">Закрыть</button></div>
          </div>
        </div>
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->    
<div style="/* height:102px; */ height:147px;">
<div class="nmenu" data-spy="affix" data-offset-top="95">
    <div style="background:#FFF;">
      <div class="container">
        <div class="row" >
          <div class="col-xs-3">
            <img src="<?php bloginfo('template_url'); ?>/img/bea.png" style="float:right;">
            <div class="scroll_show" style="margin-top:8px; text-align:left">
              <a href="/"><img src="/wp-content/themes/EPC/img/logo_europrof.png" width="180"></a>

            </div>

          </div>
          <div class="col-xs-7">
          <ul class="nav nav-pills">                      
            <li><a href="/delivery_and_payment.html">Доставка и оплата</a></li>
            <li><a href="/contacts.html">Контакты</a></li>
            <li><a href="http://opt.europrofcosmetic.ru/">Опт</a></li>
            <li><a href="/about.html">О магазине</a></li>
            <li>
    <form style="float:left;" role="search" method="post" action="/catalog.html#search">
      
        <input type="text" class="form-control" name="search_word" style="width:100px; margin-top:3px;" placeholder="Поиск">
        <input type="hidden" value="1" name="is_search">
      
    </form>

    <img src="<?php bloginfo('template_url'); ?>/img/bea_s.png" style="margin-left:10px;">
            </li>
          </ul>

    <!-- <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button> -->          
        </div>
        <div class="col-xs-2">
          <div class="scroll_show myfont miniphone" >
            <? print $miniphone; ?>
          </div>
        </div>
        </div>
      </div>
    </div>
      <div class="container">

<!-- <nav class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>    
  </div>
  <div class="collapse navbar-collapse navbar-ex1-collapse" style="padding-left:0px;">
    <ul class="nav navbar-nav">
      <li class="active"><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
      <li><a href="/catalog.html">Каталог</a></li>
      <li><a href="/delivery_and_payment.html">Доставка и оплата</a></li>
      <li><a href="/contacts.html">Контакты</a></li>
      <li><a href="http://opt.europrofcosmetic.ru/">Опт</a></li>
      <li><a href="/about.html">О магазине</a></li>
    </ul>
    <form class="navbar-form navbar-left" role="search" method="post" action="/catalog.html">
      <div class="form-group">
        <input type="text" class="form-control" name="search_word" placeholder="Поиск">
        <input type="hidden" value="1" name="is_search">
      </div>
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
    </form>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span></a></li>
      <li><div style="padding:15px 15px 15px 0;">
            <?
               print 'Корзина: <strong><span id="basketcount">'.count($_SESSION["ORDER"]).'</span></strong> <a href="/catalog/order">Оформить &rarr;</a> ';
            ?>
        </div>
      </li>
    </ul>
  </div>
</nav> -->



<nav class="navbar navbar-inverse navbar-default" role="navigation" >

  <!-- data-spy="affix" data-offset-top="128" data-offset-bottom="200"> -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>    
  </div>

  <div class="collapse navbar-collapse navbar-ex1-collapse" style="padding-left:0px;">
    <ul class="nav navbar-nav myfont">          
<?
  global $view_type;
  print get_top_allmenu($view_type);
?>
<li class="dropdown">
  <a href="#" class="dropdown-toggle js-activated" data-toggle="dropdown">Бренды</a>
  <ul class="dropdown-menu brands-menu">
    <?   print get_top_brandmenu(); ?>
  </ul>
</li>
    </ul>


     <ul class="nav navbar-nav navbar-right">
      <li><a href="/catalog/order" style="color: #FFF;"><span class="glyphicon glyphicon-shopping-cart"></span></a></li>
      <li><div style="padding:15px 15px 15px 0; color: #FFF;">
            <?
               print 'Заказы: <strong><span id="basketcount">'.count($_SESSION["ORDER"]).'</span></strong> <a href="/catalog/order">Оформить &rarr;</a> ';
            ?>
        </div>
      </li>

    </ul>
  </div> 
</nav>

<!-- <div class="myfont" style="margin-top:-20px; margin-bottom:20px; z-index:9999; background:url(<?php bloginfo('template_url'); ?>/img/backbanner.jpg) center center #009eed; text-align:center; color:#006ea1; text-shadow: 1px 1px 0px #FFF; font-size:20px;">
  <img src="<?php bloginfo('template_url'); ?>/img/bea.png"> &nbsp; <img src="<?php bloginfo('template_url'); ?>/img/bea_s.png"> &nbsp; 
  С Новым годом! Мы работаем: 27.12 (11-17), отдыхаем <span style="font-size:23px;">29.12-12.01</span>! 
   &nbsp; <img src="<?php bloginfo('template_url'); ?>/img/bea_s.png"> &nbsp; 
  <img src="<?php bloginfo('template_url'); ?>/img/bea.png">
</div> -->


      </div>
</div>
</div>
    