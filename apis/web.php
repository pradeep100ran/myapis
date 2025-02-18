<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: application/json; charset=utf-8');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'id19957279_fnodata');
define('DB_PASSWORD', 'Pradeep@123#');
define('DB_NAME', 'id19957279_fno');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


function get_profile($userid){
$output=0;
$link=$GLOBALS["link"];
$sql="SELECT name,number,email FROM user WHERE userid='".$userid."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$name=$row["name"];
$number=$row["number"];
$email=$row["email"];
$output=array();
$data=array(
'name'=>$name,
'number'=>$number,
'email'=>$email);
$output[]=$data;
}
}
if($output==0){
$code=401;
$output=array(
'error_code'=>$code,
'error'=>'unauthorized',
'status'=>'failed');
}
return json_encode($output);
}


//var_dump(get_profile(1));


function real_escape($value) {
    $return = '';
    for($i = 0; $i < strlen($value); ++$i) {
        $char = $value[$i];
        $ord = ord($char);
        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
            $return .= $char;
        else
            $return .= '\\x' . dechex($ord);
    }
    return $return;
}



function real_escape2($value){
$value = preg_replace("/[^a-zA-Z0-9]/", "",$value);
return $value;
}



function hrc($response_code,$response_status,$message,$data){
$data=array(
'response_code'=>$response_code,
'response_status'=>$response_status,
'message'=>$message,
'data'=>array($data));
return $data;
}





function userlogin($number,$pass){
$output=hrc('401','failed', 'unauthorised','');
$link=$GLOBALS["link"];
$sql="SELECT userid,name,number,email,password FROM user WHERE number='".$number."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$output=hrc('402','failed','Password is Wrong','');
$userid=$row["userid"];
$name=$row["name"];
$number=$row["number"];
$email=$row["email"];
$password=$row["password"];
$hash=md5(md5($userid));
if($password==$pass){
$data=array(
'userid'=>$userid,
'name'=>$name,
'number'=>$number,
'email'=>$email,
'login_hash'=>$hash);
$output=hrc('200','success','success',$data);
}
}
}
return $output;
}




function check_user_register($name,$number,$email,$password){
$output=0;
$link=$GLOBALS["link"];
$sql="SELECT userid,name,password FROM user WHERE number='".$number."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$output=hrc('402','failed', 'Already Exists','');
}
}
return $output;
}


function user_register($name,$number,$email,$password){
$link=$GLOBALS["link"];
$time=time()+19800;
$userid=$time.crc32($number);
$sql ="INSERT INTO user (userid,name,number,email,password,funds,balance,expiry,trial,time) VALUES ('$userid','$name','$number','$email','$password','0','0','0','0','$time')";
$query=mysqli_query($link,$sql);
$output=hrc('200','success', 'Registration Successful','');
return $output;
}

function get_title_and_kite_title_hash_from_title_hash($title_hash){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT title,instrument_token,name,expiry,strike,tick_size,lot_size,instrument_type,segment,exchange,expiry_timestamp FROM token WHERE title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$instrument_token=($row["instrument_token"]);
$name=($row["name"]);
$expiry=($row["expiry"]);
$strike=($row["strike"]);
$tick_size=($row["tick_size"]);
$lot_size=($row["lot_size"]);
$instrument_type=($row["instrument_type"]);
$segment=($row["segment"]);
$exchange=($row["exchange"]);
$expiry_timestamp=($row["expiry_timestamp"]);
$order_data=array(
'title'=>$title,
'kite_title_hash'=>$instrument_token,
'name'=>$name,
'expiry'=>$expiry,
'strike'=>$strike,
'tick_size'=>$tick_size,
'lot_size'=>$lot_size,
'instrument_type'=>$instrument_type,
'segment'=>$segment,
'exchange'=>$exchange,
'expiry_timestamp'=>$expiry_timestamp);
$result[]=$order_data;
}
}
return $result;
}





function check_script_in_watchlist($userid,$basket_id,$watchlist_id,$title_hash){
$result=0;
$link=$GLOBALS["link"];
$sql="SELECT userid FROM watchlist WHERE title_hash='".$title_hash."'and basket_id='".$basket_id."' and watchlist_id='".$watchlist_id."' and userid='".$userid."'";
$query=mysqli_query($link,$sql);
$result=mysqli_num_rows($query);
return $result;
}


function get_spot_token($tradingsymbol){
if($tradingsymbol=='NIFTY'){
 $tradingsymbol='NIFTY 50';
}
if($tradingsymbol=='BANKNIFTY'){
 $tradingsymbol='NIFTY BANK';
}
if($tradingsymbol=='FINNIFTY'){
 $tradingsymbol='NIFTY FIN SERVICE';
}
$token='0';
$exchange='NSE';
//$sql="SELECT instrument_token FROM token WHERE tradingsymbol='$tradingsymbol' AND exchange='$exchange'";
$sql="SELECT instrument_token FROM token WHERE tradingsymbol='$tradingsymbol'";
$link=$GLOBALS["link"];
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$instrument_token=($row["instrument_token"]);
if($instrument_token!=null){
$token=$instrument_token;
}
}
}
return $token;
}



