<?php

namespace Metabypass\Helpers;

use stdClass;

class Request{

    private static $err=false; 
    private static $error_log_file_path='aa_err_request.log';

    //send request method
    public static function send($url,$params=[],$type='GET',array $headers=[]){

        //check valid data
        self::validateUrl($url);

        self::validHeaders($headers);

        self::validType($type);

        if(self::$err==true){

            echo "ERR! check ".self::$error_log_file_path." file\n";

            return false;

        }

        return self::createRequest($url,strtoupper($type),$params,$headers);

    }

    //request creator
    private static function createRequest($url,$type='GET',$params=[],$headers=[]){
        
        $ch=curl_init($url);
       
        //params
        if(!empty($params)){
            if(!empty($headers['Content-Type']) && strtolower($headers['Content-Type'])=='application/json'){
                curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($params));
            }else{
                curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
            }
        }

        //headers
        if($headers){
            $headers=self::convertArrayToHeaderFormat($headers);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }

        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$type);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

        $response=new stdClass;

        $response->body=curl_exec($ch);

        $response->headers=json_encode(curl_getinfo($ch));

        
        curl_close($ch);
       
        return $response;
        
    }


    //send request without waiting for response
    public static function sendRequestWithoutResponse($url,$type='get',$params=[],$headers=[]){

        $ch=curl_init($url);

        if(!empty($params)){
            if(!empty($headers['Content-Type']) && strtolower($headers['Content-Type'])=='application/json'){
                curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($params));
            }else{
                curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
            }
        }


        //headers
        if($headers){
            $headers=self::convertArrayToHeaderFormat($headers);
            curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        }

        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,strtoupper($type));
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
        curl_setopt($ch,CURLOPT_TIMEOUT,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);

        curl_exec($ch);

        $response=new stdClass;

        $response->body=null;

        $response->headers=json_encode(curl_getinfo($ch));

        curl_close($ch);
       
        return true;
    }


    //get host name
    public static function getHost(){
        $protocol=$_SERVER['SERVER_PROTOCOL'];
        if($protocol=='HTTP/1.1'){
            $protocol="http://";
        }elseif($protocol=='HTTPS/1.1'){
            $protocol="https://";
        }elseif($protocol=='HTTPS/2.1'){
            $protocol="https://";
        }elseif($protocol=='HTTPS/2.1'){
            $protocol="https://";
        }
    
        $host=$_SERVER['HTTP_HOST'];
    
        return $protocol.$host;
    }


    //convert an array to http headers format
    private static function convertArrayToHeaderFormat($headers){

        $output=array();

        foreach($headers as $key=>$value){

            $output[]="$key: $value";

        }

        return $output; //return array of headers format (["Referer: https://www.google.com/","Content-type: audio/mpeg"])

    }


    //url validations
    private static function validateUrl($url){

        if(empty($url)){

            file_put_contents(self::$error_log_file_path,"\nERR MESSAGE: url required !\t".date("d M Y H:i:s"),FILE_APPEND);

            self::$err=true;

        }

        if(!filter_var($url,FILTER_VALIDATE_URL)){

            file_put_contents(self::$error_log_file_path,"\nERR MESSAGE: Invalid url format !\t".date("d M Y H:i:s"),FILE_APPEND);

            self::$err=true;

        }

    }


    //headers validations
    private static function validHeaders($headers){

        if(!is_array($headers)){

            file_put_contents(self::$error_log_file_path,"\nERR MESSAGE: Invalid headers format! headers format should be an array\t".date("d M Y H:i:s"),FILE_APPEND);

            self::$err=true;

        }

    }


    //method type validations
    private static function validType($reqtype){

        if(empty($reqtype)){

            file_put_contents(self::$error_log_file_path,"\nERR MESSAGE: Request type required! put GET or POST or etc type\t".date("d M Y H:i:s"),FILE_APPEND);

            self::$err=true;
        }

    }







}
