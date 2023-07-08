<?php

namespace Metabypass\Services;

use Metabypass\Helpers\Request;

trait reCaptcha{


    //reCaptcha v2 requester
    public function reCaptchaV2Requester($url,$siteKey){

        $request_url = "https://app.metabypass.tech/CaptchaSolver/api/v1/services/bypassReCaptcha";

        $params=[
            "url"=>$url,
            "version"=>2,
            "sitekey"=>$siteKey,
        ];

        $headers=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$this->access_token,
            'Accept'=>'application/json'
        ];

        //send request to metabypass
        $response=Request::send($request_url,$params,'POST',$headers);

        //check response
        if(empty($response)){
            $message='error! server response is empty';
            $this->logger->error($message);
            return false;
        }

        $responseHeaders=json_decode($response->headers);
        $responseBody=json_decode($response->body);

        if($responseHeaders->http_code==200){
            return $responseBody;
        }elseif($responseHeaders->http_code==401){
            $status=$this->generateAccessToken();
            if($status==false){
                echo 'unauth';
                die();
            }
            return $this->v2Requester($url,$siteKey);
        }else{
            $message='error! request reCaptcha v2';
            $this->logger->error($message);
            return false;
        }
    }

    //reCaptcha v3 requester
    public function reCaptchaV3Requester($url,$siteKey){

        $request_url = "https://app.metabypass.tech/CaptchaSolver/api/v1/services/bypassReCaptcha";

        $params=[
            "url"=>$url,
            "version"=>3,
            "sitekey"=>$siteKey,
        ];

        $headers=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$this->access_token,
            'Accept'=>'application/json'
        ];


        //send request to metabypass
        $response=Request::send($request_url,$params,'POST',$headers);

        //check response
        if(empty($response)){
            $message='error! server response is empty';
            $this->logger->error($message);
            return false;
        }

        $responseHeaders=json_decode($response->headers);
        $responseBody=json_decode($response->body);

        if($responseHeaders->http_code==200){

            if($responseBody->status_code==200){

                $this->reCaptchaV3_result=!empty($responseBody->data->RecaptchaResponse) ? $responseBody->data->RecaptchaResponse : null;
            }

            return $responseBody;
        }elseif($responseHeaders->http_code==401){
            $status=$this->generateAccessToken();
            if($status==false){
                echo 'unauth';
                die();
            }
            return $this->v2Requester($url,$siteKey);
        }else{
            $message='error! request reCaptcha v3';
            $this->logger->error($message);
            return false;
        }
    }

    //reCaptcha get result requester
    public function reCaptchaV2GetResultRequester($reCaptchaId){

        $request_url = "https://app.metabypass.tech/CaptchaSolver/api/v1/services/getCaptchaResult";

        $params=[
            "recaptcha_id"=>$reCaptchaId,
        ];

        $headers=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$this->access_token,
            'Accept'=>'application/json'
        ];

        //send request to metabypass
        $response=Request::send($request_url,$params,'GET',$headers);

        //check response
        if(empty($response)){
            $message='error! server response is empty';
            $this->logger->error($message);
            return false;
        }

        $responseHeaders=json_decode($response->headers);
        $responseBody=json_decode($response->body);

        if($responseHeaders->http_code==200){

            if($responseBody->status_code==200){
                $this->reCaptchaV2_result=!empty($responseBody->data->RecaptchaResponse) ? $responseBody->data->RecaptchaResponse : null;
            }

            return $responseBody;

        }elseif($responseHeaders->http_code==401){
            $status=$this->generateAccessToken();
            if($status==false){
                echo 'unauth';
                die();
            }
            return $this->getReCaptchaResultRequester($reCaptchaId);
        }else{
            $message='error! get reCaptchaResult';
            $this->logger->error($message);
            return false;
        }
    }

    //invisible reCaptcha requester
    public function reCaptchaInvisibleRequester($url,$siteKey){

        $request_url = "https://app.metabypass.tech/CaptchaSolver/api/v1/services/bypassReCaptcha";

        $params=[
            "url"=>$url,
            "version"=>'invisible',
            "sitekey"=>$siteKey,
        ];

        $headers=[
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$this->access_token,
            'Accept'=>'application/json'
        ];


        //send request to metabypass
        $response=Request::send($request_url,$params,'POST',$headers);

        //check response
        if(empty($response)){
            $message='error! server response is empty';
            $this->logger->error($message);
            return false;
        }

        $responseHeaders=json_decode($response->headers);
        $responseBody=json_decode($response->body);

        if($responseHeaders->http_code==200){

            if($responseBody->status_code==200){

                $this->reCaptchaInvisible_result=!empty($responseBody->data->RecaptchaResponse) ? $responseBody->data->RecaptchaResponse : null;
            }

            return $responseBody;
        }elseif($responseHeaders->http_code==401){
            $status=$this->generateAccessToken();
            if($status==false){
                echo 'unauth';
                die();
            }
            return $this->v2Requester($url,$siteKey);
        }else{
            $message='error! request invisible reCaptcha';
            $this->logger->error($message);
            return false;
        }
    }


}
