<?php
if(!session_id()){
    session_start();
}

//Include Twitter client library 
include_once 'src/twitteroauth.php';

/*
 * Configuration and setup Twitter API
 */
$consumerKey = '9YkpjnKmwpigvCDg7fDpdXi4i';
$consumerSecret = 'ibuEIKdrr5PS6H24zl7ZaeR72GgeVEhvoWneBmekGlzsmfRu0X';
$redirectURL = 'http://18.217.131.163/twitter/';

?>
