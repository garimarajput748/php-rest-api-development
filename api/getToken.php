<?php
/**
 * if method type is POST and data is pass from form-data means CURLOPT_POSTFIELDS is not in json format then 
 * data will be avilable in the $_POST
 * 
 * if data is pass as json encode then read the data from file_get_contents('php://input');
 * 
 * During create a API we are strict to follow then standard,
 * always pass the data in json format if not found in json then we are not perform the any operation.
 */

$entityBody = file_get_contents('php://input');
if(empty($entityBody)) return_response();


try{
    $receivedData = json_decode($entityBody,true);
    addUser($receivedData);
}catch(Exception $e){
    return_response("Fail","Something went wrong",json_encode($e->getMessage()));
}



function return_response($status='fail', $message="Data not found", $data='') {    
    echo json_encode(["status"=>$status,"message"=>$message,"data"=>$data]);
    exit; 
}


function addUser($receivedData=array()){
    require_once("db.php");
    $connection = new db();
    if(!empty($receivedData['username'])) $username = $receivedData['username'];
    else return_response("Fail","Please Provide User name",json_encode($receivedData));

    if(!empty($receivedData['name'])) $name = $receivedData['name'];
    else return_response("Fail","Please Provide name. For more info visit our API documentation or contact with administration",json_encode($receivedData));

    if(!empty($receivedData['email'])){
        $email = $receivedData['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))   
            return_response("Fail","Please Provide Valid email",json_encode($receivedData));
    } 
    if(!empty($receivedData['password'])) $password = $receivedData['password'];

    $pass_data['username'] = $username;
    $pass_data['firstname'] = $name;
    $pass_data['email'] = $email;
    
    $userFound = $connection->select("user_master"," AND username='$username'");
    if(!empty($userFound)){
        $insertID = $userFound[0]['auto_id'];
    }else{
        $insertID = $connection->insertdata("user_master",$pass_data);
    }

    $token = base64_encode(json_encode("###".$insertID."###".$name."###".$username."$"));

    if(!empty( $insertID))  return_response("Success","User Is registered",$token);
    else return_response();

}

