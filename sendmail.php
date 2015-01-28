<?
function convert_charset($item) {
	if ($unserialize = unserialize($item)) {
		foreach ($unserialize as $key => $value) {
			$unserialize[$key] = @iconv('utf-8', 'koi8-r', $value);
		}
		$serialize = serialize($unserialize);
        return $serialize;
	}
	else {
		return @iconv('utf-8', 'koi8-r', $item);
	}
}

$type=$_POST['type'];

if ($type==1){
	$name=$_POST['nm'];
	$contact=$_POST['cntc'];
	$email_title='Заказ обратного звонка с EuroProfCosmetic';
	$email_text='Имя: '.$name.'<br> Телефон: '.$contact.'<br> Когда: '.date("H:i d.m.y");
}
elseif ($type==2){
	$name=$_POST['nm'];
	$mail=$_POST['ml'];
	$phone=$_POST['pn'];
	$email_title='Заказ с EuroProfCosmetic';
	$email_text='Имя: '.$name.'<br> Email: '.$mail.'<br> Телефон: '.$phone.'<br> Когда: '.date("H:i d.m.y");
}


$title=convert_charset($email_title);
$message=convert_charset($email_text);
$adress='zakaz@europrofcosmetic.ru';

$headers  = 'MIME-Version: 1.0
Content-type: text/html; charset=koi8-r
From: EuroProfCosmetic <mailer@europrofcosmetic.ru>
';

$ok=mail($adress,$title,$message,$headers);	
print $ok;

if ($type==1){
	include "wp-config.php";
	$date_time=date("c",strtotime('+3 hours'));
	$sql="INSERT INTO `shop_users` (`name`,`phone`,`comment`,`status`,`date_time`) VALUES ('$name','$contact','Заказ обратного звонка','3','$date_time')";
	$result = mysql_query($sql) or die(mysql_error());	
}


if ($type==2){

$title=convert_charset('Заказ прайс-листа с EuroProfCosmetic.ru');
$email_text='
Здравствуйте, '.$name.'!<br>
<br>
Прайс и каталог в приложении.<br>
С нами вы можете связаться по почте или телефону (495) 517-73-80.<br>
<br>
Благодарим Вас,<br>
Менеджер - Светлана Скорода<br>
<br>
Интернет магазин профессиональной косметики и оборудования<br>
EuroProfCosmetic.ru<br>
<br>
Телефон: (495) 517-73-80<br>
E-mail: opt@europrofcosmetic.ru
';
$message=convert_charset($email_text);

$headers  = 'MIME-Version: 1.0
Content-type: text/html; charset=koi8-r
From: EuroProfCosmetic <opt@europrofcosmetic.ru>
';

require_once('phpgmailer/class.phpgmailer.php');

$subject=$title;
$to=$mail;
$text=$message;

$mail = new PHPGMailer();
$mail->CharSet = "koi8-r";
$mail->Username = 'opt@europrofcosmetic.ru';
$mail->Password = 'profopt';
$mail->From = 'opt@europrofcosmetic.ru';
$mail->FromName = convert_charset('Интернет-магазин EuroProfCosmetic');
$mail->Subject = $subject;
$mail->AddAddress($to);
$mail->Body = $text;
$mail->AddAttachment('download/price_europrofcosmetic.pdf');
$mail->AddAttachment('download/catalog_europrofcosmetic.pdf');
$mail->Send();

}
?>