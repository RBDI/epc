$('.thumbnail').click(function(){
	$('.mybody').empty();
	var title = $(this).parent('a').attr("title");
	$('.mytitle').html(title);
	$($(this).parents('div').html()).appendTo('.mybody');
	$('#myModal').modal({show:true});
});

// very simple to use!
$(document).ready(function() {
  $('.js-activated').dropdownHover().dropdown();
});


$( "#promocode" ).change(function() {
  checkpromo();
});

function checkpromo()	{
	var promocode = $("#promocode" ).val();
	$('#promotext').hide();
	$.post("http://europrofcosmetic.ru/checkdapromo.php", {promocode:promocode}, function(data){
		var obj = JSON.parse(data);
		
		for (i = 0; i < obj.a.length; i++) { 
		    // $('#item_price_'+obj.a[i][0]).html($('#item_price_'+obj.a[i][0]).html()*obj.a[i][1]);
		    $('#item_discount_'+obj.a[i]).val(obj.a[i][1]);
		    if (obj.a[i][1]<1) x=1;
		    ch_prix(i);
		}
		if (obj.b==1) $('#promotext').show();
		
		
	});
	
}

function Validate(name)	{
	var IsValid = true;
	var a,b;
		
	a=document.getElementById("inputName_"+name);
	b=document.getElementById("inputContact_"+name);

	a.style.background="#FFF";
	b.style.background="#FFF";

	if (a.value == "") {
		a.style.background="#FF9999";
		IsValid = false;
	}
	if (b.value == "") {
		b.style.background="#FF9999";
		IsValid = false;
	}	  
	return IsValid;
}

function backcall(){
	var isvalid, name, contact;
	isvalid= Validate('backcall');
	if (isvalid) {		
		document.getElementById("backcall_form").style.display="none";
		document.getElementById("backcall_success").style.display="block";

		name=document.getElementById("inputName_backcall").value;
		contact=document.getElementById("inputContact_backcall").value;
		$.post("http://europrofcosmetic.ru/sendmail.php", { nm: name, cntc: contact, type: 1}, function(data){ 	});
		yaCounter20407267.reachGoal('Backcall');
		setTimeout("$('#Backcall').modal('hide')",1000);
		setTimeout("refresh_backcall()",2000);
		return true;
	}
	else{
		return false;
	}
}

function send_order(id){
	var isvalid, name, mail, phone;
	isvalid= Validate(id);
	if (isvalid) {	
		name=document.getElementById("inputName_"+id).value;
		mail=document.getElementById("inputContact_"+id).value;
		phone=document.getElementById("inputPhone_"+id).value;
		$.post("http://europrofcosmetic.ru/sendmail.php", { nm: name, ml: mail, pn:phone, type: 2}, function(data){ });
		// yaCounter20407267.reachGoal('ORDER');		
		$('#ordersent').modal('show');
		setTimeout("$('#ordersent').modal('hide')",3000);
		document.getElementById("inputName_"+id).value='';
		document.getElementById("inputContact_"+id).value='';
		document.getElementById("inputPhone_"+id).value='';
		return true;
	}
	else{
		return false;
	}
}

function refresh_backcall(){
	document.getElementById("inputName_backcall").value="";
	document.getElementById("inputContact_backcall").value="";
	document.getElementById("backcall_success").style.display="none";
	document.getElementById("backcall_form").style.display="block";	
	return true;
}

function show_image(img){
	var a;
	a=document.getElementById('bigimg');
	a.src='/products/'+img+'_medium.jpg';
}

function change_x(){
	var subitem_id, temp;
	subitem_id=$('#subitem_id_X').val();
	$('#show_art').html( $('#article_'+subitem_id).html() );
	$('#show_price').html($('#itemprice_'+subitem_id).html());
	$('#show_oldprice').html($('#hid_oldprice_'+subitem_id).html());
	$('#to_basket').html("<a href='javascript:{};' id='ache_"+subitem_id+"' onclick='buyrightnow("+subitem_id+")' class='btn btn-xs btn-default'>В корзину</a>");
	$('#cont_is').html($('#hid_active_'+subitem_id).html());
	$('#by_btn').attr("onclick","buyrightnow("+subitem_id+")");

	$('#minus').attr("onclick","ch_precount("+subitem_id+",1)");
	$('#plus').attr("onclick","ch_precount("+subitem_id+",2)");
	$('#count_container').find('input').attr("id","item_count_"+subitem_id);
	$('#count_container').find('input').attr("name","item_count["+subitem_id+"]");
	$('#count_container').find('input').val('1');
}

function change_y(item_id){	
	var subitem_id, temp, old_price;
	subitem_id=$('#subitem_id_'+item_id).val();
	// alert (subitem_id);

	$('#show_price_'+item_id).html($('#itemprice_'+subitem_id).html());
	old_price=$('#hid_oldprice_'+subitem_id).html();
	
	if (old_price) $('#show_oldprice_'+item_id).html("Старая цена: "+old_price+" р.");
	else $('#show_oldprice_'+item_id).html("");

	$('#by_btn_'+item_id).attr("onclick","buyrightnow("+subitem_id+")");
	$('#minus_'+item_id).attr("onclick","ch_precount("+subitem_id+",1)");
	$('#plus_'+item_id).attr("onclick","ch_precount("+subitem_id+",2)");
	$('#count_container_'+item_id).find('input').attr("id","item_count_"+subitem_id);
	$('#count_container_'+item_id).find('input').attr("name","item_count["+subitem_id+"]");

	// $('#plus_'+item_id).attr("id","item_count_"+subitem_id);


	$('#to_basket_'+item_id).html("<a href='javascript:{};' id='ache_"+subitem_id+"' onclick='buyrightnow("+subitem_id+")' class='btn btn-sm btn-default'>В корзину</a>");

	// $('#cont_is').html($('#hid_active_'+subitem_id).html());
	// $('#show_art').html( $('#article_'+subitem_id).html() );
}

