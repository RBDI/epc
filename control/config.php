<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	$db2 = mysql_select_db(DB_NAME, $db1);
	mysql_query('SET NAMES utf8');
//	print getcwd();
	$imagepath='/home/u388041/europrofcosmetic.ru/www/products/';	
	include "functions.php";
?>