function add_script_to_watchlist($userid,$basket_id,$watchlist_id,$title_hash){
$output=array();
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
$valid=(count($data));
if($valid==0){
$output=hrc('251','failed','Invalid Title Hash',$output);
return $output;
}
foreach ($data as $row) {
$title=$row['title'];
$kite_title_hash=($row["kite_title_hash"]);
$name=($row["name"]);
$expiry=$row["expiry"];
$strike=($row["strike"]);
$lot_size=($row["lot_size"]);
$instrument_type=$row["instrument_type"];
$segment=($row["segment"]);
$exchange=($row["exchange"]);
}
$spot_token=0;
if($exchange=='NFO' or $exchange=='BFO'){
$spot_token=get_spot_token($name);
}
$expiry_timestamp=$row["expiry_timestamp"];
$link=$GLOBALS["link"];
$sql ="INSERT INTO watchlist (userid,basket_id,watchlist_id,title,title_hash,kite_title_hash,strike,lot_size,instrument_type,segment,exchange,spot_token,expiry_timestamp) VALUES ('$userid','$basket_id','$watchlist_id','$title','$title_hash','$kite_title_hash','$strike','$lot_size','$instrument_type','$segment','$exchange','$spot_token','$expiry_timestamp')";
$query=mysqli_query($link,$sql);
$output=hrc('200','success','success',$output);
return $output;
}


function delete_script_from_watchlist($userid,$basket_id,$watchlist_id,$title_hash){
$output=array();
$link=$GLOBALS["link"];
$sql="DELETE FROM watchlist WHERE userid='".$userid."' and basket_id='".$basket_id."' and watchlist_id='".$watchlist_id."' and title_hash='".$title_hash."'";  
$query=mysqli_query($link,$sql);
$output=hrc('200','success','success',$output);
return $output;
}




function get_watchlist_from_userid($userid,$basket_id){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT watchlist_id,title,title_hash,kite_title_hash,strike,lot_size,instrument_type,segment,exchange,spot_token,expiry_timestamp FROM watchlist WHERE userid='".$userid."' and basket_id='".$basket_id."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$watchlist_id=($row["watchlist_id"]);
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
$kite_title_hash=($row["kite_title_hash"]);
$strike=($row["strike"]);
$lot_size=$row["lot_size"];
$instrument_type=$row["instrument_type"];
$segment=$row["segment"];
$exchange=$row["exchange"];
$spot_token=$row["spot_token"];
$expiry_timestamp=$row["expiry_timestamp"];
$price_decimal=price_decimal($exchange);
$order_data=array(
'watchlist_id'=>$watchlist_id,
'title'=>$title,
'title_hash'=>$title_hash,
'kite_title_hash'=>$kite_title_hash,
'strike'=>$strike,
'lot_size'=>$lot_size,
'instrument_type'=>$instrument_type,
'segment'=>$segment,
'exchange'=>$exchange,
'expiry_timestamp'=>$expiry_timestamp,
'spot_token'=>$spot_token
//,'price_decimal'=>$price_decimal
);
$result[]=$order_data;
}
}
return $result;
}

function price_decimal($exchange){
$price_decimal=2;
if($exchange=='BCD'){
$price_decimal=4;
}
if($exchange=='CDS'){
$price_decimal=7;
}
return $price_decimal;
}



function get_open_position_from_databasee($userid, $basket_id) {
    $link = $GLOBALS["link"];
    $result =array();
    $sql = "SELECT title, title_hash, buy_value, buy_quantity, sell_value, sell_quantity, ltp FROM openposition WHERE userid='".$userid."' AND basket_id='".$basket_id."'";
    $query = mysqli_query($link, $sql);

    if(mysqli_num_rows($query) > 0) {
        while($row = $query->fetch_assoc()) {
            $extra_data = get_title_and_kite_title_hash_from_title_hash($row["title_hash"])[0] ?? [];
            $decimal = ($extra_data["exchange"] == 'BCD') ? 4 : 2;
            $price = deci(($row["buy_value"] - $row["sell_value"]) / ($row["buy_quantity"] - $row["sell_quantity"]), $decimal);
            $quantity = $row["buy_quantity"] - $row["sell_quantity"];
            $pl = deci(($row["ltp"] - $price) * $quantity, $decimal);

            $order_data = [
                'title' => $row["title"],
                'title_hash' => $row["title_hash"],
                'kite_title_hash' => $extra_data["kite_title_hash"],
                'lot_size' => $extra_data["lot_size"],
                'segment' => $extra_data["segment"],
                'exchange' => $extra_data["exchange"],
                'price' => $price,
                'quantity' => $quantity
            ];

            $result[] = $order_data;
        }
    }
    return $result;
}


