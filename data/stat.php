 <?
session_start();

if ($_POST['login']){
	if ($_POST['login']=='epc'&$_POST['password']=='35398752'){
		$_SESSION['loginXX']=1;
		$maf=1;
	}
}

if ($_SESSION['loginXX']!=1&&$maf!=1) {
?>
<form action="/data/stat.php" method="post">
	<p>LOGIN <input type="text" name="login" value=""></p>
	<p>PASSWORD <input type="password" name="password" value=""></p>	
	<input type="submit" value="LOG IN">
</form>
<?

	die();
}
?>
<?
// include "../config.php";

include_once "../wp-config.php";
$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
$db2 = mysql_select_db(DB_NAME, $db1);
mysql_query('SET NAMES utf8');

$needles=array(1249, 1496, 1495, 1494, 218, 997, 1234, 1235, 1236, 1238, 1239, 1240, 1471, 1474, 227, 998, 1241, 1242, 1243, 1244, 1245, 1246, 1247, 329, 1399, 1398, 1397, 999, 1252, 1253, 1254, 1400, 1401, 1708, 1709, 1710, 1711, 1712, 1713, 1714, 1715, 1716, 1717, 1718, 1719, 1720, 1721, 1722, 1723, 1724, 1725, 1726, 1727, 1728, 1729, 1730, 1731, 1732, 1733, 1734, 1735, 1736, 1737);

if (!$_GET['status']) $_GET['status']='paiddate';

if ($_GET['status']=='paid') $sql='WHERE payment_status>0';
if ($_GET['status']=='paiddate') $sql='WHERE payment_status>0';
if ($_GET['manager']=='nika') $sql.=' AND manager=2';
if ($_GET['manager']=='skoroda') $sql.=' AND manager=1';
if ($_GET['manager']=='ksenia') $sql.=' AND manager=3';

if ($_POST['start_time']&&$_POST['end_time']){
	
	$start_time=strtotime($_POST['start_time']);
	$end_time=strtotime($_POST['end_time']);
	$_SESSION['period']['start_time']=$start_time;
	$_SESSION['period']['end_time']=$end_time;
}
else {
	if ($_SESSION['period']['start_time']&&$_SESSION['period']['end_time']){
		$start_time=$_SESSION['period']['start_time'];
		$end_time=$_SESSION['period']['end_time'];
	}
	else {
		$end_time=time();
		$delta=30*24*60*60;
		// $start_time=strtotime('01.'.date("m.Y"));
		$start_time=$end_time-$delta;
		// $end_time-=1;
	}
}


$sql="SELECT * FROM `shop_users` $sql ORDER BY ID DESC";	

