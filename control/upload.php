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
	$fullpath=$imagepath.$resultfile;
	$img_info=getimagesize($fullpath);
	if ($size=='small'){
		$img_maxsize=170;
		$sq=1;
		$ext="_small.jpg";
	}
	else if($size=='medium'){
		$img_maxsize=500;
		$sq=0;
		$ext="_medium.jpg";

	}
	else if($size=='cats'){
		$img_maxsize=228;
		$sq=1;
		$ext='_.jpg';

	} else {
		$img_maxsize=500;
		$sq=0;		
		$ext="_".$img_maxsize.".jpg";
	}
	$img_type = $img_types[$img_info[2]];
	$th_w = $img_info[0];
	$th_h = $img_info[1];
	if ($th_w<$img_maxsize&&$th_h<$img_maxsize) if ($th_w<$th_h) $img_maxsize=$th_h; else  $img_maxsize=$th_w;
//	if ($th_h<$img_maxsize_h) $img_maxsize_h=$th_h;

	$move_w = $move_h = 0;
	$w = $h = 0;
//	$w=$img_maxsize_w;
//	$h=$img_maxsize_h;


	if ($sq==1){
		$WW=$HH=$img_maxsize;
		if (($th_w<$img_maxsize)||($th_h<$img_maxsize)){
				$w=$th_w;
				$h=$th_h;
				$move_h=$move_w=0;
			}
		else{
			if ($th_w>$th_h) { 
				$move_h=round(($img_maxsize-($th_h*$img_maxsize/$th_w))/2);
				$w=$img_maxsize;
				$h=($th_h*$img_maxsize/$th_w);
			}
			else {
				$move_w=round(($img_maxsize-($th_w*$img_maxsize/$th_h))/2);
				$w=($th_w*$img_maxsize/$th_h);
				$h=$img_maxsize;
			}
		}
	}	
	
	else{
		if ($th_w>$th_h){	
			$w = $img_maxsize;
			$h = (($th_h * $w) / $th_w);
		}
		else {
			$h = $img_maxsize;
			$w = (($th_w * $h) / $th_h);		
		}
	
		$WW=$w;
		$HH=$h;
	}
	
	
		//// create image ////
		$thumb = imagecreatetruecolor($WW, $HH);
		$white = imagecolorallocate($thumb, 255, 255, 255);
		imagefill($thumb, 0, 0, $white);
		
		$image = imagecreatetruecolor($th_w, $th_h);
		$white = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 0, 0, $white);	
			
		$img=$fullpath;

			//// copy image ////
			if (($img_type == "JPG") && (imagetypes() & IMG_JPG)) {
				$filename=eregi_replace(".jpg", "", $resultfile);
				$image = imagecreatefromjpeg($img);

			} else if (($img_type == "GIF") && (imagetypes() & IMG_GIF)) {
				$filename=eregi_replace(".gif", "", $resultfile);
				$image = imagecreatefromgif($img);

			} else if (($img_type == "PNG") && (imagetypes() & IMG_PNG)) {
				$filename=eregi_replace(".png", "", $resultfile);
				$image = imagecreatefrompng($img);

			}
		$filename=$num;
		$thumb_url=$imagepath.$filename.$ext;
		$img_quality=90;

		$created = imagecopyresampled($thumb, $image, $move_w, $move_h, 0, 0, $w, $h, $th_w, $th_h);
		if ($size=='small'){
			$text_color = imagecolorallocate ($thumb, 255, 255, 255); 
			$rect_color = imagecolorallocate ($thumb, 255, 102, 0); 
		}
		$thumbCreated = imagejpeg($thumb, $thumb_url, $img_quality);		//if ($thumbCreated) print $thumb_url;

	//// destroy images (free memory)
	imagedestroy($image);
	imagedestroy($thumb);

	/* END */
}



	//if (!is_dir($imagepath)) mkdir($imagepath);

	$tmp_file=$_FILES['userfile']['tmp_name'];
	$result_file=$_FILES['userfile']['name'];
	$type_file= $_FILES['userfile']['type'];
	$size_file= $_FILES['userfile']['size'];
	$error_file = $_FILES['userfile']['error'];

$i=0;
$num = array();


//echo 'count ',count($tmp_file);
while ($i<count($tmp_file)) {
if (strlen(trim($tmp_file[$i]))>2) {
//	$result_file[$i]=slug($result_file[$i]);
	
	$num[$i]=$brand_name_result.'-'.$name.'-'.rand(100,2000);	

	if ($tmp_file[$i]){

	if (stristr($result_file[$i], '.jpg')) {
		//$result_file[$i]=md5(eregi_replace(".jpg", "", $result_file[$i])).'.jpg';
		$result_file[$i]=$num[$i].'.jpg';
	}
	else if (stristr($result_file[$i], '.gif')) {
		//$result_file[$i]=md5(eregi_replace(".gif", "", $result_file[$i])).'.gif';
		$result_file[$i]=$num[$i].'.gif';
	}
	else if (stristr($result_file[$i], '.png')) {
		//$result_file[$i]=md5(eregi_replace(".png", "", $result_file[$i])).'.png';
		$result_file[$i]=$num[$i].'.png';
	}
	else if (stristr($result_file[$i], '.jpeg')) {
		//$result_file[$i]=md5(eregi_replace(".png", "", $result_file[$i])).'.png';
		$result_file[$i]=$num[$i].'.jpeg';
	}
	else {
	print 'JPG, GIF or PNG only!';
	$tmp_file[$i]='';
	}

		$full_path[$i]=$imagepath.$result_file[$i];
		$ok=move_uploaded_file($tmp_file[$i], $full_path[$i]);



		if($ok){
			chmod ($full_path[$i], 0644);
		}
		else {
			print "error!<br/>";
			//print $error_file[$i];
		}
	}

////////CREATING THUMBS

	if ($full_path[$i]){
		if ($view) {
			createthumb($imagepath,$result_file[$i],$id_m,$num[$i]);
		} else {
			if ($C_AA){
				createthumb($imagepath,$result_file[$i],'cats',$num[$i]);
			}
			else{
				createthumb($imagepath,$result_file[$i],'small',$num[$i]);
				if (!$reg) createthumb($imagepath,$result_file[$i],'medium',$num[$i]);
			}
		}
		unlink($full_path[$i]);
	}
}
$i++;
}
?>