function get_open_position_from_database($userid,$basket_id){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT title,title_hash,buy_value,buy_quantity,sell_value,sell_quantity,ltp FROM openposition WHERE userid='".$userid."' and basket_id='".$basket_id."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
//$extra_data=get_title_and_kite_title_hash_from_title_hash($title_hash);
$extra_data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($extra_data as $r) {
$kite_title_hash=($r["kite_title_hash"]);
$lot_size=($r["lot_size"]);
$segment=($r["segment"]);
$exchange=($r["exchange"]);
$price_decimal=price_decimal($exchange);
$decimal=2;
if($exchange=='BCD'||$exchange=='CDS'){
$decimal=4;
}

}
$ltp=($row["ltp"]);
$buy_value=
deci($row["buy_value"],$decimal);
$sell_value=
deci($row["sell_value"],$decimal);
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
$price=deci((($buy_value-$sell_value)/($buy_quantity-$sell_quantity)),$decimal);
$quantity=$buy_quantity-$sell_quantity;
$pl=deci(($ltp-$price)*$quantity,$decimal);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'kite_title_hash'=>$kite_title_hash,
'lot_size'=>$lot_size,
'segment'=>$segment,
'exchange'=>$exchange,
'price'=>$price,
'quantity'=>$quantity
//,'price_decimal'=>$price_decimal
);
$result[]=$order_data;
}
}
return $result;
}


function get_closed_position($userid,$basket_id){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT title,title_hash, exchange,buy_value,buy_quantity,sell_value,sell_quantity,buy_time,sell_time,flow FROM closeposition WHERE userid='".$userid."' and basket_id='".$basket_id."'";
$query=mysqli_query($link,$sql);
$response=mysqli_num_rows($query);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title_hash=($row["title_hash"]);
$title=$value=($row["title"]);
$exchange=$value=($row["exchange"]);
$buy_value=
($row["buy_value"]);
$sell_value=
($row["sell_value"]);
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
$buy_time=($row["buy_time"]);
$sell_time=($row["sell_time"]);
$flow=($row["flow"]);
$decimal=2;
if($exchange=='BCD'||$exchange=='CDS'){
$decimal=4;
}
$buy_value=
deci($buy_value,$decimal);
$sell_value=
deci($sell_value,$decimal);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'exchange'=>$exchange,
'buy_value'=>$buy_value,
'sell_value'=>$sell_value,
'buy_quantity'=>$buy_quantity,
'sell_quantity'=>$sell_quantity,
'buy_time'=>$buy_time,
'sell_time'=>$sell_time,
'flow'=>$flow);
$result[]=$order_data;
}
}
return $result;
}

function order_history($userid,$basket_id){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT title,title_hash,exchange,order_number,order_type,status,buy_value,buy_quantity,sell_value,sell_quantity,time FROM orders WHERE userid='".$userid."' and basket_id='".$basket_id."'";
$query=mysqli_query($link,$sql);
$response=mysqli_num_rows($query);
if($response==0){
}
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title_hash=($row["title_hash"]);
$exchange=$row['exchange'];
$title=($row["title"]);
$order_number=
($row["order_number"]);
$order_type=
($row["order_type"]);
$status=
($row["status"]);

$decimal=2;
if($exchange=='BCD'||$exchange=='CDS'){
$decimal=4;
}
$buy_value=
deci($row["buy_value"],$decimal);
$sell_value=
deci($row["sell_value"],$decimal);
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
$time=($row["time"]);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'exchange'=>$exchange,
'buy_value'=>$buy_value,
'sell_value'=>$sell_value,
'buy_quantity'=>$buy_quantity,
'sell_quantity'=>$sell_quantity,
'order_type'=>$order_type,
'order_number'=>$order_number,
'time'=>$time,
'status'=>$status);
$result[]=$order_data;
}
}
return $result;
}



function order_history_beta($userid, $basket_id) {
    $link = $GLOBALS["link"];
    $result = array();
    $sql = "SELECT title, title_hash, exchange, order_number, order_type, status, buy_value, buy_quantity, sell_value, sell_quantity, time FROM orders WHERE userid='" . $userid . "' AND basket_id='" . $basket_id . "'";
    $query = mysqli_query($link, $sql);
    $response = mysqli_num_rows($query);
    
    if ($response == 0) {
        return array(
            'count' => 0,
            'available_count' => 0,
            'data' => $result
        );
    }
    
    $num_rows = mysqli_num_rows($query);
    $max_results = 10;
    $count = min($num_rows, $max_results);
    
    while ($row = $query->fetch_assoc()) {
        $title_hash = ($row["title_hash"]);
        $exchange = $row['exchange'];
        $title = ($row["title"]);
        $order_number = ($row["order_number"]);
        $order_type = ($row["order_type"]);
        $status = ($row["status"]);

        $decimal = 2;
        if ($exchange == 'BCD') {
            $decimal = 4;
        }
        
        $buy_value = deci($row["buy_value"], $decimal);
        $sell_value = deci($row["sell_value"], $decimal);
        $buy_quantity = ($row["buy_quantity"]);
        $sell_quantity = ($row["sell_quantity"]);
        $time = ($row["time"]);
        
        $order_data = array(
            'title' => $title,
            'title_hash' => $title_hash,
            'exchange' => $exchange,
            'buy_value' => $buy_value,
            'sell_value' => $sell_value,
            'buy_quantity' => $buy_quantity,
            'sell_quantity' => $sell_quantity,
            'order_type' => $order_type,
            'order_number' => $order_number,
            'time' => $time,
            'status' => $status
        );
        
        $result[] = $order_data;
if (count($result) >= $max_results) {
            break;
        }
    }
    
    return array(
        'count' => $count,
        'available_count' => $num_rows,
        'data' => $result
    );
}



