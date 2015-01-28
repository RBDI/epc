<?
session_start();
$type=$_GET['type'];
$mode=$_GET['mode'];
$filename=$_GET['filename'];

if ($type=='catalog'&&$mode=='checkauth') {
	print "success\n";
	print session_name()."\n";
	print session_id();
}
elseif ($type=='catalog'&&$mode=='init') {
print 'zip=no
file_limit=2000000';
}
elseif ($type=='catalog'&&$mode=='success') {
	print 'success';
}
elseif ($type=='catalog'&&$mode=='file') {
	print 'success\n';
	$f = fopen($filename, 'w');
	fwrite($f, file_get_contents('php://input'));
	fclose($f);	
}



if ($type=='sale'&&$mode=='checkauth') {
	print "success\n";
	print session_name()."\n";
	print session_id();
}
elseif ($type=='sale'&&$mode=='init') {
print 'zip=no
file_limit=2000000';
}
elseif ($type=='sale'&&$mode=='query') {

include "orders.php";
}
elseif ($type=='sale'&&$mode=='success') {
	print 'success';
}

elseif ($type=='sale'&&$mode=='file') {	
	$f = fopen($filename, 'w');
	fwrite($f, file_get_contents('php://input'));
	fclose($f);
}



?>