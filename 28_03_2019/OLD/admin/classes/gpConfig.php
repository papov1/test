<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '230473965772-aeesp6plnv3uihjc1uin1e1gbgb5b9s0.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'sz3bgXBTLH212B_hTLpoI7_9'; //Google client secret
$redirectURL = 'https://datenblatt.online/admin/'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('login_with_google_using_php');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
