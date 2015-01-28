<?
	include_once "../wp-config.php";
	$db1 = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

	$db2 = mysql_select_db('u388041_new', $db1);
	mysql_query('SET NAMES utf8');

$sql="SELECT * FROM shop_img";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$imgs[$row['ID']]=$row;
}

$sql="SELECT ID, name FROM shop_catalog";
$result = mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result)) {
	$items[$row['ID']]=$row;
}
$i=0;

foreach ($imgs as $ID => $img) {
	$souce_path='http://www.europrofcosmetic.ru/files/'.$img['filename'];
	$img_new_name=slug($items[$img['item_id']]['name']).'-'.rand(100,999);
	$new_path='/home/u388041/new.europrofcosmetic.ru/www/products/'.$img_new_name;
	print $i.' - '.$ID.' '; // .'<br>'.$souce_path.'<br>'.$new_path.'</p>';
	// createthumb($souce_path,$new_path,'small');
	// createthumb($souce_path,$new_path,'medium');

	$sql="UPDATE `shop_img` SET `fn_old`='$img_new_name' WHERE `ID`='$ID'";
	// $result = mysql_query($sql) or die(mysql_error());


	$i++;
	// if ($i==10) die();
}


//$sql="UPDATE `shop_catalog` SET `name`='$name',`article`='$article' WHERE `ID`='$id'";
// $resultx = mysql_query($sql) or die(mysql_error());

?>


<?

function createthumb($imagepath,$resultfile,$size,$num){
	$img_types = array(
		"",
		"GIF",
		"JPG",
		"PNG",
		"SWF",
		"PSD",
		"BMP",
		"TIFF",
		"TIFF",
		"JPC",
		"JP2",
		"JPX",
		"JB2",
		"SWC",
		"IFF",
		"WBMP",
		"XBM"
	);
	$fullpath=$imagepath;//.$resultfile;
	$img_info=getimagesize($fullpath);
	
	if ($size=='small'){
		
		$sq=1;
		$dst_w = $dst_h = 170;
		$ext="_small.jpg";
	}
	else if($size=='medium'){
		
		$dst_w = $dst_h = 500;
		$sq=1;
		$ext="_medium.jpg";

	}


	$img_type = $img_types[$img_info[2]];

	$th_w = $img_info[0];
	$th_h = $img_info[1];

	$src_w = $img_info[0];
	$src_h = $img_info[1];
	

	
	
		//// create image ////
		$dst_image = imagecreatetruecolor($dst_w, $dst_h);
		$white = imagecolorallocate($dst_image, 255, 255, 255);
		imagefill($dst_image, 0, 0, $white);
		
		$src_image = imagecreatetruecolor($src_w, $src_h);
		$white = imagecolorallocate($src_image, 255, 255, 255);
		imagefill($src_image, 0, 0, $white);	
			
		$img=$fullpath;

		//// copy image ////
		if (($img_type == "JPG") && (imagetypes() & IMG_JPG)) {				
			$src_image = imagecreatefromjpeg($img);
		}
		else if (($img_type == "GIF") && (imagetypes() & IMG_GIF)) {			
			$src_image = imagecreatefromgif($img);
		}
		else if (($img_type == "PNG") && (imagetypes() & IMG_PNG)) {			
			$src_image = imagecreatefrompng($img);
		}
	
		$dst_x =$dst_y = $src_x = $src_y = 0;

		if ($src_h<$dst_h&&$src_w<$dst_w) {
			
			$dst_x=round(($dst_w-$src_w)/2);
			$dst_y=round(($dst_h-$src_h)/2);

			$dst_w=$src_w;
			$dst_h=$src_h;
		}
		else {
			if ($src_w>$src_h) {
				$tmp_h=round($src_h/($src_w/$dst_w));
				$dst_y=round(($dst_h-$tmp_h)/2);
				$dst_h=$tmp_h;
			}
			elseif ($src_w<$src_h) {
				$tmp_w=round($src_w/($src_h/$dst_h));
				$dst_x=round(($dst_w-$tmp_w)/2);
				$dst_w=$tmp_w;
			}			
		}

		imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		$thumb_url=$resultfile.$ext;
		$img_quality=90;
		$thumbCreated = imagejpeg($dst_image, $thumb_url, $img_quality);		//if ($thumbCreated) print $thumb_url;
		
	//// destroy images (free memory)
	imagedestroy($image);
	imagedestroy($dst_image);

	/* END */
}
?>


<?
function slug($text) {
	$text=mb_strtolower($text);
 	$text = mb_ereg_replace ("[^a-zабвгдеёжзийклмнопрстуфхцчшщьыъэюя«»0-9\-\s]","",$text);
	//$text= preg_replace('![^\w\d\s]*!','',$text);
	
	//print $text;
	while ($text) {
		$single =  mb_substr($text, 0, 1);
		//print $single.'-';
		$single_new='';
		switch ($single) {
			case "а": $single_new ="a"; break;	
			case "б": $single_new ="b"; 	break;
			case "в": $single_new ="v"; break;	
			case "г": $single_new ="g"; 	break;
			case "д": $single_new ="d"; 	break;	
			case "е": $single_new ="e"; 	break;
			case "ё": $single_new ="yo"; break;	
			case "ж": $single_new ="j"; 	break;
			case "з": $single_new ="z"; break;
			case "й": $single_new ="i"; 	break;
			case "и": $single_new ="i"; 	break;
			case "к": $single_new ="k"; 	break;	
			case "л": $single_new ="l"; 	break;
			case "м": $single_new ="m"; 	break;	
			case "н": $single_new ="n"; 	break;
			case "о": $single_new ="o"; 	break;	
			case "п": $single_new ="p"; 	break;
			case "р": $single_new ="r"; break;	
			case "с": $single_new ="s"; 	break;
			case "т": $single_new ="t"; break;	
			case "у": $single_new ="u"; 	break;
			case "ф": $single_new ="f"; break;	
			case "х": $single_new ="h"; 	break;
			case "ц": $single_new ="c"; break;	
			case "ч": $single_new ="ch";	break;
			case "ш": $single_new ="sh";	break;	
			case "щ": $single_new ="sch";	break;
			case "ь": $single_new ="?";	break;	
			case "ы": $single_new ="i";	break;
			case "ъ": $single_new ="?";	break;	
			case "э": $single_new ="e";	break;
			case "ю": $single_new ="u";	break;	
			case "я": $single_new ="ya"; break;	
			case "«": $single_new ="?"; break;	
			case "»": $single_new ="?"; break;			
		}
		if ($single_new) {
			if ($single_new=='?') $single_new='';
			$word=$word.$single_new;
			$text = substr_replace ($text, "", 0, 2);			
		}
		else {
			$word=$word.$single;
			$text = substr_replace ($text, "", 0, 1);			
		}


 	}
 
	$vowels2 = array(" ", "/", "_");				
	$slug=str_replace($vowels2, "-", $word);
	
	return $slug;
}
?>