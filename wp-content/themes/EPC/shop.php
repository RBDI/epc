<?php get_header(); ?>

<div class="container">
	<? // print_r($_SESSION); ?>
	<div class="row">
	<!--
		<div class='span12 pingation'>
			<? dimox_breadcrumbs(1); ?>
			<p><a href='#null'>Каталог</a>  /  <a href='#null'>Видеорегистраторы</a>  / Garmin</p>
		</div>
	-->	
		
<?
global $new_title;
global $view_type,$view_brand, $special, $URL_PARTS;

// if ($URL_PARTS[1]!='products') {
// 	if ($URL_PARTS[1]=='special') print '<h1> '.get_newprd_title('index_prod_title').' </h1>';
// 	//else print '<h2> '.$new_title.' </h2>';
// }

// if (!$_POST['is_search']){
	if ($URL_PARTS[1]=='products'&&$URL_PARTS[2]) {

		$item=get_item($URL_PARTS[2]);	

		//print "<div class='product noborder'>";
		$subbrand='';
		if ($item['subbrand']) $subbrand='<small> / <a href="/catalog_'.$item['subbrand']['slug'].'.html">'.$item['subbrand']['name'].'</a></small>';

		print '<div class="col-sm-12" itemscope itemtype="http://data-vocabulary.org/Product">';
		print "<h3 style='margin-top:0px;'><a href='/catalog_".$item['type_slug'].".html'><span itemprop='category' content='".$item['type_name']."'>".$item['type_name']."</span></a> / <a href='/catalog_".$item['brand_slug'].".html'>".$item['brand_name']."</a>".$subbrand."</h3>";
		print '<div class="row">';	
		print "<div class='col-sm-6 product-view'>";
		$i=0;
		foreach ($item['image'] as $image_id =>$image){			
			$previews.= '<div class="col-md-3"><a href="javascript:{};" onclick="show_image(\''.$image['filename'].'\')"><img src="/products/'.$image['filename'].'_small.jpg" alt="" class="img-responsive" /></a></div>';
			// $bigimg="<img src='/products/".$image['filename']."_medium.jpg' id='bigimg' alt='' />";

			if ($m==0&&$image['main']==1){
				$m=1;
				$bigimg="<img itemprop='image' src='/products/".$image['filename']."_medium.jpg' id='bigimg' title='".$item['brand_name'].' '.$item['name']."'  alt='".$image_id."' /><input type='hidden' id='img_filename' name='aa' value='".$image['filename']."' >";
				$bigimg_id=$image_id;
			}
			elseif ($m==0) {
				$bigimg="<img itemprop='image' src='/products/".$image['filename']."_medium.jpg' id='bigimg' title='".$item['brand_name'].' '.$item['name']."' rel='".$image['filename']."' alt='".$image_id."' /><input type='hidden' id='img_filename' name='aa' value='".$image['filename']."' >";
				$bigimg_id=$image_id;
			}
			
			// $previews.= '<div class="preview_s"><a href="javascript:{};" onclick="show_image(\''.$image['filename'].'\')"><img src="http://europrofcosmetic.ru/files/'.$image['filename'].'" alt="" /></a></div>';
			// $bigimg="<img src='http://europrofcosmetic.ru/files/".$image['filename']."' id='bigimg' alt='' />";
			$i++;

		}
		if ($i==1) $previews='';
		print '<div style="width:500px; text-align:center;">'.$bigimg.'</div>';
		print '<div class="row">'.$previews.'</div>';
		if (count($item['files'])!=0){		 
			print "<h4>Файл</h4>";
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
			<div class='col-sm-6 product-data'>";
		print "<h2><span style='font-size:19px; color:#444; font-style:italic;'><span itemprop='brand'>".$item['brand_name']."</span> <small>".$item['brand_text']."</small></span>";
		if ($item['brand_country']!='') print "<div class='myfont' style='font-size:14px;'>".$item['brand_country']."</div>";
		print '<div style="font-size:23px; margin-top:12px;" itemprop="name">'.$item['name']."</div></h2>";
		
		

		// print '<div class="row" style="margin-top:0px;">';
	if ($item['list']==1){
		print '<div class="row" style="border-bottom:1px dashed #CCC; margin-top:10px; padding-bottom:10px;">';
		print '<div class="col-sm-3" id="subitemvalue_'.$subitem_id.'">';
		print '<select class="form-control" name="" onchange="change_x();" id="subitem_id_X">';
		$cont='';
		$gg='selected="selected"';
		foreach ($item['subitem'] as $subitem_id => $subitem) {
			print '<option value="'.$subitem_id.'" '.$gg.' >'.$subitem['name'].'</option>';
			$gg='';
			
			$cont.= '<input type="hidden" id="item_id_'.$subitem_id.'" value="'.$item['ID'].'">';
			if ($subitem['name']!='') {
				
				$cont.= '<span id="hid_name">'.$subitem['name'].'</span>';
				$subname= ', '.$subitem['name'];
				
			}			
			$cont.= '<span id="item_'.$subitem_id.'">'.$item['brand_name'].' '.$item['name'].$subname.'</span>';
			$cont.= '<span id="article_'.$subitem_id.'">'.$subitem['value3'].'</span>';
			
			
			if ($subitem['value2']!='') {
				$cont.= '<span id="itemprice_'.$subitem_id.'">'.$subitem['value2'].'</span>';
				$cont.= '<span id="hid_oldprice_'.$subitem_id.'">'.$subitem['value1'].' руб.</span>';
				
			}
			else {
				$cont.= '<span id="itemprice_'.$subitem_id.'">'.$subitem['value1'].'</span>';
				
			}						
			
			if ($subitem['instock']==0) $cont.= '<div id="hid_active_'.$subitem_id.'"><span class="label label-success">В наличии</span></div>';
			if ($subitem['instock']==1) $cont.= '<div id="hid_active_'.$subitem_id.'"><span class="label label-warning">Под заказ</span></div>';			
		}

				print '</select>';
				print '<span id="cont_name">
				<span style="font-size:13px; color:#999;" id="show_art"></span>

				</span>';			
			print '</div>';

			print '<div class="col-sm-3" id="cont_price">
			<div style="font-size:24px;"><span id="show_price">XXX</span> <span style="font-size:20px;">руб.</span></div>
			<div class="old_price" id="show_oldprice"></div>

			</div>';
			print '<div class="col-sm-6" id="cont_buy">';
			print "<a onclick='buyrightnow(0)' data-toggle='modal' id='by_btn' href='#Buynow' class='btn btn-md btn-success' style='padding: 7px 10px;'> <span class='glyphicon glyphicon-shopping-cart'></span> Купить</a>";		
			
			print '<div style="display:inline-block; margin: 0px 10px;">
			<a href="javascript:{}" id="minus" onclick="ch_precount('.$subitem_id.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
      		<a href="javascript:{}" id="plus" onclick="ch_precount('.$subitem_id.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>';
			print '<span id="count_container"><input type="text" class="form-control" id="item_count_'.$subitem_id.'" name="item_count['.$subitem_id.']" value="1" style="width:42px; display: inline-block; "></span></div>';
			print "<div style='display:none;'><span id='to_basket'></span></div>";

			
			
			print '<div style="padding-top: 5px; display:inline-block;"  id="cont_is"> </div>';
			print '</div>';
		print '</div>';

		print '<div style="display:none;">'.$cont.'</div>';

	}
	else{
		foreach ($item['subitem'] as $subitem_id => $subitem) {
			print '<div class="row" style="border-bottom:1px dashed #CCC; margin-top:10px; padding-bottom:10px;" itemprop="offerDetails" itemscope itemtype="http://data-vocabulary.org/Offer">';
			print '<div class="col-sm-3" style="font-size:16px; padding-top: 5px;" id="subitemvalue_'.$subitem_id.'">';
			print '<input type="hidden" id="item_id_'.$subitem_id.'" value="'.$item['ID'].'">';
			if ($subitem['name']!='') {
				
				print '<span style="color:#333;">'.$subitem['name'].'</span><br>';
				$subname= ', '.$subitem['name'];
				print ' <span style="font-size:13px; color:#999;">'.$subitem_id;
				if ($subitem['value3']!='') {
					print ' / '.$subitem['value3'];
					// $subname.= ' ('.$subitem['value3'].')';
				}
				print '</span>';
			}			
			print '<span id="item_'.$subitem_id.'" style="display:none;">'.$item['brand_name'].' '.$item['name'].$subname.'</span>';
			print '<span id="article_'.$subitem_id.'" style="display:none;">'.$subitem['value3'].'</span>';
			
			print '</div>';
			print '<div class="col-sm-3">';
			if ($subitem['value2']!='') {
				print '<div style="font-size:24px;">
				<meta itemprop="currency" content="RUB" />
				<span id="itemprice_'.$subitem_id.'" itemprop="price">'.$subitem['value2'].'</span> <span style="font-size:20px;">руб.</span></div>';
				print '<div class="old_price">'.$subitem['value1'].' руб.</div>';
				
			}
			else {
				print '<div style="font-size:25px; margin-top: -3px; margin-bottom: -2px;">
				<meta itemprop="currency" content="RUB" />
				<span id="itemprice_'.$subitem_id.'" itemprop="price">'.$subitem['value1'].'</span> <span style="font-size:20px;">руб.</span></div>';
				
			}			
			print '</div>';
			print "<div class='col-sm-6'>";
			print "<a  onclick='buyrightnow(".$subitem_id.")' data-toggle='modal' href='#Buynow' class='btn btn-md btn-success' style='padding: 7px 10px;'> <span class='glyphicon glyphicon-shopping-cart'></span> Купить</a>";
			print '<div style="display:inline-block; margin: 0px 10px;">
			<a href="javascript:{}" onclick="ch_precount('.$subitem_id.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
      		<a href="javascript:{}" onclick="ch_precount('.$subitem_id.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>';
			print '<input type="text" class="form-control" id="item_count_'.$subitem_id.'" name="item_count['.$subitem_id.']" value="1" style="width:42px; display: inline-block; "></div>';
			print "<div style='display:none;'><a href='javascript:{};' id='ache_".$subitem_id."' onclick='buyrightnow(".$subitem_id.")' class='btn btn-xs btn-default'>В корзину</a></div>";
			

			print '<div style="padding-top: 5px; display:inline-block;">';
			if ($subitem['instock']==0) print '<div ><span class="label label-success" itemprop="availability" content="in_stock">В наличии</span></div>';
			if ($subitem['instock']==1) print '<div ><span class="label label-warning" itemprop="availability" content="preorder">Под заказ</span></div>';
			print '</div></div>';


			 

			print '</div>';
		}
	}
		// print '</div>';

		// print "
		// 		<p style='font-size:25px;'>Цена: ".$price." руб.</p>";
		// if ($item['special_price']>0) print '<p>Старая цена: <span style="text-decoration: line-through;">'.$item['price'].' руб.</span></p>';
		/*	
		if ($item['count']>0&&$item['archive']==0) print "<div class='label label-success'>en stock</div>";
		elseif ($item['count']==0&&$item['archive']==0) print "<div class='label label-warning'>sur demande</div>";
		elseif ($item['archive']!=0) print "<div class='label'>pas en stock</div>";
		*/
// 		if ($_POST['buy']){
// 			print 'Товар добавлен в корзину! <br />
// Вы можете сразу перейти к <a href="/shop/order">Оформлению заказа &rarr;</a> или сделать это позже через ссылку в верхней части страницы.';
// 		}
						
// 		else{

// 			if ($item['archive']==1) print "<div class='label label-warning'>No</div>";
// 			else print '					 
// 						<a href="javascript:{};" onclick="putinbasket('.$item['ID'].')" id="ache_'.$item['ID'].'" class="btn btn-large btn-success" >Купить</a> <!-- Total: <input style="width:50px;" type="text" id="count_'.$item['ID'].'" value="1"> -->
// 					<div id="finir" style="display:none; margin-top:10px;"><a class="btn btn-warning"  href="/shop/order">Valider &rarr;</a></div>						
			              
			           
// 				 ';
							
// 		}

		

		print '<div class="prd-desc" id="desc">';
		if ($item['unq']==1) print '<span itemprop="description">'.$item['desc'].'</span>';
		else print '<noindex><span itemprop="description">'.$item['desc'].'</span></noindex>';
		print '</div><div id="desc_open" class="desc-link"><a href="javascript:{};" onclick="showdesc(1);">Раскрыть описание &darr;</a></div><div id="desc_close" class="desc-link"><a href="javascript:{};" onclick="showdesc(2);">Скрыть описание &uarr;</a></div>';
		print '<div class="well" style="margin-top:10px;"><img src="http://www.salonsdirect.com/assets/images/delivery.jpg"> &nbsp; <strong>Бесплатная доставка*</strong> &mdash; от 5000 р.<br>
		<img src="http://www.salonsdirect.com/assets/images/product-listing/tick.jpg"> &nbsp; При заказе до 15:00 &mdash; доставка на следующий день.</div>';
?>
<noindex>
<div class="panel-group" id="accordion">

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseZero">
          *Доставка по Москве и России
        </a>
      </h4>
    </div>
    <div id="collapseZero" class="panel-collapse collapse">
      <div class="panel-body">
      	*Бесплатная доставка по всей России от 5000 р. (не более 1 кг)
<table class="table table-bordered">
<thead>
	<tr>
		<th>Город</th>
		<th>Способ</th>
		<th>Стоимость</th>
		<th>Время</th>
		<!-- <th>Способ оплаты</th> -->
	</tr>
</thead>	
	<tr>
		<td>Москва, МО (не более 25 км от МКАД)</td>
		<td>Курьер</td>
		<td>до 5 000 руб. — 350 руб.<br> от 5 000 руб. — бесплатно</td>
		<td>пн-сб с 10:00 до 20:00 / 1-3 дня</td>
		<!-- <td rowspan="4" valign="middle">Наличными, карты Visa и Master Card, Яндекс.Деньги, по коду через терминал</td> -->
	</tr>
	<tr>
		<td>Cанкт-Петербург</td>
		<td>Курьер</td>
		<td>до 5 000 руб. — 350 руб.<br> от 5 000 руб. — бесплатно</td>
		<td>пн-сб с 10:00 до 18-00 / 2-4 дня</td>
		
	</tr>
	<tr>
		<td rowspan="2" valign="middle">Другие города России</td>
		<td>Почта России 1-ый класс </td>
		<td>до 5 000 руб. — 350 руб.<br> от 5 000 руб. — бесплатно (не более 1 кг)</td>
		<td>5-14 дней</td>
		
	</tr>
	<tr>
		<td>Курьер</td>
		<td>до 5 000 руб. — от 350 руб. (расчитывается индивидуально)<br> от 5 000 руб. — скидка на доставку 350 р. (расчитывается индивидуально)</td>
		<td>2-5 дней</td>
	</tr>
</table>
<strong>Способы оплаты для всех городов: </strong>наличными при получении, карты Visa и Master Card, Яндекс.Деньги, по коду через терминал.
      </div>
    </div>
  </div>
  

<!--
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
          Доставка по Москве
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
        <p>При заказе до 15-00 доставка осуществляется на следующий день.<br>Доставка по городу Москва осуществляется после подтверждения заказа менеджером.</p>
<ol>
<li>Сумма заказа до 5000 руб. — <strong>350 руб.</strong></li>
<li>Сумма заказа от 5000 руб. — <strong>бесплатно</strong></li>
</ol>
<p>Время доставки в удобный для вас час:</p>
<ul>
<li>понедельник–пятница<br> – с 10:00 до 21:00</li>
<li>суббота<br> – с 10:00 до 18:00;</li>
</ul>
<p>Оплата наличными курьеру.</p>
      </div>
    </div>
  </div>
-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
          Самовывоз
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
        <p>Вы можете самостоятельно забрать заказанный товар в одном из наших пунктов самовывоза <strong>в Москве</strong>:</p>
        <ol>
<li><strong>м. Рязанский проспект</strong><br>
<ul>
<li>Рязанский проспект, дом 52а (52к2)</li>
<li>с понедельника по пятницу с 11:00 до 21:00, в субботу с 12:00 до 17:00</li>
<li>Общефедеральные праздники и воскресенье – выходной. У входа плакат Expressm24.</li>
<li>Заказы от 1000 р. бесплатно, до 1000 р. – самовывоз 50 р.</li>
<li>Оплата наличными</li>
</ul>
</li>
<li><strong>м. Нагатинская</strong><br>
<ul>
<li>Варшавское шоссе, дом 28а</li>
<li>с понедельника по пятницу с 11 до 18-30</li>
<li>Общефедеральные праздники и суббота и воскресенье – выходной</li>
<li>Самовывоз – бесплатно</li>
<li>Оплата наличными</li>
</ul>
</li>
</ol>
      </div>
    </div>
  </div>
<!--
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
          Доставка по России - от 280 руб.
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse">
      <div class="panel-body">
        <ol>
<li>Транспортной компанией СДЭК.
<ul>
<li>В течение 2-3 дней после подтверждения заказа менеджером.</li>
<li><strong>Стоимость:</strong> от 280 р. (Расчет стоимости производиться исходя от расстояния и веса покупки, по тарифу “Экспресс лайт дверь-дверь”)</li>
<li>Оплата наличными при получении</li>
</ul>
</li>
<li>Почтой России
<ul>
<li>Доставка Почтой России все отправления производятся 1-ым классом, ожидания заказа от 7 дней (сроки устанавливает ФГУП «Почта России» тел. для справок по доставке 8-800-2005-888). </li>
<li>Сразу после отправки покупателю сообщается номер посылки. С помощью сервиса отслеживания почтовых отправлений вы можете в любой момент узнать, где находится посылка.</li>
<li>Сумма заказа до 5000 руб. – <strong>350 руб.</strong>, если вес заказа не более 0,5 кг ( посылки более 0,5 кг обсуждается индивидуально с менеджером)</li>
<li>Сумма заказа от 5000 руб. — <strong>бесплатно</strong>, если вес заказа не более 0,5 кг ( посылки более 0,5 кг обсуждается индивидуально с менеджером)</li>
</ul>
</li>
</ol>
      </div>
    </div>
  </div>
-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
          Оплата
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body">
        <ol>
<li>Оплата наличными при получении заказа</li>
<li>Оплата банковскими картами Visa, MasterCard или Яндекс.Деньгами</li>
<li>Банковский перевод (предоплата)<br>
Вам на e-mail будет выслан заполненный бланк для оплаты, который необходимо оплатить в течение 3 дней. Желательно выслать копию (фото или сканированную) квитанции об оплате на электронный адрес zakaz@europrofcosmetic.ru Отгрузка заказа будет осуществлена после поступления денег на наш расчетный счет (через 2-3 дня после оплаты). Обратите внимание, банк взимает комиссию за перевод денежных средств, размер комиссии устанавливается банком.</li>
<li>Безналичный расчет для юридических лиц<br>
Юридические лица могут оплатить заказ по безналичному расчёту, перечислив сумму заказа со своего расчетного счёта на наш расчётный счёт. Для этого достаточно выслать реквизиты и заказ на электронную почту <strong>zakaz@europrofcosmetic.ru</strong></li>
</ol>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
          Возврат товара
        </a>
      </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse">
      <div class="panel-body">
        <p>В соответствии с Законом о защите прав потребителей РФ, статьей 26.1. от 21.12.2004 № 171-ФЗ, Покупатель в праве отказаться от товара или обменять в любое время до его передачи курьером, а после передачи в течение 7 дней, при условии полной сохранности товарного вида, всех этикеток и документов, подтверждающих факт покупки.</p>
        <p>В течение этого периода Покупатель обязан известить о своём решении менеджера магазина, письменно по адресу <strong>zakaz@europrofcosmetic.ru</strong> или по телефону <strong>+7 (495)517-73-80</strong></p>
        <p>Стоимость товара подлежит возврату Покупателю в течение 10 дней с момента получения Интернет-магазином товара от Покупателя и письменного требования (излагается в заявлении в свободной форме по эл. адресу) о возврате товара и денежных средств.</p>
        <p>При возврате товара, интернет-магазин возмещает только стоимость товара, при этом стоимость доставки товара возврату не подлежит.</p>
        <p><strong>Не подлежат возврату</strong> (Постановление Правительства РФ от 20.10.1998 №1222, 06.02.2002 г. № 81):</p>
        <ul>
<li>Парфюмерно-косметические товары</li>
<li>Чулочно-носочные и трикотажные бельевые изделия</li>
<li>Предметы личной гигиены (заколки, расчёски, бигуди для волос, шиньоны и другие аналогичные товары)</li>
<li>Непериодические издания (книги, брошюры, альбомы, картографические и нотные издания, листовые издания, календари, буклеты, издания, воспроизведенные на технических носителях информации)</li>
</ul>
      </div>
    </div>
  </div>  
</div>
</noindex>
<span itemprop="review" itemscope itemtype="http://data-vocabulary.org/Review-aggregate"><span itemprop="rating">5</span> на основе <span itemprop="count"><? print ($item['ID']-1256); ?></span> отзывов</span>
<?		

		$fURL='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
?>
<script type="text/javascript">
function showdesc(act){
	if (act==1){
		document.getElementById("desc").style.overflow="none";
		document.getElementById("desc").style.height="auto";
		document.getElementById("desc_open").style.display="none";
		document.getElementById("desc_close").style.display="block";
	}
	else if (act==2){
		document.getElementById("desc").style.overflow="hidden";
		document.getElementById("desc").style.height="100px";
		document.getElementById("desc_open").style.display="block";
		document.getElementById("desc_close").style.display="none";
	}
}
</script>
<h4>Смотрите также:</h4>
<div class="row">
<?
$item_id=$URL_PARTS[2];
$sql="SELECT ID FROM shop_catalog WHERE ID > $item_id ORDER BY ID ASC LIMIT 2";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$itms[]=$row['ID'];
}
$sql="SELECT ID FROM shop_catalog WHERE ID < $item_id ORDER BY ID DESC LIMIT 1";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$itms[]=$row['ID'];
}

