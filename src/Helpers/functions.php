<?php

//check base64 str format
function is_base64_formart($string){
    if(!is_string($string)){
        return false;
    }
    // Check if there are valid base64 characters
    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

    // Decode the string in strict mode and check the results
    $decoded = base64_decode($string, true);
    if(false === $decoded) return false;

    // Encode the string again
    if(base64_encode($decoded) != $string) return false;
    
    return true;
}