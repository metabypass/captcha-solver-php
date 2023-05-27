<?php

namespace Metabypass\Services;

use Metabypass\Helpers\Request;

trait CaptchaSolver{

    //image captcha requester
    public function imageCaptchaRequester($image,$numeric=0,$minLen=0,$maxLen=0){


        $request_url = "https://app.metabypass.tech/CaptchaSolver/api/v1/services/captchaSolver";

        $params=[
            "image"=>$image,
            "numeric"=>$numeric,
            "min_len"=>$minLen,
            "max_len"=>$maxLen,
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
            $this->imageCaptcha_result=!empty($responseBody->data->result) ? $responseBody->data->result : null;
            return $responseBody;
        }elseif($responseHeaders->http_code==401){
            $status=$this->generateAccessToken();
            if($status==false){
                echo 'unauth';
                die();
            }
            return $this->imageCaptcha($image);
        }else{
            $message='error! image captcha';
            $this->logger->error($message);
            return false;
        }
    }
}