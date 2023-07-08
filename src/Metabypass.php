<?php

namespace Metabypass;

use ErrorException;
use Metabypass\Auth\Auth;
use Metabypass\Services\CaptchaSolver;
use Metabypass\Services\reCaptcha;

class Metabypass extends Auth{

    public $reCaptchaV2_result=null;
    public $reCaptchaV3_result=null;
    public $reCaptchaInvisible_result=null;
    public $imageCaptcha_result=null;

    //services
    use CaptchaSolver,reCaptcha;


    //image captcha requester
    public function imageCaptcha($image,$numeric=0,$minLen=0,$maxLen=0){

        //check image is file or base64
        if(file_exists($image)){
            try{
                $context=file_get_contents($image);
                $base64EncodedFile=base64_encode($context);
            }catch(ErrorException $e){
                $this->logger->error($e);
                return false;
            }
        }elseif(is_base64_formart($image)){
            $base64EncodedFile=$image;
        }else{
            $this->logger->error('error1 invalid image file path or invalid base64 of image file');
            return false;
        }

        return $this->imageCaptchaRequester($base64EncodedFile,$numeric,$minLen,$maxLen);
    }

    //simple reCaptcha v2 requester without handle get result
    public function reCaptchaV2($url,$siteKey){

        //this is just API caller for developers
        return $this->reCaptchaV2Requester($url,$siteKey);
    }

    //reCaptcha v2 requester & get result handler
    public function reCaptchaV2Handler($url,$siteKey){

        //request reCaptcha v2 API
        $reCaptchaResponse=$this->reCaptchaV2Requester($url,$siteKey);

        if(!$reCaptchaResponse){
            return false;
        }

        if(empty($reCaptchaResponse->data->RecaptchaId)){
            $this->logger->error('invalid reCaptcha v2 response.RecaptchaId not found in response body. '.$reCaptchaResponse->message);
            return false;
        }


        //handle get result (max: 60 seconds)
        for($i=0;$i<10;$i++){

            // sleep 10 seconds to get result
            sleep(10);

            //request get result API
            $result=$this->reCaptchaV2GetResultRequester($reCaptchaResponse->data->RecaptchaId);
            //var_dump($result); //show get result response

            if($result->status_code==200){
                break;
            }else{
                $this->reCaptchaV2_result=false;
                echo "reCAPTCHA result not ready. wait 10 seconds again ... (to disable this message go to src/Metabypass.php file and comment line ".__LINE__.")\n";
            }
        }

        return $result;
    }

    //reCaptcha v3 requester
    public function reCaptchaV3($url,$siteKey){
        return $this->reCaptchaV3Requester($url,$siteKey);
    }

    //reCaptcha invisible requester
    public function reCaptchaInvisible($url,$siteKey){
        return $this->reCaptchaInvisibleRequester($url,$siteKey);
    }





}
