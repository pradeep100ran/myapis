<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
?>
<?php
if(isset($_GET['name'])) {
$name =real_escape($_GET['name']);
}
else{
   $name= '';
}

if(isset($_GET['number'])) {
$number =real_escape($_GET['number']);
}
else{
   $number= '';
}

if(isset($_GET['email'])){
$email =real_escape($_GET['email']);
}
else{
   $email= '';
}

if(isset($_GET['password'])) {
$password =real_escape($_GET['password']);
}
else{
   $password= '';
}

$data=check_user_register($name,$number,$email,$password);

if($data==0){
$data=user_register($name,$number,$email,$password);
}
$a=json_encode($data);
echo($a);
?>