function get_kite_token_from_database($userid){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT userid,kite_token,time FROM kitetoken WHERE userid='".$userid."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$kite_token=($row["kite_token"]);
$result[]=$kite_token;
$time=($row["time"]);
$result[]=$time;
}
}
return $result;
}


function convert_title_hash_into_kite_title_hash($title_hash){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT instrument_token FROM token WHERE title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$instrument_token=($row["instrument_token"]);
$result[]=$instrument_token;
}
}
return $result;
}



function convert_title_hash_into_kite_title_hashh($title_hash){
$link=$GLOBALS["link"];
$result=array();
$sql="SELECT kite_title_hash FROM expirydata WHERE title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$kite_title_hash=($row["kite_title_hash"]);
$result[]=$kite_title_hash;
}
}
return $result;
}


function search_script($input){
$input = explode(" ", $input);
$link=$GLOBALS["link"];
$result=array();
//$sql = "SELECT * FROM expirydata WHERE title LIKE '$input' LIMIT 5";

$sql = "SELECT * FROM expirydata WHERE (title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%') LIMIT 5";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$id=($row["id"]);
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
$kite_title_hash=($row["kite_title_hash"]);
$segment=$value=($row["segment"]);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'segment'=>$segment
 );
$result[]=$order_data;
//$limit++;
  }
}
return $result;
}




function search_script2_down($input){
$input2='%'.$input.'%';
$input = explode(" ", $input);
$link=$GLOBALS["link"];
$result=array();
//$sql = "SELECT * FROM token WHERE (title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%') LIMIT 5";

$sql = "SELECT * FROM token WHERE (title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%') order by (title = '$input2') desc, length(title) LIMIT 5";


//$sql = "SELECT * FROM token WHERE  (title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%') AND (tradingsymbol LIKE '%" . implode("%' AND tradingsymbol LIKE '%",$input) . "%') order by (title = '$input2') desc, length(title) LIMIT 5";

//$sql = "SELECT * FROM token WHERE(tradingsymbol LIKE '%" . implode("%' AND tradingsymbol LIKE '%",$input) . "%') order by (title = '$input2') desc, length(title) LIMIT 5";




$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
$segment=$value=($row["segment"]);
$exchange=$value=($row["exchange"]);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'segment'=>$segment,
'exchange'=>$exchange
 );
$result[]=$order_data;
//$limit++;
  }
}
return $result;
}



function search_script22($input){
$input2='%'.$input.'%';
$input = explode(" ", $input);
echo(json_encode($input).'<br>');
$link=$GLOBALS["link"];
$result=array();
$sql = "SELECT * FROM token WHERE title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%' order by (title = '$input2') desc, length(title) LIMIT 5";

echo($sql);

$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
$segment=$value=($row["segment"]);
$exchange=$value=($row["exchange"]);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'segment'=>$segment,
'exchange'=>$exchange
 );
$result[]=$order_data;
  }
}
return $result;
}


function search_script2($input){
$input2='%'.$input.'%';
$input = explode(" ", $input);
//echo(json_encode($input).'<br>');
$link=$GLOBALS["link"];

$si0="title LIKE '%" . implode("%' AND title LIKE '%",$input). "%'";
$si1="tradingsymbol LIKE '%" . implode("%' AND tradingsymbol LIKE '%",$input). "%'";
//$si2="instrument_token LIKE '%" . implode("%' AND instrument_token LIKE '%",$input). "%'";
$si=$si0.' OR '.$si1;
$result=array();
//$sql = "SELECT * FROM token WHERE title LIKE '%" . implode("%' AND title LIKE '%",$input) . "%' order by (title = '$input2') desc, length(title) LIMIT 5";

$sql = "SELECT * FROM token WHERE ".$si." order by (title = '$input2') desc, length(title) LIMIT 5";
//echo($sql);

$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$title_hash=($row["title_hash"]);
$segment=$value=($row["segment"]);
$exchange=$value=($row["exchange"]);
$order_data=array(
'title'=>$title,
'title_hash'=>$title_hash,
'segment'=>$segment,
'exchange'=>$exchange
 );
$result[]=$order_data;
  }
}
return $result;
}


