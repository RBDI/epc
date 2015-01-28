<?php
/*
Plugin Name: Rus filename and link translit
Plugin URI: 
Description: Производит транслитерацию загружаемых файлов и постоянных ссылок, создаваемых из заголовков страниц и записей, имеющих русские символы в названии.
Version: 1.0
Author: Dmitry Fatakov
License: GPL2
Copyright: 2013
*/

$chars = array(
   "Є"=>"EH","І"=>"I","і"=>"i","№"=>"N","є"=>"eh",
   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
   "Е"=>"E","Ё"=>"JO","Ж"=>"ZH",
   "З"=>"Z","И"=>"I","Й"=>"JJ","К"=>"K","Л"=>"L",
   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
   "Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"jo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"jj","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya",
   "—"=>"-","«"=>"","»"=>"","…"=>""
  );
 
function rutranslit($title) {
    global $chars;

	if (seems_utf8($title))
		$title = urldecode($title);

	$title = preg_replace('/\.+/','.',$title);
	$r = strtr($title, $chars);
    return $r;
}

add_filter('sanitize_file_name','rutranslit');
add_filter('sanitize_title','rutranslit');
?>