$result = mysql_query($sql) or die(mysql_error());
$ids='';
while ($row=mysql_fetch_array($result)) {
	$orders[$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['ID'];
	else $ids=$row['ID'];

	$mytime=strtotime($row['date_time']);
	if ($_GET['status']=='paiddate') {
		if ($row['pay_time']=='0000-00-00 00:00:00') $mytime=strtotime($row['edit_time']);
		else $mytime=strtotime($row['pay_time']);
	}
	$time=strtotime(date("d.m.Y",$mytime));
	
	
	if ($time>=$start_time && $time<=$end_time) $order_by_date[$time][$row['ID']]=$row;
}
// print_r($order_by_date);

$sql="SELECT * FROM `shop_orders` WHERE user_id IN ($ids)";	
$result = mysql_query($sql) or die(mysql_error());
$ids='';
while ($row=mysql_fetch_array($result)) {
	$order_items[$row['user_id']][$row['ID']]=$row;
	if ($ids!='') $ids.=', '.$row['item_id'];
	else $ids=$row['item_id'];	
}

$sql="SELECT * FROM `shop_subitem` WHERE ID IN ($ids)";	
$result = mysql_query($sql) or die(mysql_error());

while ($row=mysql_fetch_array($result)) {
	$subitems[$row['ID']]=$row;	
}


// $i=count($base);
$DAY30_TOTAL=0;
$TOTALX=0;
$I=0;
$D=0;
$graph='';

krsort($order_by_date);

foreach ($order_by_date as $date => $orders) {

	$i=0;
	$j=0;
	$tr='';
	$tr1=1;
	$DAY_TOTAL=0;
	foreach ($orders as $order_id => $order) {
		$i++;
		$date_time=date("d.m.Y",strtotime($order['date_time']));
		if ($tr1==1) $tr='<td>'.$i.'</td><td>'.$order_id.'</td><td>'.$date_time.'</td>';
		else $tr.='<tr><td>'.$i.'</td><td>'.$order_id.'</td><td>'.$date_time.'</td>';
		$tr.='<td>';
		$total1=0;
		$total2=0;
		foreach ($order_items[$order_id] as $suborder_id => $item) {
			if (!$_GET['needles']||($_GET['needles']==1&& in_array($item['item_id'], $needles))||($_GET['needles']=='no'&& !in_array($item['item_id'], $needles))){

				if ($item['price']>0) $price=$item['price'];
				else {
					$price=$subitems[$item['item_id']]['value1'];
					if ($subitems[$item['item_id']]['value2']!='') $price=$subitems[$item['item_id']]['value2'];
				}
				$dis='';

				if ($item['discount']) {
					$price=$price*((100-$item['discount'])/100);
					$dis='*';
				}

				$tr.=$item['item_id'].' - '.$price.$dis.' руб. - '.$item['count'].' шт.<br>';
				$sales[$item['item_id']]+=$item['count'];

				// $discount=1;
				// if ($item['discount']) {
				// 	$discount=(100-$item['discount'])/100;
				
				// }
				
				
								
				// $total1+=$price*$item['count'];
				$total1+=$price*$item['count'];
				
				$buy_price=$subitems[$item['item_id']]['value4'];
				if ($buy_price==0) {
					$buy_price=$price*0.65;
					$red='style="color:#F00;"';
				}
				$total2+=$buy_price*$item['count'];
			}
		}

		$tr.='</td>';

		$tr.='<td>+'.$total1.'</td><td '.$red.'>-'.$total2.'</td>';
		$red='';
		// if ($order['delivery_price']>0) $tr.='<td>-'.$order['delivery_price'].'</td>';
		$outgo='';
		if ($total1==0) $order['outgo']=0;
		if ($order['outgo']>0) $outgo='-'.$order['outgo'];
		$tr.='<td>'.$outgo.'</td>';
		$TOTAL=$total1-$total2-$order['outgo'];
		if ($TOTAL>0) $j++;
		$TOTALX+=$total1;
		$tr.='<td>='.$TOTAL.'</td>';
		
		if ($tr1==1) {
			$tr1=0;
			$tx=$tr;
			$tr='';

		}
		else $tr.='</tr>';
		$DAY_TOTAL+=$TOTAL;
	}
	$DAY30_TOTAL+=$DAY_TOTAL;
	$N=date("N",$date);
	$blue='';
	if ($N>5) $blue='style="color:#CCF;"';
	$print.= '<tr><td '.$blue.' rowspan="'.$i.'">'.date("d.m.Y",$date).'</td>'.$tx.'<td rowspan="'.$i.'" align="center"><strong>+'.$DAY_TOTAL.' руб.</strong></td>'.$tr;
	$graph.='{ "x": "'.date("Y-m-d",$date).'", "y": '.$DAY_TOTAL.' },';
	$I+=$j;
	$D++;
}


foreach ($sales as $subitem_id => $count) {
	if ($subitem_ids=='') $subitem_ids=$subitem_id;
	else $subitem_ids.=','.$subitem_id;
}

$sql="SELECT shop_subitem.ID, shop_catalog.name, shop_subitem.value1,shop_subitem.name,shop_subitem.item_id  FROM shop_catalog, shop_subitem WHERE shop_subitem.ID IN ($subitem_ids) AND shop_catalog.ID=shop_subitem.item_id  ";

$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$item_sales[$row[0]]=$row;
}

?>
<table>
<?

