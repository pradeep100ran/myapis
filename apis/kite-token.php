<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
$user_id=$request_id=$otp_type=$otp=$error_msg='';
if(isset($_GET['user_id'])){
$user_id=real_escape($_GET['user_id']);
}
if(isset($_GET['request_id'])){
$request_id=real_escape($_GET['request_id']);
}
if(isset($_GET['otp_type'])){
$otp_type=real_escape($_GET['otp_type']);
}
if(isset($_GET['otp'])){
$otp=real_escape($_GET['otp']);
}

if($user_id==null){
$error_msg="USER ID can't be Empty";
}
if($request_id==null){
$error_msg="Request Id CAN'T BE EMPTY";
}

if($otp_type==null){
$error_msg="OTP TYPE can't be Empty";
}
if($otp==null){
$error_msg="OTP CAN'T BE EMPTY";
}

$output=hrc('401','failed',$error_msg,'');
if($error_msg==''){
$output=kite_token_request_maker($user_id,$request_id,$otp_type,$otp);
}
output($output);

function kite_token_request_maker($user_id,$request_id,$otp_type,$otp){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/api/twofa');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "user_id=".$user_id."&request_id=".$request_id."&twofa_value=".$otp."&twofa_type=".$otp_type."&skip_session=");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$cookies = array();
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch); 
if (preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches)) {
foreach ($matches[1] as $cookie) {
parse_str($cookie, $cookieArr);
$cookies += $cookieArr;
}
$response = substr($response, strpos($response, "\r\n\r\n") + 4);
}
$received_cookie = json_encode($cookies);
curl_close($ch);
$output=enc_handler($response,$received_cookie);
return $output;
}

function enc_handler($result,$cookie){
$json = json_decode($result);
$status=$json->status;
if($status=='error'){
$message=$json->message;
$output=hrc('401','failed',$message,'');
}
if($status=='success'){
$message='SUCCESS';
$cookie=json_decode($cookie,true);
$enctoken=($cookie['enctoken']);
$enctoken=urlencode(str_replace(' ', '+',$enctoken));
$data=array(
'enctoken'=>$enctoken  
    );
$output=hrc('200','success',$message,$data);
}
return $output;
}
?>