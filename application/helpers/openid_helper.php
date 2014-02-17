<?

function openid_object() {
	return new LightOpenID('http://ec2-122-248-217-213.ap-southeast-1.compute.amazonaws.com/');
}
function openid_login_link($sv, $redirect_uri) {
	$openid = openid_object();
	switch($sv) {
		case "yahoo":
			$openid->identity = 'https://me.yahoo.com';
			break;
		case "gmail":
			$openid->identity = 'https://www.google.com/accounts/o8/id';
			break;
	}
	$openid->required = array('contact/email', 'namePerson');
	//$openid->optional = array('namePerson', 'namePerson/friendly');
	$openid->returnUrl = $redirect_uri;
	return $openid->authUrl();
}
?>