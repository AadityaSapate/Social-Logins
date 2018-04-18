<?php
//Include LinkedIn client library 
require_once 'src/http.php';
require_once 'src/oauth_client.php';

/*
 * Configuration and setup LinkedIn API
 */
$apiKey = '';
$apiSecret = '';
$redirectURL = '';
$scope = 'r_basicprofile r_emailaddress'; //API permissions
?>