function check_order_in_option_position($userid,$basket_id,$title_hash){
$result=0;
$link=$GLOBALS["link"];
$sql ="SELECT userid,basket_id,title_hash FROM openposition WHERE userid='".$userid."' and basket_id='".$basket_id."' and title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
$result=mysqli_num_rows($query);
return $result;
}


function new_order_in_orders($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value){
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($data as $row) {
$title=$row['title'];
$exchange=$row['exchange'];
}
$decimal=2;
if($exchange=='BCD'||$exchange=='CDS'){
$decimal=4;
}
$buy_value=deci($buy_value,$decimal);
$sell_value=deci($sell_value,$decimal);
$order_number=(date("ymdHis",time()+19800)).rand(1000,9999);
$order_type='MARKET';
$status='SUCCESS';
$link=$GLOBALS["link"];
$time=time()+19800;
$sql="INSERT INTO `orders`(`userid`, `basket_id`, `order_number`, `order_type`, `status`, `title`, `title_hash`,`exchange`,`buy_quantity`, `buy_value`, `sell_quantity`, `sell_value`, `time`) VALUES ('$userid','$basket_id','$order_number','$order_type','$status','$title','$title_hash','$exchange','$buy_quantity','$buy_value','$sell_quantity','$sell_value','$time')";
mysqli_query($link,$sql);
$time=(date("h:i:s A d-M-Y",$time));
$order_data=array(
'order_status'=>$status,
'title'=>$title,
'order_number'=>$order_number,
'order_type'=>$order_type,
'buy_value'=>$buy_value,
'sell_value'=>$sell_value,
'buy_quantity'=>$buy_quantity,
'sell_quantity'=>$sell_quantity,
'time'=>$time);
return $order_data;
}

function new_order_in_open_position($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value,$ltp){
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($data as $row) {
$title=$row['title'];
$expiry_timestamp=$row['expiry_timestamp'];
}
$result='failed';
$link=$GLOBALS["link"];
$time=$last_updated=time()+19800;
$sql ="INSERT INTO openposition (userid,basket_id,title,title_hash,buy_quantity,buy_value,sell_quantity,sell_value,ltp,expiry_timestamp,last_updated,time) VALUES ('$userid','$basket_id','$title','$title_hash','$buy_quantity','$buy_value','$sell_quantity','$sell_value','$ltp','$expiry_timestamp','$last_updated','$time')";
mysqli_query($link,$sql);
$result='success';
return $result;
}

function update_order_in_open_position($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value){
$last_updated=time()+19800;
$link=$GLOBALS["link"];
$sql="UPDATE openposition SET buy_quantity=buy_quantity+'".$buy_quantity."',buy_value=buy_value+'".$buy_value."',sell_quantity=sell_quantity+'".$sell_quantity."',sell_value=sell_value+'".$sell_value."' WHERE userid='".$userid."' and basket_id='".$basket_id."' and title_hash='".$title_hash."'";
mysqli_query($link,$sql);
return 'success';
}


function new_order_in_closed_position($userid,$basket_id,$title_hash,$buy_value,$sell_value,$quantity,$time,$flow){
$buy_time='';
$sell_time='';
$new_flow='';
if($flow=='SELL'){
$buy_time=$time;
$sell_time=time()+19800;
$new_flow='BS';
}
if($flow=='BUY'){
$buy_time=time()+19800;
$sell_time=$time;
$new_flow='SB';
}
$output=0;
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($data as $row) {
$title=$row['title'];
$exchange=$row['exchange'];
}
$last_updated=time()+19800;
$link=$GLOBALS["link"];
$sql ="INSERT INTO closeposition (userid,basket_id,title,title_hash,exchange,buy_quantity,buy_value,sell_quantity,sell_value,buy_time,sell_time,flow) VALUES ('$userid','$basket_id','$title','$title_hash','$exchange','$quantity','$buy_value','$quantity','$sell_value','$buy_time','$sell_time','$new_flow')";
//mysqli_query($link,$sql);
if(mysqli_query($link, $sql)){
$output=$sql;
}
else{
$output="Error: " . mysqli_error($link);
}
return $output;
}






function delete_order_from_open_position($userid,$basket_id,$title_hash){
$link=$GLOBALS["link"];
$sql ="SELECT buy_quantity,sell_quantity FROM openposition WHERE userid='".$userid."' and basket_id='".$basket_id."' and title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
if($buy_quantity==0 and $sell_quantity==0){
$sql="DELETE FROM openposition WHERE userid='".$userid."' and basket_id='".$basket_id."' and title_hash='".$title_hash."'";    
mysqli_query($link,$sql);
}
}
}
return 'success';
}

function twoo_decimal_place($number){
return sprintf('%0.2f', $number);
}

function two_decimal_place($number) {
    return number_format($number, 4, '.', '');
}


function deci($number,$decimal) {
    return number_format($number,$decimal, '.', '');
}



