<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
$user_id=$password=$captcha_id=$captcha=$error_msg='';
if(isset($_GET['user_id'])){
$user_id=real_escape($_GET['user_id']);
}

if(isset($_GET['password'])){
$password=real_escape($_GET['password']);
}

if(isset($_GET['captcha_id'])){
$captcha_id=real_escape($_GET['captcha_id']);
}
if(isset($_GET['captcha'])){
$captcha=real_escape($_GET['captcha']);
}
if($captcha_id!='' and $captcha==''){
$captcha=rand(1000000,999999); 
}



if($user_id==null){
$error_msg="USER ID can't be Empty";
}
if($password==null){
$error_msg="PASSWORD CAN'T BE EMPTY";
}



$output=hrc('401','failed',$error_msg,'');
if($error_msg==''){
$output=kite_otp_request_maker($user_id,$password,$captcha_id,$captcha);
}
output($output);





function kite_otp_request_maker($user_id,$password,$captcha_id,$captcha){
$password=urldecode($password);
if($user_id!=null and $password!=null){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/api/login');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
if($captcha_id=='' and $captcha==''){
curl_setopt($ch, CURLOPT_POSTFIELDS, "user_id=".$user_id."&password=".$password);
}
if($captcha_id!='' and $captcha!=''){
curl_setopt($ch, CURLOPT_POSTFIELDS, "user_id=".$user_id."&password=".$password."&captcha_id=".$captcha_id."&captcha=".$captcha);
}
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
$output=kite_apis_login_root($result);
//output($output);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
}
return $output;
}


function kite_apis_login_root($result){
$json = json_decode( $result);
$status=$json->status;
if($status=='error'){
$output=kite_apis_login_error($json);
}
if($status=='success'){
$output=kite_apis_login_success($json);
}
return $output;
}
function kite_apis_login_error($json){
$message=$json->message;
$output='';
if($message==''){
$output=hrc('401','failed','Something Went Wrong','');
}
if($message!=''){
$output=hrc('401','failed',$message,'');
}
if($message=='Invalid username or password.'){
$output=hrc('401','failed','USER ID or PASSWORD IS WRONG','');
}
if($message=='Invalid username.'){
$output=hrc('401','failed','USER ID IS WRONG','');
}
if($message=='Invalid CAPTCHA values.'){
$captcha=0;
$captcha= $json->data->captcha;
if($captcha=='true'){
$captcha=1;
}
$data=array(
'captcha'=>$captcha
);
$output=hrc('401','failed','PLEASE SOLVE CAPTCHA AND TRY WITH CORRECT PASSWORD',$data);
}
return $output;
}
function kite_apis_login_success($json){
$user_id= $json->data->user_id;
$request_id= $json->data->request_id;
$otp_type = $json->data->twofa_type;
if($otp_type=='app_code'){
$message='OTP IS SEND SUCCESSFULLY ON YOUR ZERODHA/KITE MOBILE APPLICATION';
}
if($otp_type!='app_code'){
$message='OTP IS SEND ON YOUR AUTHENTICATOR APP';
}

$data=array(
'user_id'=>$user_id,
'request_id'=>$request_id,
'otp_type'=>$otp_type
);
$output=hrc('200','success',$message,$data);
return $output;
}
?>