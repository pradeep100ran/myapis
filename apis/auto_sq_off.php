<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
$enc_token=direct_totp();
function sq_off_root(){
$enc_token=$GLOBALS["enc_token"];
$instrument_token_array=array();
$order_list=auto_sq_position();
if(empty($order_list)){
 die('done');
}
foreach ($order_list as $r) {
$kite_title_hash=($r["kite_title_hash"]);
array_push($instrument_token_array,$kite_title_hash);
}
$ltp_array=(get_ltp($instrument_token_array,$enc_token));
foreach ($order_list as $k) {
$userid=$k["userid"];
$basket_id=$k["basket_id"];
$title_hash=$k["title_hash"];
$quantity=($k["quantity"]);
$type=($k["type"]);
$alt_type='';
if($type=='BUY'){
$alt_type='SELL';
}
if($type=='SELL'){
$alt_type='BUY';
}
$kite_title_hash=($k["kite_title_hash"]);
$ltp=(ltp_matcher($ltp_array,$kite_title_hash));
order_place($userid,$basket_id,$title_hash,$ltp,$quantity,$alt_type);
}
}

$strt=0;
while($strt<=$strt){
sq_off_root();
$strt++;
}
?>