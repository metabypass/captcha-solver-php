<?php

namespace Metabypass\Auth;

use Metabypass\Helpers\Request;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Auth{

    public $logger;
    protected $email;
    protected $password;
    protected $clientId;
    protected $clientSecret;
    protected $access_token;
    protected $access_token_file_path=__DIR__.'/metabypass.token';
    

    public function __construct($clientId,$clientSecret,$email,$password)
    {
        //init logger system
        $this->logger = new Logger('metabypass_logger');
        $this->logger->pushHandler(new StreamHandler('metabypass_errors.log',Logger::DEBUG));

        //credentials
        $this->clientId=$clientId;
        $this->clientSecret=$clientSecret;
        $this->password=$password;
        $this->email=$email;


        #generate access token
        if(file_exists($this->access_token_file_path)){
            $access_token=file_get_contents($this->access_token_file_path);
        }else{
            $access_token=$this->generateAccessToken($clientId,$clientSecret,$email,$password);
        }

        if(!$access_token){
            return false;
        }

        $this->access_token=$access_token;

    }

    
    //get new access_token
    public function generateAccessToken(){
       
        $request_url = "https://app.metabypass.tech/CaptchaSolver/oauth/token";

        $params=[
            "grant_type"=>"password" ,
            "client_id"=>$this->clientId,
            "client_secret"=>$this->clientSecret,
            "username"=>$this->email,
            "password"=>$this->password
        ];

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $response=Request::send($request_url,$params,'POST',$headers);

        if(empty($response)){
            $message='error! server response is empty';
            $this->logger->error($message);
            return false;
        }

        $headers=json_decode($response->headers);
        $body=json_decode($response->body);

        if($headers->http_code==200){
            $this->access_token=$body->access_token;
            file_put_contents($this->access_token_file_path,$body->access_token);
            return $body->access_token;
        }else{

            $message='error! unauth';
            $this->logger->error($message);
            return false;
        }

    }
}