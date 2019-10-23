<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '230473965772-eomfj37mimvqgseqlo607f10p2b2vp1v.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'cWNwQIpBcn7usZAaXMODaakU'; //Google client secret
$redirectURL = 'http://3.16.24.200/pandora/'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('login_with_google_using_php');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
