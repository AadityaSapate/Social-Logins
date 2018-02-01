<?php
//start session
session_start();


include_once 'twConfig.php';
include_once 'User.php';

//If OAuth token not matched
if(isset($_REQUEST['oauth_token']) && $_SESSION['token'] !== $_REQUEST['oauth_token']){
	//Remove token from session
	unset($_SESSION['token']);
	unset($_SESSION['token_secret']);
}

//If user already verified 
if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified' && !empty($_SESSION['request_vars'])){
	//Retrive variables from session
	$username 		  = $_SESSION['request_vars']['screen_name'];
	$twitterId		  = $_SESSION['request_vars']['user_id'];
	$oauthToken 	  = $_SESSION['request_vars']['oauth_token'];
	$oauthTokenSecret = $_SESSION['request_vars']['oauth_token_secret'];
	$profilePicture	  = $_SESSION['userData']['picture'];
         $firstN = $_SESSION['userData']['first_name'];
         $lastN = $_SESSION['userData']['last_name'];          
 $email = $_SESSION['userData']['email'];
        $description = $_SESSION['userData']['description'];
        $location = $_SESSION['userData']['location'];	
        $link = $_SESSION['userData']['link'];	
/*
	 * Prepare output to show to the user
	 */
	$twClient = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
	
        
		$output = '<h2>Twitter Profile Details </h2><br>';   
		$output .= '<img src="'.$profilePicture.'" width="120" height="110"/></img><br>';
	    $output .= 'Screen Name : '.$username.'<br>';
        $output .= 'Name : '.$firstN.' '.$lastN.'<br>';
        $output .= 'Twitter ID : '.$twitterId.'<br>';
        $output .= 'Email : '.$email.'<br>';	$output .= 'Description : '.$description.'<br>';
       $output .= 'Location : '.$location.'<br>';
       $output .= 'Logout From Twitter : <a href="logout.php">Logout</a><br>';
       $output .= 'visit profile : <a href="'.$link.'">Visit</a>';

}elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']){
	//Call Twitter API
	$twClient = new TwitterOAuth($consumerKey, $consumerSecret, $_SESSION['token'] , $_SESSION['token_secret']);
	
	//Get OAuth token
	$access_token = $twClient->getAccessToken($_REQUEST['oauth_verifier']);
	
	//If returns success
	if($twClient->http_code == '200'){
		//Storing access token data into session
		$_SESSION['status'] = 'verified';
		$_SESSION['request_vars'] = $access_token;
		
		//Get user profile data from twitter
		$userInfo = $twClient->get('account/verify_credentials', ['include_entities' => true, 'skip_status' => true, 'include_email' => true, 'include_location' => true]);

		//Initialize User class
		$user = new User();
		
		//Insert or update user data to the database
		$name = explode(" ",$userInfo->name);
		$fname = isset($name[0])?$name[0]:'';
		$lname = isset($name[1])?$name[1]:'';
		$profileLink = 'https://twitter.com/'.$userInfo->screen_name;
                 $email = $userInfo->email;	
	    $description = $userInfo->description;
          	$location = $userInfo->location;

	$twUserData = array(
			'oauth_provider'=> 'twitter',
			'oauth_uid'     => $userInfo->id,
			'first_name'    => $fname,
			'last_name'     => $lname,
			'email'         => $email,
			'description'        => $description,
			'location'        => $location,
			'picture'       => $userInfo->profile_image_url,
			'link'          => $profileLink,
			'username'		=> $userInfo->screen_name
		);
		
		$userData = $user->checkUser($twUserData);
		
		//Storing user data into session
		$_SESSION['userData'] = $userData;
		
		//Remove oauth token and secret from session
		unset($_SESSION['token']);
		unset($_SESSION['token_secret']);
		
		//Redirect the user back to the same page
		header('Location: ./');
	}else{
		$output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
	}
}else{
	//Fresh authentication
	$twClient = new TwitterOAuth($consumerKey, $consumerSecret);
	$request_token = $twClient->getRequestToken($redirectURL);
	
	//Received token info from twitter
	$_SESSION['token']		 = $request_token['oauth_token'];
	$_SESSION['token_secret']= $request_token['oauth_token_secret'];
	
	//If authentication returns success
	if($twClient->http_code == '200'){
		//Get twitter oauth url
		$authUrl = $twClient->getAuthorizeURL($request_token['oauth_token']);
		
		//Display twitter login button

		$output = '<center><h1>Login With Twitter!!!</h1><br></center><center><a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/sign-in-with-twitter.png" width="151" height="50" border="0" /></a></center>';
	}else{
		$output = '<h3 style="color:red">Error connecting to twitter! try again later!</h3>';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login</title>
<style type="text/css">
body
{
margin : 0;
background-color:grey;
}

</style>
</head>
<body>
	<!-- Display login button / profile information -->
	<?php echo $output; ?>
</body>
</html>
