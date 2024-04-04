<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$instrument_token_array=array();
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';


$result=array();
$sql="SELECT * FROM token LIMIT 7000,400";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$instrument_token=($row["instrument_token"]);
$exchange=($row["exchange"]);
$name=($row["name"]);
//echo($instrument_token.'<br>');
echo($exchange.'<br>');
array_push($instrument_token_array,$instrument_token);
}
}

//array_push($instrument_token_array,256265);

//echo(json_encode($instrument_token_array));

$tok='JM5nq7e5yDq5/OnTC8XMWNHKn5FRfRb0GEzSAgwSvg/4a1bCM7mOeLDwlZ1EGimnHlkWx7xBlilLoyAjwwQWySk8C2iTVs/Xb9Y2XYokZo7dydOLp9478g==';

//$tok=urlencode($tok);
//echo($tok);
echo(json_encode(get_ltpp($instrument_token_array,$tok)));

?>









<?php


function get_ltpp($instrument_token_array,$token){
$instrument_token_raw='';
$strt=0;
$lim=count($instrument_token_array);
//echo($lim);
while($strt<$lim){
$n=$instrument_token_array[$strt];
$n_final='&i='.$n;
if($strt==0){
$n_final='i='.$n;
}
$instrument_token_raw=$instrument_token_raw.$n_final;
$strt++;
}
$output=array();
$ltp=0;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/oms/quote?'.$instrument_token_raw);
curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/oms/quote/ltp?'.$instrument_token_raw);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$headers = array();
$headers[] = 'Authorization: enctoken '.$token;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
//echo($result);
$data = json_decode($result,true);
if ($data && isset($data['data'])) {
$dataArray = $data['data'];
foreach ($dataArray as $item) {
$instrument_token= $item['instrument_token'];
$ltp= $item['last_price'];
//$ltp=two_decimal_place($ltp);
$data=array(
'instrument_token'=>$instrument_token,
'ltp'=>$ltp);
$output[]=$data;
}
}
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
return $output;
}

?>