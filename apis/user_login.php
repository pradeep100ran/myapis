<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
?>
<?php
if(isset($_GET['number'])) {
$number =real_escape($_GET['number']);
}
else{
   $number= '';
}
if(isset($_GET['password'])) {
$password =real_escape($_GET['password']);
}
else{
   $password= '';
}
if($number==null or $password==null){
$data=array();
$data=hrc('311','failed',"Username or Password Can't be Empty",$data);
$data=json_encode($data);
echo($data);
return;
}
$data=userlogin($number,$password);
$a=json_encode($data);
echo($a);
?>