function find_sqare_off_quantity($userid,$basket_id,$title_hash){
$result=array();
$link=$GLOBALS["link"];
$sql ="SELECT buy_quantity,buy_value,sell_quantity,sell_value,time FROM openposition WHERE userid='".$userid."' and basket_id='".$basket_id."' and title_hash='".$title_hash."'";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
$time=($row["time"]);
$quantity=min($buy_quantity,$sell_quantity);
$buy_value=$sell_value='0.00';
if($buy_quantity!=0){
$buy_value=two_decimal_place(($row["buy_value"]*($quantity))/($buy_quantity));
}
if($sell_quantity!=0){
$sell_value=two_decimal_place(($row["sell_value"]*($quantity))/($sell_quantity));
}

$data=array(
'buy_value'=>$buy_value,
'sell_value'=>$sell_value,
'square_off_quantity'=>$quantity,
'time'=>$time);
$result[]=$data;
}
}
if($quantity>0){
update_order_in_open_position($userid,$basket_id,$title_hash,-$quantity,-$buy_value,-$quantity,-$sell_value);
}
return $result;
}








function option_chain($title_hash){
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($data as $row) {
$name=($row["name"]);
$expiry=($row["expiry"]);
$segment=($row["segment"]);
}


$output=array();
$link=$GLOBALS["link"];
$sql='SELECT * FROM token WHERE  expiry="'.$expiry.'" AND name="'.$name.'" AND segment="'.$segment.'"';
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$title=$value=($row["title"]);
$instrument_token=($row["instrument_token"]);
$name=($row["name"]);
$strike=($row["strike"]);
$lot_size=($row["lot_size"]);
$instrument_type=($row["instrument_type"]);
$segment=($row["segment"]);
$exchange=($row["exchange"]);
$order_data=array(
'title'=>$title,
'kite_title_hash'=>$instrument_token,
'name'=>$name,
'strike'=>$strike,
'lot_size'=>$lot_size,
'instrument_type'=>$instrument_type,
'segment'=>$segment,
'exchange'=>$exchange);
$output[]=$order_data;
}
}
return $output;
}





function expiry_maker($name){
$output=array();
$link=$GLOBALS["link"];
$sql='SELECT * FROM token WHERE   name="'.$name.'" AND exchange="NFO"';
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
$expiry=$row["expiry"];
$insert=1;
if(in_array($expiry,$output)){
$insert=0;
}
if($insert==1){
array_push($output,$expiry);
}
}
}
sort($output);
$output=json_encode($output);
return $output;
}
//echo(expiry_maker('FINNIFTY'));










