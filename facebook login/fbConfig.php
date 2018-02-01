<?php
if(!session_id()){
	session_start();
}


require_once __DIR__ . '/facebook-php-sdk/autoload.php';


use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/*
 * Configuration and setup Facebook SDK
 */
$appId 			= '142740246424809'; 
$appSecret 		= 'f48ed4254d1c394cd909b066654410b5'; 
$redirectURL 	= 'http://ec2-18-217-131-163.us-east-2.compute.amazonaws.com/'; 
$fbPermissions 	= array('email');  s

$fb = new Facebook(array(
	'app_id' => $appId,
	'app_secret' => $appSecret,
	'default_graph_version' => 'v2.2',
));

// Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

//  get access token
try {
	if(isset($_SESSION['facebook_access_token'])){
		$accessToken = $_SESSION['facebook_access_token'];
	}else{
  		$accessToken = $helper->getAccessToken();
	}
} catch(FacebookResponseException $e) {
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(FacebookSDKException $e) {
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
}

?>
