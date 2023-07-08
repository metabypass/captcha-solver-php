<?php

use Metabypass\Metabypass;

require 'vendor/autoload.php';


//---------------------- CONFIGURATION ---------------------------

//get your credentials from https://app.metabypass.tech/application
$clientId='YOUR_CLIENT_ID';
$clientSecret='YOUR_CLIENT_SECRET';
$email='YOUR_EMAIL';
$password='YOUR_PASSWORD';

//metabypass instance
$metabypass=new Metabypass($clientId,$clientSecret,$email,$password);




//-------------------- IMAGE CAPTCHA SOLVER ---------------------------

// you can pass base64 encoded image file or path of image file
//$image='base64_encoded_image';
$image='./samples/icaptcha2.png';
//you can pass some optional params too. more details: https://app.metabypass.tech/docs.html?#api_3
$numeric=0; //default
$minLen=0;  //default
$maxLen=0;  //default

$server_response=$metabypass->imageCaptcha($image,$numeric,$minLen,$maxLen); //complete response in an object
var_dump($metabypass->imageCaptcha_result); //end result



//-------------------- reCAPTCHA v2 ---------------------------
// $url='SITE_URL';
// $siteKey='SITE_KEY';
// $server_response=$metabypass->reCaptchaV2Handler($url,$siteKey); //complete response in an object
// var_dump($metabypass->reCaptchaV2_result); //end result



// //-------------------- reCAPTCHA v3 ---------------------------
// $url='SITE_URL';
// $siteKey='SITE_KEY';
// $metabypass->reCaptchaV3($url,$siteKey); //complete response in an object
// var_dump($metabypass->reCaptchaV3_result); //end result


// //-------------------- reCAPTCHA Invisible ---------------------------
// $url='SITE_URL';
// $siteKey='SITE_KEY';
// $metabypass->reCaptchaInvisible($url,$siteKey); //complete response in an object
// var_dump($metabypass->reCaptchaInvisible_result); //end result
