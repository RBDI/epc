<? get_header(); ?>

<?
$items=get_special_products(1);
if (count($items)>0){
?> 

<!--    <img src="/wp-content/themes/Baradom/images/blank.jpg"> -->
    <!-- Carousel
    ================================================== -->

    <div id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
<?
$active='active';
foreach($items as $item_id => $item){
  $title='';
  if ($item['item_param']['6207']!='') $title.=" ".$item['item_param']['6207'];
  if ($item['item_param']['6208']!='') $title.=" ".$item['item_param']['6208'];
  if ($item['item_param']['6034']!='') $title.=" ".$item['item_param']['6034']."L";
  if ($item['item_param']['6045']!='') $title.= ", Pack de ".$item['item_param']['6045'];
  if ($item['desc']!='') $title.= "<br/> ".$item['desc'];
  print '
        <div class="item '.$active.'">
        <div class="today_promo">Aujourd\'hui PROMO!</div>
          <img src="/products/'.$item['image'].'_medium.jpg" alt="">
          <img class="logo" src="/logos/'.$item['brand_logo'].'" alt="">
          
          <div class="container">
            <div class="carousel-caption">            
              <div class="h1">'.$item['brand_name'].'</div> <h2>'.$item['name'].'</h2>
              <p>'.$title.'</p>
              <p class="lead">'.$item['price'].'&euro;</p>';
  if ($item['old_price']!='') print '
              <p class="old-price">Ancien prix: <span style="text-decoration: line-through;">'.$item['old_price'].' &euro;</span></p>';
  print '            
              <a class="btn btn-large btn-primary" href="/shop/products/'.$item_id.'">J\'en profite</a>
            </div>
          </div>
        </div>
  ';
  $active='';
}
?>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div><!-- /.carousel -->
<? } ?>
 
 <div class="row" style="margin:30px 0px; padding:20px 0px; background:#FFF;">       
        <div class="span10">
          <? $index_page=get_page(32);
            print $index_page->post_content; ?>
        </div>
</div>        

      <div class="row-fluid" style="margin:30px 0px;">
       
        <div class="span12 bottom_logos">
          <h3 style="margin-left:30px;">Nos différents partenaires:</h3>
          <?
          $active_brands=get_active_brands();
          foreach ($active_brands as $brand_id => $brand) {
            print '<a href="/shop/'.$brand['slug'].'"><img src="/logos/'.$brand['logo'].'" border="0"></a>';
          }

            //global $brand_logo;
            //print $brand_logo;
          ?>
        </div>
      </div>

      <div class="row-fluid" style="margin:30px 0px;">
       
        <div class="span12 index_text">
          <h1>Livraison de boissons à domicile à Lyon et agglomération.</h1><p>
BARADOM est un service rapide et efficace pour vous faire livrer à domicile par pack vos <a href="/shop/sodas/">sodas</a>, <a href="/shop/eaux/">eaux</a>, <a href="/shop/jus/">jus</a> de fruits, <a href="/shop/bieres/">bières</a> et <a href="/shop/vins/">vins</a>.<br/>
Pour les boissons sans alcool, nous disposons d’un grand choix de grandes marques telles que <a href="/shop/evian/">Evian</a>, <a href="/shop/volvic/">Volvic</a>, <a href="/shop/pago">Pago</a>, <a href="/shop/tropicana/">Tropicana</a> ou encore <a href="/shop/coca-cola/">Coca-Cola</a> et <a href="/shop/orangina/">Orangina</a>.<br/>
Vos achats de bières en ligne vont  enfin être facilités, car que ce soit <a href="/shop/heineken/">Heineken</a>, <a href="/shop/kronenbourg/">Kronenbourg</a>, <a href="/shop/carlsberg/">Carlsberg</a> ou même <a href="/shop/buckler/">Buckler</a>, une large gamme de bières est disponible quand vous le souhaitez.
</p>
<p>
Enfin, BARADOM est votre cave à vins en ligne à Lyon. Avec une très large gamme de vins, achetez et faites vous livrez vos caisses de vins en un clic.<br/>
Des dizaines de producteurs et d’appellations disponibles, de Duboeuf en passant par <a href="/shop/eguigal/">Guigal</a>, <a href="/shop/patriarche/">Patriarche</a> ou <a href="/shop/chapoutier/">Chapoutier</a> à des prix très avantageux.<br/>
En bouteilles ou en cubis, des promotions sur les vins vous sont régulièrement proposées. Côtes du Rhône, Chablis, Beaujolais, Bougogne, la sélection des meilleurs vins rouges, vins blancs et rosés est chez BARADOM.
</p>
        </div>
      </div>
<?php get_footer(); ?>