foreach ($itms as $id) {
	$ITEM=get_item($id);
	$SUBITEM=current($ITEM['subitem']);
	$IMG=current($ITEM['image']);
print '
<div class="col-sm-4 product">
 <div class="product-preview">
  <a href="/catalog/products/'.$id.'">
   <img src="/products/'.$IMG['filename'].'_small.jpg" title="'.$ITEM['name'].'" class="img-responsive">
  </a>     
 </div>';
  
// print ' <div class="product-desc">     
//   <div class="product-price">Цена: <span class="price_count" id="itemprice_261">'.$SUBITEM['value1'].'</span> р.   
//   </div>
//   <h4><a href="/catalog/products/'.$id.'"><strong>'.$ITEM['brand_name'].'</strong> '.$ITEM['name'].'  <span style="color:#999; font-style:italic;">'.$SUBITEM['name'].'</span></a></h4>
//  </div>
// </div>
// ';

// $title='<strong>'.$ITEM['brand_name'].'</strong> '.$ITEM['name'].'  <span style="color:#999; font-style:italic;">'.$SUBITEM['name'].'</span>';

if ($ITEM['brand_name']!='') {
	$f_name=$ITEM['brand_name'].' - ';
	$ITEM['brand_name']='<strong>'.$ITEM['brand_name']."</strong> ";
	$p_name=$ITEM['brand_name'].' - ';
}
$title=$ITEM['brand_name']; //.'('.$ITEM['brand_id'].')';
if ($ITEM['name']!=''&&$ITEM['name']!=' '){
	$title.=$ITEM['name'];
	$f_name.=$ITEM['name'];
	$p_name.=$ITEM['name'];
}

if ($subitem['value']!=''){
	$itm_select.='<option value="'.$subitem_id.'">'.$subitem['value'].'</option>';

	// $title.=' <span style="color:#999; font-style:italic;">('.$subitem['value'].')</span>';
	$p_name.=' ('.$subitem['value'].')';
	$f_name.=', '.$subitem['value'];
} 

print '<input type="hidden" id="item_id_'.$SUBITEM['ID'].'" value="'.$id.'">';
print "
	<div class='product-desc' >					
		<div class='product-price'>Цена: <span class='price_count' id='itemprice_".$SUBITEM['ID']."'>".$SUBITEM['value1']."</span> р. <br>
			<a  onclick='buyrightnow(".$SUBITEM['ID'].")' data-toggle='modal' href='#Buynow' class='btn btn-mini btn-success'>Купить</a>";
print '<div style="display:inline-block; margin: 0px 10px;">
			<!-- <a href="javascript:{}" onclick="ch_precount('.$SUBITEM['ID'].',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> -->
      		<!-- <a href="javascript:{}" onclick="ch_precount('.$SUBITEM['ID'].',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a> -->';
			print '<input type="text" class="form-control" id="item_count_'.$SUBITEM['ID'].'" name="item_count['.$SUBITEM['ID'].']" value="1" style="width:42px; display: inline-block; "></div>';
			print "<div style='display:none;'><a href='javascript:{};' id='ache_".$SUBITEM['ID']."' onclick='buyrightnow(".$SUBITEM['ID'].")' class='btn btn-xs btn-default'>В корзину</a></div>";

			// <a href='javascript:{};' id='ache_".$SUBITEM['ID']."' onclick='buyrightnow(".$SUBITEM['ID'].")' class='btn btn-sm btn-default'>В корзину</a>
			// <input type='hidden' id='count_".$SUBITEM['ID']."' value='1'>";

if ($SUBITEM['special_price']>0) print '<div class="old_price">Старая цена: '.$SUBITEM['price'].' р.</div>';

print "</div>";
print '<div style="display:none;" id="item_'.$SUBITEM['ID'].'">'.$f_name.'</div>';
print '<span id="article_'.$SUBITEM['ID'].'" style="display:none;">'.$SUBITEM['value3'].'</span>';
print "<h4><a href='/catalog/products/".$id."' >".$title."</a></h4>";


print "</div>";			
print "</div>";


}
?>	
</div>

