<?php
require_once("db.php");
$db_connection  = new db();

$response_data_arr = array();

$allHeaders_arr = getallheaders();
    if(!empty($allHeaders_arr) && is_array($allHeaders_arr) && array_key_exists("client-secret",$allHeaders_arr) ) {
    
        $userId = explode("###",json_decode(base64_decode($allHeaders_arr['client-secret']),true))[1];
        $userFound = $db_connection->select("user_master"," AND auto_id='$userId'");
        
    if(empty($userFound)){
        $token_found = $allHeaders_arr['client-secret'];
        $response_data_arr['status'] = 'fail';
        $response_data_arr['http_status_code'] = '404';
        $response_data_arr['message'] = "Authentication Required, Token mismatch";
    }else{
        try{
            $wp_users = $db_connection->select("user_master");
            if(!empty($wp_users)) {
                $response_data_arr['status'] = 'success';
                $response_data_arr['data'] = $wp_users;
            }
        }catch(Exception $e){ 
            $response_data_arr['status'] = 'fail';
            $response_data_arr['message'] = $e->getMessage();
        }
    }

}else{
    $response_data_arr['status'] = 'fail';
    $response_data_arr['message'] = "Token Not found";
}




echo json_encode($response_data_arr);
exit;

