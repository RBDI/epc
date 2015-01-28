      <div class="footer">
        <p>L'abus d'alcool est dangereux pour la santé. A consommer avec modération. Vente interdite aux mineurs. Photographie non contractuelle. Les indications ci-dessus sont données à titre d'information. Elles ne sont pas forcément exhaustives et ne sauraient se substituer aux informations figurant sur l'emballage du produit qui font seules foi, et auxquelles vous êtes invités à vous reporter, notamment en cas d'intolérance alimentaire.</p>

          Les prix affichés sont indiqués en Euros TTC.<br/><a href="/cgv/"> Conditions Générales de Ventes</a></p>
        <div class="underfooter">
        <? print date("Y"); ?> &copy; Baradom &mdash; Livraison de boissons à Lyon <div style="float:right;">Téléphone: 04 78 47 47 47 / E-mail: <a href="mailto:contact@baradom.eu">contact@baradom.eu</a></div>
        </div>        
        
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php bloginfo('template_url'); ?>/js/jquery.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-transition.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-alert.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-modal.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-dropdown.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-scrollspy.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-tab.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-tooltip.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-popover.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-button.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-collapse.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-carousel.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap-typeahead.js"></script>

    <script>
      !function ($) {
        $(function(){
          // carousel demo
          $('#myCarousel').carousel()
        })
      }(window.jQuery)

      function putinbasket(ID){
        var count;
        count=document.getElementById("count_"+ID).value;

        $.post("http://baradom.eu/putinbasket.php", { item_id: ID, item_count: count }, 
          function(data){
            /*alert(data);*/
            document.getElementById("basketcount").innerHTML=data;            
        });
        var xx;
        xx=0;
        xx=document.getElementById("basketcount").innerHTML;
        xx++;
        document.getElementById("ache_"+ID).innerHTML='Panier: '+xx;
         setTimeout ('document.getElementById("ache_'+ID+'").innerHTML="Acheter"',3000);
        

      }
      function setback(ID){
        document.getElementById("ache_"+ID).innerHTML="Acheter";
      }

      function ch_prix(){
        var count, total,c,p,ttl;
        ttl=0;
        total=0;
        count=document.getElementById("total_items").value;
        for (var i = 1; i <= count; i++) {
          c=document.getElementById("count_"+i).value;
          p=document.getElementById("item_price_"+i).innerHTML;
          ttl=c*p;
          document.getElementById("ttl_"+i).innerHTML=ttl;
          document.getElementById("ttl2_"+i).value=ttl;
          total=total+ttl;          
        };
        document.getElementById("total_prix").innerHTML=total;
        document.getElementById("total_price").value=total;
      }


      $('.tooltip-demo').tooltip({
      selector: "a[data-toggle=tooltip]",
      trigger: "hover",      
      placement: "right"
    })


    
    function Validate()
    {
      var IsValid = true;
      var a,b;
      a=document.getElementById("inputPhone");
      b=document.getElementById("inputMail");
      a.style.background="#FFF";
      b.style.background="#FFF";

      if (a.value == "") {
        a.style.background="#FF9999";        
      }
      if (b.value == "") {
        b.style.background="#FF9999";        
      }

      if (a.value =="" && b.value == ""){
        IsValid = false;        
      }
      
      return IsValid;
    }
    

    </script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42426447-1', 'baradom.eu');
  ga('send', 'pageview');

</script>
<? wp_footer(); ?>
  </body>
</html>