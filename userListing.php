<?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
   require_once("functions.php");
   
   $data['username'] = "MAyank";
   $data['Name'] = "Mayank"; 
   $data['email'] = "test@gmail.com"; 
   $data['password'] = "123";
   
   echo '<br>MAYANK '.basename(__FILE__).' '.__LINE__.'<pre> data :: '; print_r(json_encode($data)); echo '</pre>'; exit;
   //$response_token = apiCall("http://localhost/project-1/api/getToken.php","POST",$data);
   
   
$response = apiCall("http://localhost/project-1/api/getUser.php","GET",'',array('client-secret: mytempToken'));

if(empty($response)) die("data Not found");


$response_arr = json_decode($response,true);
if ($response_arr['status']==="success") {
    foreach($response_arr['data'] as $dataValue) {
        echo "=====================<br/>";
        foreach($dataValue as $key=>$value) 
            echo "$key : ".$value."</br>";
    }
}else{
    echo $response_arr['message'];
}


