<noindex>
  <!-- Modal -->
  <div class="modal fade" id="Buynow" tabindex="-1" role="dialog" aria-labelledby="BuynowLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Оформление заказа</h4>
        </div>
        <div class="modal-body">

          <form class="form-horizontal" role="form" id="backcall_form" action="/catalog/order#send" method="post" onsubmit="return Validate(2);">
            <input type="hidden" name="gobuy" value="1"> 
            <div class="row" style="margin-bottom:15px;">
              <div class="col-md-12">
                <strong>Ваш заказ:</strong>
<?
$order_items=get_order_items();
$item_list='';
$j=0;
$total_items=0;
foreach ($order_items as $j => $item) {
      $total_items++;
      $item_list.='<tr>
      <td bgcolor="#FFFFFF">'.($j+1).'</td>
      <td>
      <input type="hidden" name="id['.$j.']" value="'.$item['ID'].'">
      <input type="hidden" name="name['.$j.']" value="'.$item['name'].'">
      <input type="hidden" name="article['.$j.']" value="'.$item['article'].'">
      <input type="hidden" name="items_price['.$j.']" id="ttl2_'.$j.'" value="'.$item['price']*$item['count']*$item['discount'].'" >
      <input type="hidden" name="item_discount['.$j.']" id="item_discount_'.$j.'" value="'.$item['discount'].'" >
      <span style="display:none;" id="item_price_'.$j.'">'.$item['price'].'</span>

      '.$item['name'].'</td>
      <td align="center" width="88">      
      <a href="javascript:{}" onclick="ch_count('.$j.',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> 
      <a href="javascript:{}" onclick="ch_count('.$j.',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a>
        <input type="text"  onchange="ch_prix('.$j.')"  class="form-control" id="count_'.$j.'" name="count['.$j.']" value="'.$item['count'].'" style="width:42px;">        
      </td>
      <td align="right" width="70" ><span id="ttl_'.$j.'">'.$item['price']*$item['count']*$item['discount'].'</span> р.</td>
      </tr>';
      $total+=$item['price']*$item['count']*$item['discount'];  
}
$my_order='
      <input type="hidden" id="total_items" value="'.$total_items.'">
      <table  class="table table-condensed" id="order_list">';
      $my_order.=$item_list;            
      $my_order.='</table>';
      $my_order.='<div align="right" style="margin-top:-15px; margin-bottom:15px;">
      <div style="float:left;"><a href="/catalog/order/"><span class="glyphicon glyphicon-pencil"></span> Редактировать заказ</a></div>
      Итого: <strong><input type="hidden" name="total_price" id="total_price" value="'.$total.'"><span id="total_prix">'.$total.'</span> р.</strong></div>';
print $my_order;      
?>                
              </div>
            </div>
            <div class="form-group" style="margin-bottom:20px;">
              <label for="inputName_backcall" class="col-sm-3 control-label">Промокод:</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="promocode" id="promocode" placeholder="Промокод (если есть)" value="<? print $_SESSION['promocode']; ?>">
              </div>
              <div id="promotext" style="display:none;">Ваша скидка: 20% на косметику.</div>
            </div>

            <div class="form-group">
              <label for="inputName_backcall" class="col-sm-3 control-label">Ваше имя:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="username" id="inputName_2" placeholder="Введите имя">
              </div>
            </div>
            <div class="form-group">
              <label for="inputContant_backcall" class="col-sm-3 control-label">Телефон:</label>
              <div class="col-sm-8">
                <div style="display:inline-block;">+7</div> <input type="text" style="display:inline-block; width:94%;" class="form-control" name="phone" id="inputContact_2" placeholder="9275556677">
              </div>
            </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-9">
                  <button type="submit" class="btn btn-success btn-lg myfont" >Отправить заказ</button> или 
                  <button type="button" class="btn btn-default btn-sm" onclick="" id="addtobusket_and_close" data-dismiss="modal" aria-hidden="true" >Продолжить</button>
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
</noindex>



    <div class="footer">
      
      <p><strong>Телефоны:</strong> Москва <strong>+7 (499) 322-10-17</strong>, Санкт-Петербург: <strong>+7 (812) 426-14-61</strong>, или РФ <strong>8 800 500-05-79</strong></p>
      <p><strong>Время работы:</strong> Пн-Сб, с 10:00 до 19:00</p>
      <p><strong>E-mail:</strong> <a href="mailto:zakaz@europrofcosmetic.ru">zakaz@europrofcosmetic.ru</a></p>
      <p>2010 - <? print date("Y"); ?> &copy; EuroProfCosmetic &mdash; интернет-магазин профессиональной косметики и оборудования.</p>
      <p><a href="http://rbdi.ru/" style="color:#FFF;">Создание сайта</a> - <strong>RBDI</strong></p>


    </div>

  <!-- Modal -->
  <div class="modal fade" id="ordersent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Ваша заявка</h4>
        </div>
        <div class="modal-body">
          <div id="backcall_success" align="center">
            <big>Ваша заявка отправлена!</big><br/>
            Скоро мы свяжемся с вами уточнения деталей.
            <div style="margin-top:10px;"><button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button></div>
          </div>
        </div>
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->   


<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = '118262';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!-- {/literal} END JIVOSITE CODE -->



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-hover-dropdown.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/js.js"></script>



<? // wp_footer(); ?>


<!--LiveInternet counter--><script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//--></script><!--/LiveInternet-->

<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter20407267 = new Ya.Metrika({id:20407267, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/20407267" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

<script type="text/javascript" id="leadhacker-embeder-c40470882d2a4ee594a29e78302780d8" class="leadhacker-async-script-loader">
  var tracker_code = 'c40470882d2a4ee594a29e78302780d8';
  
  (function(w,d,c) {
    function async_load(){
      var t = new Date();t = (t.getMonth() + 1).toString() + t.getDate().toString() + t.getFullYear();
      var s = d.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.src = (d.location.protocol == 'https:' ? 'https:' : 'http:') + '//www.leadhacker.ru/t/' + t + '/lhw.js'
      var embedder = d.getElementById('leadhacker-embeder-c40470882d2a4ee594a29e78302780d8');
      ( (embedder == null) || (typeof embedder === "undefined") ) ? d.body.appendChild(s) : embedder.parentNode.insertBefore(s, embedder);
    }
    if (w.attachEvent)
      w.attachEvent('onload', async_load);
    else
      w.addEventListener('load', async_load, false);
  })(window, document, 'leadhacker_callbacks');
</script>

<!-- ucall.im -->
<!-- <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script> var jQ = jQuery.noConflict();</script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<a id="callback" data-token="9176d5e5a33b3bb0a66c84dd7fdbd270" href="#ucall" style="display:none;">Обратный звонок</a>
<script src="//ucall.im/callback/js/callback.js" type="text/javascript"></script> -->
<!-- /ucall.im -->

  </body>
</html>