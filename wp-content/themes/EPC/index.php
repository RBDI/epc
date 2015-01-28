<? get_header(); ?>    
    <div class="container">
<!--       <div class="index_pic">
      
      <h1>Профессиональная<br>  косметика и оборудование<br> от официального дилера</h1>
      <p>Более 25 брендов, более 5000 товаров. Доставка по России и СНГ.</p>
        <a   class="btn btn-success btn-lg myfont" href="/catalog.html">Перейти в каталог &rarr;</a>      
    	</div> -->

      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="margin:20px 0px 40px;">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
          <li data-target="#carousel-example-generic" data-slide-to="1"></li>
          <li data-target="#carousel-example-generic" data-slide-to="2"></li>
          <li data-target="#carousel-example-generic" data-slide-to="3"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
          <div class="item active">
            <a href="/catalog_beauty_style_cosm.html"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/b4.jpg"></a>
            <!-- <div class="carousel-caption">Beauty Style</div> -->
          </div>
          <div class="item">
            <a href="/catalog_nabori-s-mezorollerami.html"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/b3.jpg" alt=""></a>
            <!-- <div class="carousel-caption">Beauty Style</div> -->
          </div>
          <div class="item">
            <a href="/catalog/products/4891"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/b2.jpg" alt=""></a>
            <!-- <div class="carousel-caption">Beauty Style</div> -->
          </div>
          <div class="item">
            <a href="/catalog/products/4585"><img src="http://europrofcosmetic.ru/wp-content/themes/EPC/img/b1.jpg" alt=""></a>
            <!-- <div class="carousel-caption">Beauty Style</div> -->
          </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
      </div>

    </div>

    <div align="center" style="margin-bottom:50px;">
      <p><strong>EuroProfCosmetic</strong> &mdash; интернет-магазин для специалистов индустрии красоты (работников и владельцев салонов и спа-центров).<br/>
      	Бренды с лучшей репутацией &mdash; <strong>Algologie, Decomedical, Depileve, Janssen Cosmeceutical, Mertz, Reneve.</strong>
      </p>
      <p>
      	Продукция соответствует всем требованиям и существующим стандартам качества, принятым на территории России и ЕС,<br/>
      	а также сертифицирована в соответствии с требованиями технических регламентов.
      </p>
    </div>

    <div class="advan">    
      <div class="container">
        <center><h2>Наши преимущества</h2></center>
        <div class="row">
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>12 лет на рынке!</h3>
            Уже долгое время мы являемся одним из крупнейших дилеров.
          </div>
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>Быстрая доставка</h3>
            По Москве - 1-2 дня, по России и СНГ - 3-6 дней.
          </div>
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>Гарантия</h3>
            Гарантийное обслуживение в течении года, а также возврат в случае проблем.
          </div>
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>Сертификаты</h3>
            Только сертифицированная косметика и оборудование.
          </div>
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>100% оригинал</h3>
            Только подлинные товары.
          </div>
          <div class="col-md-4">
            <div class="checkit"><span class="glyphicon glyphicon-ok"></span></div>
            <h3>Удобные способы оплаты</h3>
          </div>          
        </div>
      </div>
    </div>
    <div class="container prd">
      <center><h2>Товары по акции!</h2></center>

      