function get_ltp($instrument_token_array,$token){
$instrument_token_raw='';
$strt=0;
$lim=count($instrument_token_array);
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
$ltp=two_decimal_place($ltp);
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








function auto_sq_position(){
$max=0;
$order_array=array();
$link=$GLOBALS["link"];
$instrument_token_array=array();
$result=array();
$sql="SELECT userid,basket_id,title,title_hash,buy_value,buy_quantity,sell_value,sell_quantity,ltp, expiry_timestamp FROM openposition";
$query=mysqli_query($link,$sql);
if(mysqli_num_rows($query) > 0){
while($row = $query->fetch_assoc()) {
if($max==400){
 break;
}
$max=$max+1;
$userid=($row["userid"]);
$basket_id=($row["basket_id"]);
$title_hash=($row["title_hash"]);
$buy_quantity=($row["buy_quantity"]);
$sell_quantity=($row["sell_quantity"]);
$net_quantity=$buy_quantity-$sell_quantity;
$type='BUY';
if($net_quantity<0){
$type='SELL';
}
$quantity=abs($net_quantity);
$extra_data=get_title_and_kite_title_hash_from_title_hash($title_hash);
foreach ($extra_data as $r) {
$kite_title_hash=($r["kite_title_hash"]);
}
$expiry_timestamp=($row["expiry_timestamp"]);
if($expiry_timestamp!=0){
$diff=time()+19800-$expiry_timestamp;
if($diff>=1800){
//if($diff==$diff){
array_push($instrument_token_array,$kite_title_hash);
$order_data=array(
'userid'=>$userid,
'basket_id'=>$basket_id,
'title_hash'=>$title_hash,
'kite_title_hash'=>$kite_title_hash,
'quantity'=>$quantity,
'type'=>$type);
$order_array[]=$order_data;
}
}
}
}
return $order_array;
}







function totp($secret) {
    $time = floor(time() / 30); // Current time divided by the time step (30 seconds)

    // Pack the timestamp into binary format
    $timestamp = pack('J', $time);

    // Convert the secret from Base32 to binary
    $secret = base32_decode($secret);

    // Generate the HMAC-SHA1 hash
    $hash = hash_hmac('sha1', $timestamp, $secret, true);

    // Get the offset value
    $offset = ord(substr($hash, -1)) & 0xF;

    // Extract the 4 bytes at the offset
    $code = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;

    // Generate a 6-digit TOTP code
    $totp = $code % 1000000;

    return sprintf('%06d', $totp);
}

function base32_decode($base32) {
    $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $base32CharsFlipped = array_flip(str_split($base32Chars));

    $output = '';

    $v = 0;
    $vbits = 0;

    for ($i = 0, $j = strlen($base32); $i < $j; $i++) {
        $v <<= 5;
        $v += $base32CharsFlipped[$base32[$i]];
        $vbits += 5;

        if ($vbits >= 8) {
            $vbits -= 8;
            $output .= chr(($v & (0xFF << $vbits)) >> $vbits);
        }
    }

    return $output;
}


function ltp_matcher($ltp_array,$instrument_token){
$root='https://'.getenv('HTTP_HOST');
$final_ltp='0.00';
foreach ($ltp_array as $k) {
$token=$k["instrument_token"];
$ltp=$k["ltp"];
if($token==$instrument_token){
$final_ltp=$ltp;
break;
}
}
return $final_ltp;
}
function order_place($userid,$basket_id,$title_hash,$ltp,$quantity,$type){
if($ltp<=0){
 $ltp=0.05;
}
$root='https://'.getenv('HTTP_HOST');
file_get_contents($root.'/apis/order_process.php?userid='.$userid.'&basket_id='.$basket_id.'&title_hash='.$title_hash.'&price='.$ltp.'&quantity='.$quantity.'&type='.$type);
}






function direct_totp(){
$userid='UT1881';
$password='Sheoran@%2312345@%23';
$root='https://'.getenv('HTTP_HOST');
$secret = 'BIFUJKENU7OFZRED4NDUA24NU7MSKPYE';
$url=$root.'/apis/kite-otp.php?user_id='.$userid.'&password='.$password;
$temp_token=file_get_contents($url);
$totp =totp($secret);
$enctoken=file_get_contents($root.'/apis/kite-token.php?user_id='.$userid.'&token='.$temp_token.'&otp='.$totp);
return (urldecode($enctoken));
}


function output($data){
$data=json_encode($data);
//header('Content-Type: application/json; charset=utf-8');
echo($data);
exit;
}
function is_json($string) {
  return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
}


function is_valid($variable){
$output=0;
if($variable!=null && $variable!= "" && $variable!==0){
$output=1;
}
return $output;
} 

function update_watchlist($userid,$basket_id,$watchlist_id,$title_hash,$action){
if($action!='add'&&$action!='remove'){
$data=hrc('430','failed',$action.' is not a valid parameter','');
}

if($action=='add'or $action=='remove'){
if ($watchlist_id < 1 || $watchlist_id > 5) {
$data = hrc('250', 'failed', 'Watchlist Id Must be between 1 and 5', '');
    }
else{
if($action=='add'){
$found=1;
$found=check_script_in_watchlist($userid,$basket_id,$watchlist_id,$title_hash);
if($found==0){
$data=add_script_to_watchlist($userid,$basket_id,$watchlist_id,$title_hash);
}
if($found!=0){
$data=hrc('250','failed','Already available in your Watchlist','');
}
}
if($action=='remove'){
$data=delete_script_from_watchlist($userid,$basket_id,$watchlist_id,$title_hash);
}
}
}
return $data;
}







function order_handler_root($userid,$basket_id,$title_hash,$price,$price_hash,$timestamp,$quantity,$type){ 
$error_msg='';
$quantity=floor(abs($quantity));
//echo($price);
if(is_numeric($price) && $price<=0){
$error_msg="PRICE CAN'T BE ZERO";
}
$ok_price=is_ok_price($price,$price_hash,$timestamp);
if($ok_price['status']==0){
$error_msg=$ok_price['msg'];
}
if(is_numeric($quantity) && $quantity<=0){
$error_msg="QUALITY CAN'T BE ZERO";
}

if($quantity>100000){
//$error_msg="MAX QUANTITY CAN'T BE GREATER THAN 1 LAKH";
}
$order_value=$price*$quantity;
if($order_value>1000000){
//$error_msg="MAX ORDER VALUE CAN'T BE GREATER THAN 10 LAKH";
}

$tim=time()+19800;
$time=(date("h:i:s A d-M-Y",$tim));
$buy_value=$sell_value=$ltp=0.00;
$buy_quantity=$sell_quantity=0;
if($type!='BUY' && $type!='SELL'){
$error_msg='Please Enter Valid Buy or Sell Type';
}
if($type=="BUY"){
$buy_quantity=$quantity;
$buy_value=($quantity*$price);
}
if($type=="SELL"){
$sell_quantity=$quantity;
$sell_value=($quantity*$price);
}
$data=get_title_and_kite_title_hash_from_title_hash($title_hash);
if(count($data)===0){
$error_msg='THIS SCRIPT IS NO LONGER VALID';
}
foreach ($data as $row) {
$expiry_timestamp=($row["expiry_timestamp"]);
$tick_size=$row['tick_size'];
if($tick_size==0){
 $error_msg='THIS SCRIPT IS NOT TRADABLE';
$tick_size=1;
}
$lot_size=$row['lot_size'];
if($lot_size==0){
 $error_msg='THIS SCRIPT IS NOT TRADABLE';
$lot_size=1;
}

if($error_msg==''){
$valid_quantity=(abs($quantity))%$lot_size;
$factor=10000;
$is_tick_true=($price*$factor) % ($tick_size*$factor);
if($is_tick_true!=0){
$error_msg='PLEASE ENTER PRICE IN THE MULTIPLE OF '.$tick_size;
}
if($valid_quantity!=0){
$error_msg='PLEASE ENTER QUANTITY IN THE MULTIPLE OF '.$lot_size;
}
$expiry_gap=$expiry_timestamp-$tim;
if($expiry_timestamp==0){
$expiry_gap=1;
}
if($expiry_gap<=0){
$error_msg='THIS SCRIPT IS EXPIRED FOR TRADING';
}
}
$valid=(count($data));
if($valid==0){
$error_msg='TRADING IS NOT ALLOWED IN THIS SCRIPT';
}
}
if($error_msg!=''){
$inside_data=array(
'order_status'=>'FAILED',
'title'=>'REASON:'.$error_msg,
'order_number'=>"NOT REQUIRED",
'order_type'=>'MARKET',
'buy_value'=>$buy_value,
'sell_value'=>$sell_value,
'buy_quantity'=>$buy_quantity,
'sell_quantity'=>$sell_quantity,
'time'=>$time);
$data=hrc('301','failed','failed',$inside_data);
}
if($error_msg==''){
$process_output=new_order_in_orders($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value);
$order_found=check_order_in_option_position($userid,$basket_id,$title_hash);
if($order_found==0){
new_order_in_open_position($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value,$ltp);
}
if($order_found==1){
update_order_in_open_position($userid,$basket_id,$title_hash,$buy_quantity,$buy_value,$sell_quantity,$sell_value);
$data=find_sqare_off_quantity($userid,$basket_id,$title_hash);
foreach ($data as $row) {
$buy_value=($row["buy_value"]);
$sell_value=($row["sell_value"]);
$square_off_quantity=($row["square_off_quantity"]);
$time=($row["time"]);
}
if($square_off_quantity>0){
$flow=$type;
new_order_in_closed_position($userid,$basket_id,$title_hash,$buy_value,$sell_value,$square_off_quantity,$time,$flow);
}
}
delete_order_from_open_position($userid,$basket_id,$title_hash);
$data=hrc('200','success','success',$process_output);
}
return $data;
}


function is_ok_price($price,$price_hash,$timestamp){
$status=0;
$msg='';
$server_time=time();
$gap=$server_time-abs($timestamp);
if($gap>=30){
$msg='TIME IS NOT MATCHED WITH OUR SYSTEM';
}
if($msg==''){
$cal_hash=md5($price.$timestamp);
if($cal_hash!=$price_hash){
$msg='PRICE HASH IS NOT VERIFIED';
}
}
if($msg==''){
$msg='success';
$status=1;
}
$output=array(
'status'=>$status,
'msg'=>$msg
);
return $output;
}



function is_ok_pricee($price,$price_hash,$timestamp){
$verify=0;
$t0=time();
$t1=time()-1;
$t2=time()+1;


$cal_hash=md5($price.$t0);
$cal_hash1=md5($price.$t1);
$cal_hash2=md5($price.$t2);
if($cal_hash==$price_hash){
$verify=$verify+1;
}
if($cal_hash1==$price_hash){
$verify=$verify+1;
}
if($cal_hash2==$price_hash){
$verify=$verify+1;
}

$verify=0;
return $verify;
}




function b10_b62($num){
$charset="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$rtn="";

$n=$num;$base=62;
while($n>0){
    $temp=$n%$base;
    $rtn=$charset[$temp].$rtn;
    $n=intval($n/$base);
}
 return $rtn;
}





function send_notification($userid,$msg){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://flawless-valley-harbor.glitch.me/send-message/'.$userid);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"message\":\"'.$msg.'\"}");
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{"message":"'.$msg.'"}');

curl_setopt($ch, CURLOPT_POSTFIELDS,''.$msg.'');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: demo-and-review.000webhostapp.com';
$headers[] = 'Accept: */*';
$headers[] = 'Accept-Language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Origin: https://demo-and-review.000webhostapp.com';
$headers[] = 'Referer: https://demo-and-review.000webhostapp.com/';
$headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"107\", \"Not=A?Brand\";v=\"24\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?1';
$headers[] = 'Sec-Ch-Ua-Platform: \"Android\"';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 11; M2101K7AI) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Mobile Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
//echo($result);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
 
 
}



function decode_number($number) {
    //$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $base = strlen($characters);
    $output = '';

    while ($number > 0) {
        $index = $number % $base;
        $output = $characters[$index] . $output;
        $number = (int)($number / $base);
    }

    return $output;
}
?>