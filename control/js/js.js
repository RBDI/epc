    //TinyMCE Visual HTML Editor
tinyMCE.init({
	mode : "textareas",
	language : "ru",
	theme : "advanced",
	editor_selector : "mceSimple",
	plugins : "table,fullscreen,paste,safari,inlinepopups,style",
	theme_advanced_buttons1 : "formatselect,fontselect,fontsizeselect,bold,italic,underline,forecolor,backcolor,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,link,unlink,|,code,|,fullscreen",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	// Example content CSS (should be your site CSS)
	// content_css : "/TMPL/forprice.css",
});
function confirmDelete() {
    return confirm("Вы подтверждаете удаление?");
}


function send_sms(ID){
	var is,num,text;
	is=confirm("Отправляем?");
	if (is) {
		num=$('#sms_phone_'+ID).val();
		text=$('#sms_text_'+ID).val();
		$.post("http://europrofcosmetic.ru/control/send_sms.php", {phone:num,text:text},
		function(data){
			// alert (data);
			 
			
			 
        });
        $('#sms_text_'+ID).val('');	
        $('#sms_btn_'+ID).val('Отправлено!');
         setTimeout("$('#sms_btn_"+ID+"').val('Отправить SMS');",2000);
	}
}

function find_item(){
	var text=$('#add_subitem_id').val();
	$.post("http://europrofcosmetic.ru/control/get_item.php", {subitem:text},
		function(data){
			$('#add_items_table').html(data);
        });	
}

function delorditm (ID) {	
	var is;
	is=confirm("Удалить?");	
	if (is) {
		
		$.post("http://europrofcosmetic.ru/control/del_order.php", {delorderid:ID},
		function(data){ 
			$('#item_tr_'+data).remove();
		});
    }	
}

function add_item (subitem_id,order_id) {	
	var item_count= $('#item_count_'+subitem_id).val();
	var item_price= $('#item_price_'+subitem_id).text();
	var item_price2= $('#item_price2_'+subitem_id).text();
	$.post("http://europrofcosmetic.ru/control/get_item.php", {subitem_id:subitem_id, order_id:order_id, item_count:item_count, item_price:item_price, item_price2:item_price2},
		function(data){
			// $('#items_table').append(data);
			$('#total_tr').before(data);
			refresh_price();
        });	
}

function refresh_price () {
	$('#order_total').html(0);
	$('.item_tr').each(function(i,elem) {
		var item_price = $(elem).find('.item_price').html();
		var item_discount = $(elem).find('.item_discount').val();
		if (item_discount) item_discount=(100-item_discount)/100;
		else item_discount=1;
		var item_count = $(elem).find('.item_count').val();
		var item_total_price = item_price*item_count*item_discount;
		// alert(item_price+' '+item_count+' '+item_total_price);
		$(elem).find('.item_total_price').html(item_total_price);
		var total=parseInt($('#order_total').html());

		total+=parseInt(item_total_price);
		$('#order_total').html(total);
	});
	var total=parseInt($('#order_total').html());
	var delivery_price=parseInt($('#delivery_price').val());
	if (delivery_price>0){
		total+=delivery_price;
		$('#order_total').html(total);
	}
	// var item_price=$('.item_price');
	// var item_count=$('.item_count');
	// var item_price=$('.item_total_price');
}

function get_items_list (order_id) {

	var string = $('#add_subitem_name').val();
	if (string.length>2){
	$.post("http://europrofcosmetic.ru/control/get_item.php", {string:string, order_id:order_id},
		function(data){
			$('#items_list').html(data);
        });
	}
	else {
		$('#items_list').html('');
	}
}

function save_order(order_id){
	var data = $('#form_order_'+order_id).serialize();
	$('#save_order_button').val('Сохраняем...');
	$.ajax({
          type: 'POST',
          url: 'http://europrofcosmetic.ru/control/save_order.php',
          data: data,
          success: function(data) {
          	// alert(data);
            $('#save_order_button').val('Сохранено!');
            // get_order(order_id);
            setTimeout("$('#save_order_button').val('Сохранить')",2000);
          },
          error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });

}

function edit_price(ID){
	var a;
	a=document.getElementById("price_count_"+ID).innerHTML;
	document.getElementById("price_"+ID).innerHTML='<input type="text" size="5" name="price_value_'+ID+'" id="price_value_'+ID+'" value="'+a+'" onBlur="set_price('+ID+')">';
	document.getElementById("price_value_"+ID).focus();
}

function get_order(ID){
	var order_id=$('#order_id').val();
	var admin_id=$('#admin_id').val();
	if (order_id) $('#showrow'+order_id).remove();
	$("#order"+order_id).show();

	$('#order_id').val(ID);
	$.post("http://europrofcosmetic.ru/control/get_order2.php", {id:ID, manager:admin_id},
		function(data){
			if (data!=''){
				var order_id=$('#order_id').val();
          		$("#order"+order_id).hide();
          		// alert(data);
          		$("#order"+order_id).after(data);
          	}
        });
}
function hide_order(ID){
	$('#showrow'+ID).remove();
	$("#order"+ID).show();
}

function set_price(ID){
	var price_value;
	
	price_value=document.getElementById("price_value_"+ID).value;
    
    jQuery.post("http://europrofcosmetic.ru/control/ajax.php", { subitem_id: ID, price: price_value }, 
          function(data){          	
          	var answer = eval(data);          	
          	document.getElementById("price_"+answer.subitem_id).innerHTML='<a href="javascript:{};" onclick="edit_price('+answer.subitem_id+');" id="price_count_'+answer.subitem_id+'">'+answer.price+'</a>';            
          });	
	
}