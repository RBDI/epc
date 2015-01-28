<?
session_start();

// unset($_SESSION["ORDER"]);

$buy=$_POST['item_id'];
$subitem=$_POST['subitem_id'];
$count=$_POST['item_count'];
$change=$_POST['change'];
// print_r($_POST);

// print_r($_SESSION);

if ($change!=''&&$_SESSION["ORDER"][$change][0]){
	$_SESSION["ORDER"][$change][1]=$count;
	// print '???:'.$change.' ';
}
else {
	$z=0;	
	if (count($_SESSION["ORDER"])!=0) $z=count($_SESSION["ORDER"]);
	if ($buy) {
		$_SESSION["ORDER"][$z][0]=$buy;
		$_SESSION["ORDER"][$z][1]=$count;
		$_SESSION["ORDER"][$z][2]=$subitem;
	}
}
// print_r($_SESSION);
print count($_SESSION["ORDER"]);
?>