arsort($sales);
$i=0;
foreach ($sales as $subitem_id => $count) {
	$i++;
	$print_sales.='<tr><td>'.$i.'</td><td>'.$subitem_id.'</td><td>'.$item_sales[$subitem_id][4].'</td><td>'.$item_sales[$subitem_id][1].' '.$item_sales[$subitem_id][3].'</td><td>'.$item_sales[$subitem_id][2].'</td><td>'.$count.'</td></tr>';
}

?>	
<html>
<head>
	<title>База</title>
	<link href="../wp-content/themes/EPC/css/bootstrap.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" media="all" href="daterangepicker-bs3.css" />
	<link href="xcharts.css" rel="stylesheet" media="screen">
	<style type="text/css">
		.ex-tooltip{position: absolute;}
		.dts input{
			width: auto;
			display: inline-block;
		}
	</style>
	
	<script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://europrofcosmetic.ru/wp-content/themes/EPC/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="moment.js"></script>
  	<script type="text/javascript" src="daterangepicker.js"></script>

    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="xcharts.js"></script>

</head>
<body>
	


	<div style="margin:20px;">	
		<ul class="nav nav-pills">
			
			<li><a href="?status=paiddate">Оплаченные все (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&manager=nika">Оплаченные Ника (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&manager=skoroda">Оплаченные Света (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&manager=ksenia">Оплаченные Ксения (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&needles=1">Оплаченные ИГЛЫ (по дате оплаты)</a></li>
			<li><a href="?status=paiddate&needles=no">Оплаченные без игл (по дате оплаты)</a></li>
			<li><a href="?status=paid">Оплаченные (по дате заказа)</a></li>
			<!-- <li><a href="?">Все за 30 дней</a></li> -->
			<li><a href="?needles=no">Все за 30 дней без игл</a></li>
			<li><a href="?needles=1">Все за 30 дней только иглы</a></li>
			<li><a href="?status=paiddate&showsales=1">Продажи товаров</a></li>

		</ul>

<!--  -->
<!-- <form action="" method="post" onsubmit="set_date();">
	<div class="row">
		<div class="col-sm-3">
			<select name="status" class="form-control">		
				<option value="1">Оплаченные все (по дате оплаты)</option>
				<option value="2">Оплаченные ИГЛЫ (по дате оплаты)</option>
				<option value="3">Оплаченные без игл (по дате оплаты)</option>
				<option value="4">Оплаченные (по дате заказа)</option>
				<option value="5">Заказанные</option>
				<option value="6">Заказанные без игл</option>
				<option value="7">Заказанные только иглы</option>
			</select>
		
		
		</div>
		<div class="col-sm-3">
			<div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
			  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
			  <span></span> <b class="caret"></b>
			</div>	
		</div>
		<div class="col-sm-3">
			<input type="hidden" id="from_date" name="from_date" value="">
			<input type="hidden" id="to_date" name="to_date" value="">
			<input type="submit" class="btn btn-default" value="Показать">
		</div>
	</div>
</form> -->
               <script type="text/javascript">
               $(document).ready(function() {

                  var cb = function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
                  }

                  var optionSet1 = {
                    startDate: moment().subtract('days', 29),
                    endDate: moment(),
                    minDate: '01/01/2012',
                    maxDate: '12/31/2014',
                    dateLimit: { days: 60 },
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                       'Today': [moment(), moment()],
                       'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                       'Last 7 Days': [moment().subtract('days', 6), moment()],
                       'Last 30 Days': [moment().subtract('days', 29), moment()],
                       'This Month': [moment().startOf('month'), moment().endOf('month')],
                       'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    },
                    opens: 'left',
                    buttonClasses: ['btn btn-default'],
                    applyClass: 'btn-small btn-primary',
                    cancelClass: 'btn-small',
                    format: 'DD/MM/YYYY',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Submit',
                        cancelLabel: 'Clear',
                        fromLabel: 'From',
                        toLabel: 'To',
                        customRangeLabel: 'Custom',
                        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        firstDay: 1
                    }
                  };

                  var optionSet2 = {
                    startDate: moment().subtract('days', 7),
                    endDate: moment(),
                    opens: 'left',
                    ranges: {
                       'Today': [moment(), moment()],
                       'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                       'Last 7 Days': [moment().subtract('days', 6), moment()],
                       'Last 30 Days': [moment().subtract('days', 29), moment()],
                       'This Month': [moment().startOf('month'), moment().endOf('month')],
                       'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    }
                  };

                  $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

                  $('#reportrange').daterangepicker(optionSet1, cb);

                  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
                  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
                  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
                    console.log("apply event fired, start/end dates are " 
                      + picker.startDate.format('MMMM D, YYYY') 
                      + " to " 
                      + picker.endDate.format('MMMM D, YYYY')
                    ); 
                  });
                  $('#reportrange').on('cancel.daterangepicker', function(ev, picker) { console.log("cancel event fired"); });

                  $('#options1').click(function() {
                    $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
                  });

                  $('#options2').click(function() {
                    $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
                  });

                  $('#destroy').click(function() {
                    $('#reportrange').data('daterangepicker').remove();
                  });

               });
               </script>
