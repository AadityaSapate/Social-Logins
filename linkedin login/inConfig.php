<?php
//Include LinkedIn client library 
require_once 'src/http.php';
require_once 'src/oauth_client.php';

/*
 * Configuration and setup LinkedIn API
 */
$apiKey = '8119ruk86qvrqi';
$apiSecret = 'ymr1zaNWZaxvqFcs';
$redirectURL = 'http://ec2-18-217-131-163.us-east-2.compute.amazonaws.com/linkedin/';
$scope = 'r_basicprofile r_emailaddress'; //API permissions
?>
