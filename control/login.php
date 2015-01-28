<?

# import the wp environment, aka load all this overhead just to chack a password
# define("BASE_PATH", "/path/to/top/of/your/wp/install"
require('../wp-blog-header.php');



function _authenticate_wp($username, $password)
{
	global $wp_error;
	if ( empty($wp_error) ) {
    	$wp_error = new WP_Error();
	}
	$user = wp_authenticate($username, $password);
	// print_r($user);
	if(is_wp_error($user)) {
		return false;
	} else {
		return $user->ID;
	}
}

if ($_POST['log']&&$_POST['pwd']){
	$auth=_authenticate_wp ($_POST['log'],$_POST['pwd']);
	// print_r ($_SESSION['USER_AUTH']);
	// print $auth;
	if ($auth){
		// print_r($_POST);
		$_SESSION['USER_AUTH']=$_POST['log'];
		$_SESSION['USER_ID']=$auth;
	}
	else {
		print "No Access";
	}
}

if (!$_SESSION['USER_AUTH']) {

?>


<form action="" method="post">
<table  border="0" cellspacing="5" cellpadding="0" style="position:absolute; top:50%; left:50%; width:260px; margin:-100px 0 0 -130px;">
  <tr>
    <td>Login:</td>
    <td><input name="log" type="text" /></td>
    <td rowspan="2"><input name="" type="submit" value="Sign in &raquo;" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input name="pwd" type="password" /></td>
  </tr>
</table>




</form>

<? 
exit;
}
?>