<?
$phone=$_POST['phone'];
$text=$_POST['text'];

if ($phone&&$text) print sms($phone,$text);

function sms($phone,$text) {
    $text=iconv("utf-8", "windows-1251//IGNORE", $text);    
    $url='http://smsc.ru/sys/send.php';
    $data['login']='epc';
    $data['psw']='ghjgecr';
    $data['phones']=$phone;
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
    print $result;
    curl_close($ch);
	// return 1;
}
?>