change_x();

!function ($) {
	$(function(){	  
	  $('#myCarousel').carousel()
	})
}(window.jQuery)



function putinbasket(ID,subitem_id){
	var count;
	// count=document.getElementById("count_"+ID).value;
	count=1;
	// alert(ID);
	// alert(subitem_id);
	$.post("http://europrofcosmetic.ru/putinbasket.php", { item_id: ID, item_count: count, subitem_id: subitem_id  }, 
	  function(data){
	    // alert(data);
	    document.getElementById("basketcount").innerHTML=data;            
	});
	var xx;
	xx=0;
	xx=document.getElementById("basketcount").innerHTML;
	xx++;
	document.getElementById("ache_"+subitem_id).innerHTML='Всего: '+xx;
	setTimeout ('document.getElementById("ache_'+subitem_id+'").innerHTML="В корзину"',3000);
}

function setback(ID){
	document.getElementById("ache_"+ID).innerHTML="В корзину";
}

function buyrightnow(xID){
	// document.getElementById("item_container").innerHTML=document.getElementById("item_"+ID).innerHTML+" &mdash; "+document.getElementById("itemprice_"+ID).innerHTML;
	var a,aa,b,c,d,dd, ID,x,total;

	ID=document.getElementById("item_id_"+xID).value;

	putinbasket(ID,xID);

	// alert($('#Buynow').modal({show:true});
		// if ($('#Buynow').)
		
	// yaCounter20407267.reachGoal('Backcall');

	a=document.getElementById("total_items");
	total=parseInt(a.value);
	aa=total;
	total=total+1;

	a.value=total;

	b=document.getElementById("item_"+xID).innerHTML;
	c=document.getElementById("itemprice_"+xID).innerHTML;
	art=document.getElementById("article_"+xID).innerHTML;
	d=document.getElementById("total_prix");
	e=document.getElementById("total_price");
	dd=d.innerHTML;
	if (dd>0) dd=parseInt(dd);
	d.innerHTML=dd+parseInt(c);
	e.value=d.innerHTML;
	x=aa+1;
	$('#order_list').append('<tr><td>'+x+'</td><td><input type="hidden" name="id['+aa+']" value="'+xID+'"><input type="hidden" name="name['+aa+']" value="'+b+'"> <input type="hidden" name="article['+aa+']" value="'+art+'"> <input type="hidden" name="items_price['+aa+']" id="ttl2_'+aa+'" value="'+c+'" > <input type="hidden" name="item_discount['+aa+']" id="item_discount_'+aa+'" value="1"><span style="display:none;" id="item_price_'+aa+'">'+c+'</span>'+b+'</td><td width="88" > <a href="javascript:{}" onclick="ch_count('+aa+',1)" style="float:left;"><span class="glyphicon glyphicon-minus-sign"></span></a> <a href="javascript:{}" onclick="ch_count('+aa+',2)" style="float:right;"><span class="glyphicon glyphicon-plus-sign"></span></a><input type="text" class="form-control" onchange="ch_prix('+aa+')" name="count['+aa+']" id="count_'+aa+'" style="width:42px;" value="1"></td><td align="right" width="70" ><span id="ttl_'+aa+'">'+c+'</span> р.</td></tr>');
	$('#count_'+aa).val($('#item_count_'+xID).val());
	ch_prix(aa);
	checkpromo();
	// document.getElementById("addtobusket_and_close").onclick=function(){
	//   putinbasket(ID);
	// }
	// if (document.getElementById("Buynow").style.display=='block') 
	// else alert('basket');
}

$('a[href="#Buynow"]').click(function(){
		// alert('buy');
	yaCounter20407267.reachGoal('button_buy');
});



$('a[id*=ache_]').click(function(){	
	// alert('basket');
	yaCounter20407267.reachGoal('button_basket');
});


function ch_prix(j){

	var count, total,c,p,ttl;
	ttl=0;
	total=0;
	count=document.getElementById("total_items").value;
	for (var i = 0; i < count; i++) {
	  // alert(i);
	  c=document.getElementById("count_"+i).value;
	  d=document.getElementById("item_discount_"+i).value;
	  p=document.getElementById("item_price_"+i).innerHTML;
	  ttl=Math.round(c*p*d);
	  document.getElementById("ttl_"+i).innerHTML=ttl;
	  document.getElementById("ttl2_"+i).value=ttl;
	  total=total+ttl;
	  // alert(j);
	  if (i==j) {
	  	// alert(j);
	  	$.post("http://europrofcosmetic.ru/putinbasket.php", { item_count: c,  change: j }, function(data){  });
	  }
	  
	};
	document.getElementById("total_prix").innerHTML=total;
	document.getElementById("total_price").value=total;
}

function ch_count(j,n){
	var count=$('#count_'+j).val();
	if (n==1){
		if (count>0) count--;
	}
	else if (n==2){
		count++;		
	}	
	$('#count_'+j).val(count);
	ch_prix(j);
}

function ch_precount(j,n){
	var count=$('#item_count_'+j).val();
	if (n==1){
		if (count>0) count--;
	}
	else if (n==2){
		count++;		
	}	
	$('#item_count_'+j).val(count);
	
}

$('.tooltip-demo').tooltip({
	selector: "a[data-toggle=tooltip]",
	trigger: "hover",      
	placement: "right"
})