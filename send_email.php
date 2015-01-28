<?
    function convert_charset($item)
    {
        if ($unserialize = unserialize($item))
        {
            foreach ($unserialize as $key => $value)
            {
                $unserialize[$key] = @iconv('utf-8', 'koi8-r', $value);
            }
            $serialize = serialize($unserialize);
            return $serialize;
        }
        else
        {
            return @iconv('utf-8', 'koi8-r', $item);
        }
    }




// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0
Content-type: text/html; charset=utf-8
From: EuroProfCosmetic <zakaz@europrofcosmetic.ru>';
// $title=convert_charset($email_title);
// $message=convert_charset($email_text);
$title= $email_title;
$message= $email_text;

$adress='zakaz@europrofcosmetic.ru';
// $adress='agrabarnick@gmail.com';

require_once('phpgmailer/class.phpgmailer.php');

// $ok=mail($adress,$title,$message,$headers); 

// $ok=mail($to,$subject,$text,$headers); 

$mail = new PHPGMailer();
$mail->CharSet = "utf-8";
$mail->Username = 'zakaz@europrofcosmetic.ru';
$mail->Password = 'profeuro';
$mail->From = 'zakaz@europrofcosmetic.ru';
$mail->FromName = 'EuroProfCosmetic';
$mail->Subject = $title;
$mail->AddAddress($adress);
$mail->Body = $message;
$mail->Send();

// print $adress.' '.$title.' '.$message.' '.$headers;
////////////////////////////////////////////////////////////////
 



$subject='Ваш заказ с сайта EuroProfCosmetic ('.$user_id.')';
$to=$email;
$text="
Здравствуйте, ".$username.".
<p>Номер заказа: ".$user_id."</p>
".$user_text."
<p>Менеджер свяжется с вами в ближайшее время для подтверждения заказа.</p>
<p>
Интернет магазин профессиональной косметики и оборудования<br>
EuroProfCosmetic.ru
</p>
<p>
Время работы: Пн-Сб, с 9:00 до 18:00<br>
Телефон: (495) 517-73-80<br>
E-mail: zakaz@europrofcosmetic.ru
</p>
";


$headers  = 'MIME-Version: 1.0
Content-type: text/html; charset=utf-8
From: EuroProfCosmetic <zakaz@europrofcosmetic.ru>';

// $ok=mail($to,$subject,$text,$headers); 

$mail = new PHPGMailer();
$mail->CharSet = "utf-8";
$mail->Username = 'zakaz@europrofcosmetic.ru';
$mail->Password = 'profeuro';
$mail->From = 'zakaz@europrofcosmetic.ru';
$mail->FromName = 'EuroProfCosmetic';
$mail->Subject = $subject;
$mail->AddAddress($to);
$mail->Body = $text;
$mail->Send();

 
$phonex='7'.$phone;
sms($phonex,$user_id);

function sms($phone0,$id) {
        $text='Номер заказа: '.$id.' Ожидайте звонка менеджера. EuroProfCosmetic.ru';
    // $text='EDANADEN.RU Spasibo za zayavku! Vash kod na skidku: X'.$id.' Skoro mi soobshim o starte prodaj!';
        //echo 'text - '.$text.'<br>';
        $text=iconv("utf-8", "windows-1251//IGNORE", $text);    
        $url='http://smsc.ru/sys/send.php';
        $data['login']='epc';
        $data['psw']='ghjgecr';
        $data['phones']=$phone0;
        $data['mes']=$text;
        //
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        // print $result;
        curl_close($ch);
return 1;
}
?>