<div style="height:40px; margin-top:40px;">
	
<!-- Put this div tag to the place, where the Like block will be -->
<div id="vk_like" style="float:left; margin-right:10px;"></div>

<script type="text/javascript">
VK.Widgets.Like("vk_like", {type: "button"});
</script>

<div  style="float:left;" class="fb-like" data-href="<? print $fURL; ?>" data-width="200" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
	
</div>

<!-- Put this div tag to the place, where the Comments block will be -->
<!-- <div id="vk_comments" style="width:100%"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 15, width: "500", attach: "*"});
</script>
 -->
<?		
		if (count($item['params'])!=0){
			//print "<h4>title</h4>";
			print "<table class='table table-condensed'>";
			foreach ($item['params'] as $param_id =>$param){
				if (($param_id==6209||$param_id==6942)&&$param['value']!='') $param['value'].=' руб.';
				
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

 


		// print '<p align="center"><img src="/logos/'.$item['brand_logo'].'" width="150" /></p>';

		print '</div>';		
		print "</div>";
		if ($item['seo_title']!='') print '<h2>'.$item['seo_title'].'</h2>';
		print $item['seo_text'];
	
	}
	elseif ($URL_PARTS[1]=='order')	{
		print '<div class="col-sm-12">';

		print order();
	}
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
		
		// print_r($_POST['search_word']);
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
			// else print 'x;';
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
				$subcat_str.='<a href="/catalog_'.$subcat['slug'].'.html">'.$subcat['name'].'</a>';

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
			// $brands=get_category_brands($types);
		}

		// $search_params=get_search_params($types);

	  	if($view_type||$view_brand||$_POST['is_search']){
	  		if ($_POST['search_word']!='') $new_title='"'.$_POST['search_word'].'" <small>Результаты поиска</small>';
			// foreach ($search_params as $param_id => $param) {
			// 	$srch[$param_id]=$param['name'].': <select name="srch['.$param_id.']"><option value="">Tous</option>';
			// 	foreach ($param['values'] as $value) {
			// 		$sl='';
			// 		if ($_SESSION['fltr']['params'][$param_id]==$value) $sl=' selected="selected" ';
			// 		if ($value!='') $srch[$param_id].='<option value="'.$value.'" '.$sl.'>'.$value.'</option>';
			// 	}

			// 	$srch[$param_id].='</select> ';
			// }

			//print_r($srch);

			// if ($types) $prices=get_category_prices($types);
			//print_r($prices);
			// $prc='Цена: <select name="srch_price">
			// <option value="">Tous</option>';
			// foreach ($prices as $value) {
			// 	$chk='';
			// 	if ($_SESSION['fltr']['price']==$value) $chk=' selected="selected" ';
			// 	$prc.='<option value="'.$value.'" '.$chk.'>'.$value.' - '.($value+20).' руб.</option>';
			// }
		
		
			// $prc.='</select> ';

			$ttl='';
			foreach ($types as $type_id => $type) {
				$ttl.='<a href="/catalog_'.$type['slug'].'.html">'.$type['name'].'</a> ';
			}

			print '<div class="col-xs-4">';
			// print $ttl;
			$fullmenu=get_full_menu($view_type);
			print '<h3 style="font-style: italic; margin-top:0px;">Каталог</h3> <div class="leftmenu"><ul>'.$fullmenu.'</ul></div>';
			// $brands=get_active_brands();

			$fullmenu_brands=get_full_menu($view_brand,2);

			$brand_nav='<h3 style="font-style: italic;">Бренды</h3><div class="leftmenu"><ul>'; //' <ul style="margin-left:25px;" class="list-unstyled">';
			$brand_nav.= $fullmenu_brands;
			$active='';
			// foreach ($brands as $id => $brand) {
			// 	if ($id==$view_brand) $active='class="active"';
			// 	// $brand_nav.='<li '.$active.'><a href="/shop/'.$brand['slug'].'"><img src="/logos/'.$brand['logo'].'" width="60"></a><br/><a href="/shop/'.$brand['slug'].'">'.$brand['name'].'</a></li>';
			// 	$brand_nav.='<li '.$active.'><a href="/catalog_'.$brand['slug'].'.html">'.$brand['name'].'</a></li>';
			// 	$active='';
			// }
			$brand_nav.='</ul></div>';

			print $brand_nav;

			// FILTER
			print'</div>
			<div class="col-xs-8">';		
			print '<div style="margin-top:0px; font-size: 28px;" class="myfont">'.$new_title.'</div>';

			//END OF FILTER

			if ($view_brand) $cat_id=$view_brand;
			elseif ($view_type) $cat_id=$view_type;

			if (($_GET['page']==1 || !$_GET['page'])&&$cat_id){
				$sbcat=get_subcat($cat_id);			
				$x=0;
				$sbct='';
				foreach ($sbcat as $subcat_id => $subcat) {
					if ($x==0) $sbct.='<div class="row">';
					// $filename=get_cat_pict($subcat_id);
					$sbct.= '<div class="col-sm-4"><a href="/catalog_'.$subcat['slug'].'.html">'.$subcat['name'].'</a></div>';
					$x++;
					if ($x==3) {
						$sbct.='</div>';
						$x=0;
					}
				}
				if ($x!=0) $sbct.= '</div>';
				
				if ($sbct!='')  print '<div class="subcats">'.$sbct.'</div>';
			}

			if ($items_count>15&&!$_POST['search_word']){
				$page_nav='<ul class="pagination">';
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
			$j=0;
			
			// print_r($items);


			///////
			/// PRICE ORDER

			foreach ($items as $item_id => $item) {
				if ($item['special']==1) {
					$special[$item_id]=$item;
					unset($items[$item_id]);
				}
			}

			///////

			if ($special) $items = $special + $items;


			foreach ($items as $item_id => $item){
				$i++;
				
				if((($i>($page-1)*15)&&($i<=($page)*15))||$_POST['search_word']!=''){
				  if ($item['list']==1){
					if ($j==0) print '<div class="row">';						
					print "<div class='col-xs-4 product'>";				  	
					$itm_select='';

					$aa=1;
					$itm_subitem='';
					foreach ($item['subitem'] as $subitem_id => $subitem) {											
						if ($item['image'][$subitem_id]!='') $image=$item['image'][$subitem_id];
						elseif ($item['image'][0]!='') $image=$item['image'][0];
						else $image='';

					 
				 
						if ($subitem['special_price']>0) $price=$subitem['special_price'];
						else $price=$subitem['price'];

						if ($aa==1) {
							$itm_img= $image;
							$itm_subitem_id=$subitem_id;
							$itm_subitem_price=$price;
							$itm_subitem_oldprice=0;
							if ($subitem['special_price']>0) $itm_subitem_oldprice=$subitem['price'];
						}						
						
						if ($item['brand_name']!='') {
							$f_name=$item['brand_name'].' - ';
							$item['brand_name']='<strong>'.$item['brand_name']."</strong> ";
							$p_name=$item['brand_name'].' - ';
						}
						$title=$item['brand_name']; //.'('.$item['brand_id'].')';
						if ($item['name']!=''&&$item['name']!=' '){
							$title.=$item['name'];
							$f_name.=$item['name'];
							$p_name.=$item['name'];
						}

						if ($subitem['value']!=''){
							$itm_select.='<option value="'.$subitem_id.'">'.$subitem['value'].'</option>';

							// $title.=' <span style="color:#999; font-style:italic;">('.$subitem['value'].')</span>';
							$p_name.=' ('.$subitem['value'].')';
							$f_name.=', '.$subitem['value'];
						} 

						$itm_subitem.= '<input type="hidden" id="item_id_'.$subitem_id.'" value="'.$item_id.'">';
						$itm_subitem.= "<span id='itemprice_".$subitem_id."'>".$price."</span><input type='hidden' id='count_".$subitem_id."' value='1'>";

						if ($subitem['special_price']>0) $itm_subitem.= '<span id="hid_oldprice_'.$subitem_id.'">'.$subitem['price'].'</span>';


						
						$itm_subitem.= '<span id="item_'.$subitem_id.'">'.$f_name.'</span>';
						$itm_subitem.= '<span id="article_'.$subitem_id.'">'.$subitem['value3'].'</span>';
						$itm_subitem.= "<span id=''>".$title."</span>";
						$aa=0;
					}

						print "
							<div class='product-preview' id='show_img_".$item_id."'>
								<a href='/catalog/products/".$item_id."'>
								<img src='/products/".$itm_img."_small.jpg' title='".$item['name']."' class='img-responsive'  />
								</a>					
							</div>
						";
						
					
						print "
							<div class='product-desc' >					
								<div class='product-price'>Цена: <span class='price_count' id='show_price_".$item_id."''>".$itm_subitem_price."</span> р. <br>
								<a onclick='buyrightnow(".$itm_subitem_id.")' data-toggle='modal' id='by_btn_".$item_id."'' href='#Buynow' class='btn btn-mini btn-success'>Купить</a>";

			print '<div style="display:inline-block; margin: 0px 10px;">
			<a href="javascript:{}" id="minus_'.$item_id.'" onclick="ch_precount('.$itm_subitem_id.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
      		<a href="javascript:{}" id="plus_'.$item_id.'" onclick="ch_precount('.$itm_subitem_id.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>';
			print '<span id="count_container_'.$item_id.'"><input type="text" class="form-control" id="item_count_'.$itm_subitem_id.'" name="item_count['.$itm_subitem_id.']" value="1" style="width:42px; display: inline-block; "></span></div>';
			print "<div style='display:none;'><span id='to_basket_".$item_id."'><a href='javascript:{};' id='ache_".$itm_subitem_id."' onclick='buyrightnow(".$itm_subitem_id.")' class='btn btn-sm btn-default'>В корзину</a></span></div>";

						print '<div class="old_price" id="show_oldprice_'.$item_id.'">';
						if ($itm_subitem_oldprice) print 'Старая цена: '.$itm_subitem_oldprice.' р.';
						print '</div>';

						print "</div>";
						
						print "<h4><a href='/catalog/products/".$item_id."' >".$title."</a></h4>";
						print '<select onchange="change_y('.$item_id.')" id="subitem_id_'.$item_id.'">'.$itm_select.'</select>';

						
						print "</div>";		
						print '<div style="display:none;">'.$itm_subitem.'</div>';
						print "</div>";					
						$j++;

						if ($j==3) {
							print '</div>';
							$j=0;
						}



				  }
				  else {
					foreach ($item['subitem'] as $subitem_id => $subitem) {						
						if ($j==0) print '<div class="row">';						
						print "<div class='col-xs-4 product'>";
						if ($item['image'][$subitem_id]!='') $image=$item['image'][$subitem_id];
						elseif ($item['image'][0]!='') $image=$item['image'][0];
						else $image='';
						print "
							<div class='product-preview'>
								<a href='/catalog/products/".$item_id."'>
								<img src='/products/".$image."_small.jpg' title='".$item['name']."' class='img-responsive'  />
								</a>					
							</div>";
					 
				 
						if ($subitem['special_price']>0) $price=$subitem['special_price'];
						else $price=$subitem['price'];
						
						// $price=substr($price,0,-3).' '.substr($price,-3);
						// if ($item['brand_name']!='') $item['brand_name']='<div class="myfont">'.$item['brand_name']."</div>";
						
						if ($item['brand_name']!='') {
							$f_name=$item['brand_name'].' - ';
							$item['brand_name']='<strong>'.$item['brand_name']."</strong> ";
							$p_name=$item['brand_name'].' - ';
						}
						$title=$item['brand_name']; //.'('.$item['brand_id'].')';
						if ($item['name']!=''&&$item['name']!=' '){
							$title.=$item['name'];
							$f_name.=$item['name'];
							$p_name.=$item['name'];
						}

						if ($subitem['value']!=''){
							$title.=' <span style="color:#999; font-style:italic;">('.$subitem['value'].')</span>';
							$p_name.=' ('.$subitem['value'].')';
							$f_name.=', '.$subitem['value'];
						} 

						print '<input type="hidden" id="item_id_'.$subitem_id.'" value="'.$item_id.'">';
						print "
							<div class='product-desc' >					
								<div class='product-price'>Цена: <span class='price_count' id='itemprice_".$subitem_id."'>".$price."</span> р. <br>
									<a  onclick='buyrightnow(".$subitem_id.")' data-toggle='modal' href='#Buynow' class='btn btn-mini btn-success'>Купить</a>";
print '<div style="display:inline-block; margin: 0px 10px;">
			<a href="javascript:{}" onclick="ch_precount('.$subitem_id.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
      		<a href="javascript:{}" onclick="ch_precount('.$subitem_id.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>';
			print '<input type="text" class="form-control" id="item_count_'.$subitem_id.'" name="item_count['.$subitem_id.']" value="1" style="width:42px; display: inline-block; "></div>';
			print "<div style='display:none;'><a href='javascript:{};' id='ache_".$subitem_id."' onclick='buyrightnow(".$subitem_id.")' class='btn btn-xs btn-default'>В корзину</a></div>";

						// print		"<a href='javascript:{};' id='ache_".$subitem_id."' onclick='buyrightnow(".$subitem_id.")' class='btn btn-sm btn-default'>В корзину</a>
						// 			<input type='hidden' id='count_".$subitem_id."' value='1'>";

						if ($subitem['special_price']>0) print '<div class="old_price">Старая цена: '.$subitem['price'].' р.</div>';

						print "</div>";
						print '<div style="display:none;" id="item_'.$subitem_id.'">'.$f_name.'</div>';
						print '<span id="article_'.$subitem_id.'" style="display:none;">'.$subitem['value3'].'</span>';
						print "<h4><a href='/catalog/products/".$item_id."' >".$title."</a></h4>";

						
						print "</div>";			
						print "</div>";
						$j++;

						if ($j==3) {
							print '</div>';
							$j=0;
						}

					}
				  }
				}
			}
			if ($j!=0) print '</div>';		
	 	}
	  	if (!$view_type&&!$view_brand&&!$_POST['is_search']) {
		  	$types=get_full_menu0();
			$brands=get_active_brands();
?>
	<div class="col-sm-12 catalog_menu">
		<h1>Каталог</h1>
		<ul>
<? print $types; ?>
</ul>
	</div>
	
<?		
			print '</div>';
			print '<h2>Бренды</h2>';
			print '<div class="row">';
			foreach ($brands as $brand) {
				print '<div class="col-sm-2" align="center"><a href="/catalog_'.$brand['slug'].'.html"><img src="/logos/'.$brand['logo'].'" border="0" width="130"><!--<br>'.$brand['name'].'--></a></div>';
			}
			print '</div>';
		}
	}
?>

<div class="row" style="margin-top:20px;">
	<div class="col-sm-12">
<? print $page_nav; ?>  
	</div>
</div>
<?
		if (!$_GET['page']) {
			if ($view_brand) print get_text($view_brand);
			elseif ($view_type) print get_text($view_type);

			if ($view_brand||$view_type) {
				print '<div style="margin:20px 0px; display:none;"><h3>Отзывы о '.$new_title.'</h3>';
?>
<!-- <div id="vk_comments" style="width:100%"></div> -->
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 15, width: "500", attach: "*"});
</script>
</div>
<?
			}			
		}
?>
		</div>
<? //get_sidebar(); ?>
	</div>
</div>




<?php  get_footer(); ?>