<?
$items=get_special_products(55);
// print_r($items);
$j=0;
    foreach ($items as $item_id => $item){
      $i++;
      foreach ($item['subitem'] as $subitem_id => $subitem) {
          
          if ($j==0) print '<div class="row">';
          
          print "<div class='col-md-4 product'>";
      
      if ($item['image'][$subitem_id]!='') $image=$item['image'][$subitem_id];
      elseif ($item['image'][0]!='') $image=$item['image'][0];
      else $image='';

      if ($image) {
      print "
        <div class='product-preview'>
          <a href='/catalog/products/".$item_id."'><img src='/products/".$image."_small.jpg' title='".$item['name']."' class='img-responsive'  /></a>         
        </div>";
         
      } 
      if ($subitem['special_price']>0) $price=$subitem['special_price'];
      else $price=$subitem['price'];
      
      // $price=substr($price,0,-3).' '.substr($price,-3);
      // if ($item['brand_name']!='') $item['brand_name']='<div class="myfont">'.$item['brand_name']."</div>";
      if ($item['brand_name']!='') $item['brand_name']=$item['brand_name']." - ";
      $title=$item['brand_name'];
      if ($item['name']!=''&&$item['name']!=' ') $title.=$item['name'];

      if ($subitem['value']!='') $title.=', '.$subitem['value'];

      print '<input type="hidden" id="item_id_'.$subitem_id.'" value="'.$item_id.'">';
      print '<span id="article_'.$subitem_id.'" style="display:none;">'.$subitem['value3'].'</span>';
      print "
        <div class='product-desc' >         
          <div class='product-price'>Цена: <span class='price_count' id='itemprice_".$subitem_id."'>".$price."</span> р. <br>
          <a  onclick='buyrightnow(".$subitem_id.")' data-toggle='modal' href='#Buynow' class='btn btn-mini btn-success'>Купить</a>";
print '<div style="display:inline-block; margin: 0px 10px;">
      <a href="javascript:{}" onclick="ch_precount('.$subitem_id.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
          <a href="javascript:{}" onclick="ch_precount('.$subitem_id.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>';
      print '<input type="text" class="form-control" id="item_count_'.$subitem_id.'" name="item_count['.$subitem_id.']" value="1" style="width:42px; display: inline-block; "></div>';
      print "<div style='display:none;'><a href='javascript:{};' id='ache_".$subitem_id."' onclick='buyrightnow(".$subitem_id.")' class='btn btn-xs btn-default'>В корзину</a></div>";          

          // "<a href='javascript:{};' id='ache_".$subitem_id."' onclick='buyrightnow(".$subitem_id.")' class='btn btn-sm btn-default'>В корзину</a>
          // <input type='hidden' id='count_".$subitem_id."' value='1'>";

      if ($subitem['special_price']>0) print '<div class="old_price">Старая цена: '.$subitem['price'].' р.</div>';
      print "</div>";
      print "<h4><a href='/catalog/products/".$item_id."' id='item_".$subitem_id."'>".$title."</a></h4>";

      
      print "</div>";     
      print "</div>";
      $j++;

          if ($j==3) {
            print '</div>';
            $j=0;
          }
      }
    }
    if ($j!=0) print '</div>';
?>      

    </div>
<!--     <div class="advan">
      <div class="container">
        <center><h2>Наш ассортимент</h2></center>
        <div class="row">
          <div class="col-md-4">
            <div class="checkit nums">1</div>
            <h3>Профессиональная косметика</h3>
          </div>
          <div class="col-md-4">
            <div class="checkit nums">2</div>
            <h3>Мезотерапия, биоревитализация, мезороллеры</h3>
          </div>
          <div class="col-md-4">
            <div class="checkit nums">3</div>
            <h3>Депиляция</h3>
            Воски, воскоплавы, косметика.
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="checkit nums">4</div>
            <h3>Парафинотерапия</h3>
            Парафин, ванночки, косметика.
          </div>
          <div class="col-md-4">
            <div class="checkit nums">5</div>
            <h3>Профессиональные краски, биозавивка, уход за волосами</h3>
          </div>
          <div class="col-md-4">
            <div class="checkit nums">6</div>
            <h3>Косметологическое оборудование</h3>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 col-md-offset-4">
            <div class="checkit nums">7</div>
            <h3>Мебель для салонов красоты</h3>
          </div>
        </div>
      </div>
    </div> -->

    <div class="container logos">
      <a name="brands"></a>
      <center><h2>Бренды</h2></center>
      <div class="row">

          <?
          $active_brands=get_active_brands();
          foreach ($active_brands as $brand_id => $brand) {
            print '<div class="col-md-2" align="center"><a href="/catalog_'.$brand['slug'].'.html"><img src="/logos/'.$brand['logo'].'" border="0"></a></div>';
          }

            //global $brand_logo;
            //print $brand_logo;
          ?>        
      </div>
    </div>

<?php get_footer(); ?>