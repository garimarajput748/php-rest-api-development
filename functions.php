<?php

function apiCall($url='',$method="GET",$data='', $header=array()){
    $curl = curl_init();
    $curl_parameter_arr = array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    );
    if($method=="POST") {
      $curl_parameter_arr[CURLOPT_CUSTOMREQUEST] = 'POST';
    }else if($method=="PUT"){
      $curl_parameter_arr[CURLOPT_CUSTOMREQUEST] = 'PUT';
    }else if($method=="DELETE"){
      $curl_parameter_arr[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    }else{
      $curl_parameter_arr[CURLOPT_CUSTOMREQUEST] = 'GET';
    }

    if(!empty($header) && is_array($header)) {
      $curl_parameter_arr[CURLOPT_HTTPHEADER] = $header;
    }
    
    curl_setopt_array($curl, $curl_parameter_arr); 


    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}