<!--  -->

		<div class="dts">
			<form action="" method="POST">
				<input class="form-control input-lg" type="text" name="start_time" id="start_time" placeholder="<? print date("d.m.Y",$start_time); ?>" value="<? print date("d.m.Y",$start_time); ?>">
				 &mdash; 
				 <input class="form-control input-lg" type="text" name="end_time" id="end_time" placeholder="<? print date("d.m.Y",$end_time); ?>" value="<? print date("d.m.Y",$end_time); ?>">
				 <button type="submit" class="btn btn-default btn-lg">Показать</button>
			 </form>
		</div> 	
		<h3>
			<!-- <? print date("d.m.y",$start_time); ?> &mdash; <? print date("d.m.y",$end_time); ?>: -->
			2%: <? print $TOTALX*0.02; ?> Прибыль: +<? print $DAY30_TOTAL; ?> руб. <small>Оборот: <? print $TOTALX; ?> руб.</small></h3>
		<p>В среднем в день: <? print round($I/$D,2); ?> заказов, прибыль: <? print round($DAY30_TOTAL/$D); ?> руб./день. Средний чек: <? print round($TOTALX/$I); ?> руб./заказ, прибыль: <? print round($DAY30_TOTAL/$I); ?> руб./заказ</p>
<? if ($_GET['showsales']!=1){ ?>		
<figure style="width: 100%; height: 300px;" id="example1"></figure>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Дата оплаты</th>
			<th>#</th>
			<th>ID заказа</th>
			<th>Дата заказа</th>
			<th>ID - цена - кол-во</th>
			<th>Продажа</th>
			<th>Закупка</th>
			<th>Доп.расходы</th>
			<th>Итого</th>
			<th>Итого за день</th>
		</tr>
	</thead>
<? print $print; ?>
	</table>
<? } else { ?>	
<table class="table table-bordered">
	<!-- <thead>
		<tr>
			<th>ID</th>
			<th>Название</th>
			<th>Кол-во</th>			
		</tr>
	</thead> -->
<? print $print_sales; ?>
	</table>	
<? } ?>
</div>
    

<script type="text/javascript">

var tt = document.createElement('div'),
  leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
  topOffset = -32;
tt.className = 'ex-tooltip';
document.body.appendChild(tt);

var data = {
  "xScale": "time",
  "yScale": "linear",  
  "main": [
    {
      "className": ".pizza",
      "data": [
<? print $graph; ?>        
      ]
    }
  ]
};

var opts = {
  "dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
  "tickFormatX": function (x) { return d3.time.format('%d.%m')(x); },
  "mouseover": function (d, i) {
    var pos = $(this).offset();    
    $(tt).text(d3.time.format('%d.%m')(d.x) + ': ' + d.y+' руб.')
      .css({top: topOffset + pos.top, left: pos.left + leftOffset})
      .show();
  },
  "mouseout": function (x) {
    $(tt).hide();
  }
};

// $("input[name*='daterangepicker_start']").change( function() {
// 	alert('x');

// });

function set_date () {
	$('#from_date').val($(".daterangepicker_start_input input").val());
	$('#to_date').val($(".daterangepicker_end_input input").val());
	return false;
}





var myChart = new xChart('line-dotted', data, '#example1', opts);
</script>    
</body>
</html>