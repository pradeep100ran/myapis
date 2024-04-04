<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");



require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
$json=file_get_contents('php://input');
data_validator($json);




function data_validator($json){
if($_SERVER['REQUEST_METHOD']!= 'POST'){
$data=hrc('405','failed','Mehod Not Allowed','');
output($data);
}
json_validator($json);

}


function json_validator($json){
if(is_json($json)==0){
$data=hrc('422','failed','Provided data is a json data','');
output($data); 
}

json_inside_data($json);
}


function json_inside_data($json){
$act='';
$valid_act=array('get_watchlist','update_watchlist','open_position','closed_position','order_history','new_order');
if(isset($_GET['action'])){
$act=real_escape($_GET['action']);
}
if($act==''){
$data=hrc('423','failed','Url Parameters is not found','');
output($data); 
}
if(!in_array($act,$valid_act)){
$data=hrc('424','failed',$act.' is not a valid parameter','');
output($data);
}
json_ittrate($act,$json);
}


function json_ittrate($act,$json){
$basket_id=1;
$error_msg='';
$key=json_decode($json,true);
$keys=array_keys($key[0]);
$json=json_decode($json);
foreach($json as $i => $iv) {
foreach($iv as $m=>$am){
$$m=real_escape2($am);
if(is_numeric($am)){
 $$m=$am;
}
}
}

if(!isset($userid)){
$error_msg="User Id can't be empty";
}

if(!isset($login_hash)){
$error_msg="Login Hash can't be empty";
}
if($error_msg==''){
$system_hash=md5(md5($userid));
if($system_hash!=$login_hash){
$data=hrc('401','failed', 'You are Not Logged In.','');
output($data);
}
}

if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
output($data);
}


if($act=='get_watchlist'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}
if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if($error_msg==''){
$data=get_watchlist_from_userid($userid,$basket_id);
}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}
}
if($act=='update_watchlist'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}
if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if(!isset($watchlist_id)){
$error_msg="Watchlist Id can't be empty";
}
if(!isset($title_hash)){
$error_msg="Title hash can't be empty";
}

if(!isset($action)){
$error_msg="Perform Action can't be empty";
}

if($error_msg==''){
$data=update_watchlist($userid,$basket_id,$watchlist_id,$title_hash,$action);
}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}




}
if($act=='open_position'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}
if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if($error_msg==''){
$data=get_open_position_from_database($userid,$basket_id);
//$data=hrc('200','success','success',$td);
}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}
}
if($act=='closed_position'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}
if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if($error_msg==''){
$data=get_closed_position($userid,$basket_id);
//$data=hrc('200','success','success',$data);
}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}
}
if($act=='order_history'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}
if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if($error_msg==''){
$data=order_history($userid,$basket_id);
//$data=hrc('200','success','success',$data);
}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}
}
if($act=='new_order'){
if(!isset($userid)){
$error_msg="User Id can't be empty";
}

if(!isset($basket_id)){
$error_msg="Basket Id can't be empty";
}
if(!isset($title_hash)){
$error_msg="Title Hash can't be empty";
}
if(!isset($price)){
$error_msg="Price can't be empty";
}
if(!isset($price_hash)){
$error_msg="Price hash can't be empty";
}
if(!isset($timestamp)){
$error_msg="Timestamp can't be empty";
}

if(!isset($type)){
$error_msg="Order Type can't be empty";
}


if($error_msg==''){
$data=order_handler_root($userid,$basket_id,$title_hash,$price,$price_hash,$timestamp,$quantity,$type);

}
if($error_msg!=''){
$data=hrc('422','failed',$error_msg,'');
}

//send_notification($userid,json_encode($data));
//$data='Order Execution Details is send through a spreat websocket connection. This is Under beta testing.';//this is marked empty just to check our local websocket how it works;
//$data=json_encode($data);
}
output($data);
}
?>